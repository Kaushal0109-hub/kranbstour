<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\PackageExclusion;
use App\Models\PackageFaq;
use App\Models\PackageGalleryImage;
use App\Models\PackageHighlight;
use App\Models\PackageInclusion;
use App\Models\PackageItinerary;
use App\Models\PackageLocationTag;
use App\Models\TourCategory;
use App\Models\TourPackage;
use App\Services\ImageUploadService;
use App\Services\TourCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TourPackageController extends Controller
{
    use HandlesImageUploads;
    public function index(Request $request): View
    {
        $query = TourPackage::query()->with('category')->orderBy('sort_order');

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        return view('admin.master.packages.index', [
            'packages' => $query->paginate(20)->withQueryString(),
            'categories' => TourCategory::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.master.packages.form', [
            'package' => new TourPackage(),
            'categories' => TourCategory::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $package = TourPackage::create($data);
        $this->syncRelations($package, $request);

        return redirect()->route('admin.master.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(TourPackage $package): View
    {
        $package->load(['highlights', 'itineraries', 'inclusions', 'exclusions', 'faqs', 'locationTags', 'galleryImages']);

        return view('admin.master.packages.form', [
            'package' => $package,
            'categories' => TourCategory::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, TourPackage $package): RedirectResponse
    {
        $package->update($this->validated($request, $package));
        $this->syncRelations($package, $request);

        return redirect()
            ->route('admin.master.packages.edit', $package)
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(TourPackage $package): RedirectResponse
    {
        $package->delete();

        return back()->with('success', 'Package deleted.');
    }

    private function validated(Request $request, ?TourPackage $package = null): array
    {
        $categoryId = $request->input('category_id');

        $data = $request->validate([
            'category_id' => ['required', 'exists:tour_categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => [
                'nullable',
                'string',
                'max:200',
                Rule::unique('tour_packages', 'slug')
                    ->where(fn ($query) => $query->where('category_id', $categoryId))
                    ->ignore($package?->id),
            ],
            'duration' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_display' => ['nullable', 'string', 'max:30'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'tag' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'full_description' => ['nullable', 'string'],
            'review_count' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'featured_section' => ['nullable', 'string', 'max:50'],
            'featured_order' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['price_display'] = $data['price_display'] ?: number_format((float) $data['price'], 0);
        $data['rating'] = $data['rating'] ?? 4.8;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $this->mergeUploadedImages($request, $data, ['image'], 'packages', $package);
    }

    private function syncRelations(TourPackage $package, Request $request): void
    {
        $this->syncSimpleList($package, 'highlights', PackageHighlight::class, 'text', $request->input('highlights', []));
        $this->syncSimpleList($package, 'inclusions', PackageInclusion::class, 'text', $request->input('inclusions', []));
        $this->syncSimpleList($package, 'exclusions', PackageExclusion::class, 'text', $request->input('exclusions', []));
        $this->syncSimpleList($package, 'locationTags', PackageLocationTag::class, 'tag', $request->input('location_tags', []));

        $package->itineraries()->delete();
        foreach ($this->zipPairRows($request->input('itinerary', []), 'title', 'description') as $i => [$title, $description]) {
            if ($title === '' && $description === '') {
                continue;
            }
            PackageItinerary::create([
                'package_id' => $package->id,
                'day_number' => $i + 1,
                'title' => $title,
                'description' => $description,
                'sort_order' => $i + 1,
            ]);
        }

        $package->faqs()->delete();
        foreach ($this->zipPairRows($request->input('faqs', []), 'question', 'answer') as $i => [$question, $answer]) {
            if ($question === '') {
                continue;
            }
            PackageFaq::create([
                'package_id' => $package->id,
                'question' => $question,
                'answer' => $answer,
                'sort_order' => $i + 1,
            ]);
        }

        if ($request->hasFile('gallery_upload')) {
            $request->validate(['gallery_upload.*' => ['image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120']]);
            $uploader = app(ImageUploadService::class);
            $sort = (int) $package->galleryImages()->max('sort_order');

            foreach ($request->file('gallery_upload') as $file) {
                $sort++;
                PackageGalleryImage::create([
                    'package_id' => $package->id,
                    'image' => $uploader->store($file, 'uploads/packages/gallery'),
                    'alt' => $package->title,
                    'sort_order' => $sort,
                ]);
            }
        }
    }

    private function syncSimpleList(TourPackage $package, string $relation, string $modelClass, string $field, array $values): void
    {
        $package->{$relation}()->delete();
        foreach (array_values(array_filter(array_map('trim', $values))) as $i => $value) {
            $modelClass::create([
                'package_id' => $package->id,
                $field => $value,
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function zipPairRows(array $data, string $firstKey, string $secondKey): array
    {
        $first = $data[$firstKey] ?? [];
        $second = $data[$secondKey] ?? [];
        if (! is_array($first)) {
            $first = [];
        }
        if (! is_array($second)) {
            $second = [];
        }

        $count = max(count($first), count($second));
        $rows = [];

        for ($i = 0; $i < $count; $i++) {
            $rows[] = [trim((string) ($first[$i] ?? '')), trim((string) ($second[$i] ?? ''))];
        }

        return $rows;
    }
}
