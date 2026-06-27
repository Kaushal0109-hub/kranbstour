<?php

namespace App\Http\Controllers;

use App\Helpers\MediaHelper;
use App\Services\CatalogService;
use App\Services\TourCatalog;
use Illuminate\View\View;

class TourController extends Controller
{
    public function __construct(private CatalogService $catalog) {}

    public function show(string $category): View
    {
        $tour = $this->catalog->findCategory($category);
        abort_unless($tour, 404);

        $related = $this->catalog->relatedCategories($category);

        return view('tours.show', [
            'tour' => $tour,
            'related' => MediaHelper::resolve($related),
        ]);
    }

    public function package(string $category, string $packageSlug): View
    {
        $data = $this->catalog->findPackage($category, $packageSlug);
        abort_unless($data, 404);

        $relatedPackages = collect($data['category']['tours'] ?? [])
            ->filter(fn ($p) => TourCatalog::slugify($p['title']) !== $packageSlug)
            ->take(3)
            ->map(fn ($p) => array_merge(MediaHelper::resolve($p), [
                'slug' => TourCatalog::slugify($p['title']),
                'url' => TourCatalog::packageUrl($category, $p['title']),
            ]))
            ->values()
            ->all();

        return view('tours.package', [
            'category' => $data['category'],
            'package' => $data['package'],
            'categorySlug' => $category,
            'relatedPackages' => $relatedPackages,
            'highlightedReviews' => $this->catalog->highlightedReviews(),
        ]);
    }

    public function packages(): View
    {
        $categories = $this->catalog->categories()
            ->map(fn ($cat) => MediaHelper::resolve($cat))
            ->values()
            ->all();

        $allTours = $this->catalog->allPackages()->all();

        return view('tours.packages', compact('categories', 'allTours'));
    }
}
