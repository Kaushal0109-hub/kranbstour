<?php

namespace App\Services;

use App\Helpers\MediaHelper;
use App\Models\City;
use App\Models\HomeHighlight;
use App\Models\HomeStat;
use App\Models\Testimonial;
use App\Models\TourCategory;
use App\Models\TourPackage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class CatalogService
{
    public function useDatabase(): bool
    {
        return Schema::hasTable('tour_categories')
            && TourCategory::query()->where('is_active', true)->exists();
    }

    public function categories(): Collection
    {
        if (! $this->useDatabase()) {
            return TourCatalog::categories();
        }

        return TourCategory::query()
            ->active()
            ->with(['monuments', 'packages' => fn ($q) => $q->active()])
            ->get()
            ->mapWithKeys(fn (TourCategory $cat) => [$cat->slug => $this->categoryToArray($cat)]);
    }

    public function findCategory(string $slug): ?array
    {
        if (! $this->useDatabase()) {
            $category = config("tours.categories.{$slug}");

            return $category ? MediaHelper::resolve($category) : null;
        }

        $category = TourCategory::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['monuments', 'packages' => fn ($q) => $q->active()])
            ->first();

        return $category ? MediaHelper::resolve($this->categoryToArray($category)) : null;
    }

    public function findPackage(string $categorySlug, string $packageSlug): ?array
    {
        if (! $this->useDatabase()) {
            $category = $this->findCategory($categorySlug);
            if (! $category) {
                return null;
            }

            foreach ($category['tours'] as $package) {
                if (TourCatalog::slugify($package['title']) === $packageSlug) {
                    return [
                        'category' => $category,
                        'package' => TourCatalog::enrichPackageData(
                            config("tours.categories.{$categorySlug}"),
                            $package,
                            $packageSlug
                        ),
                    ];
                }
            }

            return null;
        }

        $category = TourCategory::query()
            ->where('slug', $categorySlug)
            ->where('is_active', true)
            ->with(['monuments', 'packages' => fn ($q) => $q->active()])
            ->first();

        if (! $category) {
            return null;
        }

        $package = $category->packages->firstWhere('slug', $packageSlug)
            ?? TourPackage::query()
                ->where('category_id', $category->id)
                ->where('slug', $packageSlug)
                ->where('is_active', true)
                ->first();

        if (! $package) {
            return null;
        }

        $package->load(['highlights', 'itineraries', 'inclusions', 'exclusions', 'faqs', 'locationTags']);

        return [
            'category' => MediaHelper::resolve($this->categoryToArray($category)),
            'package' => MediaHelper::resolve($this->enrichPackageFromDb($category, $package)),
            'package_model' => $package,
        ];
    }

    public function allPackages(): Collection
    {
        if (! $this->useDatabase()) {
            return collect(config('tours.categories', []))->flatMap(function (array $category, string $slug) {
                $resolvedCategory = MediaHelper::resolve($category);

                return collect($category['tours'])->map(function (array $package) use ($resolvedCategory, $slug) {
                    $resolved = MediaHelper::resolve($package);

                    return array_merge($resolved, [
                        'slug' => TourCatalog::slugify($package['title']),
                        'category_slug' => $slug,
                        'category_route' => TourCatalog::routeForSlug($slug),
                        'city' => $resolvedCategory['city'],
                    ]);
                });
            });
        }

        return TourPackage::query()
            ->where('is_active', true)
            ->with('category')
            ->orderBy('sort_order')
            ->get()
            ->map(function (TourPackage $package) {
                $category = $package->category;

                return MediaHelper::resolve([
                    'title' => $package->title,
                    'slug' => $package->slug,
                    'duration' => $package->duration,
                    'price' => $package->price_formatted,
                    'rating' => (float) $package->rating,
                    'tag' => $package->tag,
                    'image' => $package->image,
                    'category_slug' => $category->slug,
                    'category_route' => $category->route_name ?: TourCatalog::routeForSlug($category->slug),
                    'city' => $category->city_name,
                ]);
            });
    }

    public function relatedCategories(string $slug): array
    {
        if (! $this->useDatabase()) {
            return $this->relatedFromConfig($slug);
        }

        $category = TourCategory::query()->where('slug', $slug)->with('relatedCategories')->first();

        if (! $category) {
            return $this->relatedFromConfig($slug);
        }

        return $category->relatedCategories
            ->where('is_active', true)
            ->map(fn (TourCategory $cat) => MediaHelper::resolve($this->categoryToArray($cat)))
            ->values()
            ->all();
    }

    public function homeStats(): array
    {
        if (! Schema::hasTable('home_stats') || ! HomeStat::query()->active()->exists()) {
            return [
                ['value' => '4', 'label' => 'Heritage Cities'],
                ['value' => '10K+', 'label' => 'Happy Travelers'],
                ['value' => '4.9', 'label' => 'Average Rating'],
                ['value' => '80+', 'label' => 'City Tours'],
            ];
        }

        return HomeStat::query()->active()->get()
            ->map(fn (HomeStat $s) => ['value' => $s->value, 'label' => $s->label])
            ->all();
    }

    public function homeHighlights(): array
    {
        if (! Schema::hasTable('home_highlights') || ! HomeHighlight::query()->active()->exists()) {
            return [
                ['icon' => 'fa-map-marked-alt', 'title' => '4 City Specialists', 'text' => 'Dedicated Agra, Delhi, Jaipur & Varanasi teams — not generic India packages.'],
                ['icon' => 'fa-user-tie', 'title' => 'Licensed Local Guides', 'text' => 'City experts who know every monument, lane and hidden story.'],
                ['icon' => 'fa-shield-alt', 'title' => 'Safe & Flexible', 'text' => 'Private AC cars, free cancellation & 24/7 trip support.'],
                ['icon' => 'fa-tags', 'title' => 'Best Local Prices', 'text' => 'Direct operator rates — no middleman markup on city tours.'],
            ];
        }

        return HomeHighlight::query()->active()->get()
            ->map(fn (HomeHighlight $h) => ['icon' => $h->icon, 'title' => $h->title, 'text' => $h->text])
            ->all();
    }

    public function homeReviews(): array
    {
        if (! Schema::hasTable('testimonials')) {
            return $this->fallbackHomeReviews();
        }

        $reviews = Testimonial::query()->active()->where('show_on_home', true)->get();

        if ($reviews->isEmpty()) {
            return $this->fallbackHomeReviews();
        }

        return $reviews->map(fn (Testimonial $t) => [
            'quote' => $t->quote,
            'name' => $t->reviewer_name,
            'place' => $t->place,
            'city' => $t->city,
            'rating' => $t->rating,
            'avatar' => $t->avatar_image ? MediaHelper::url($t->avatar_image) : null,
        ])->all();
    }

    public function highlightedReviews(): array
    {
        if (! Schema::hasTable('testimonials')) {
            return TourCatalog::highlightedReviewsFallback();
        }

        $reviews = Testimonial::query()->active()->where('show_on_package', true)->limit(2)->get();

        if ($reviews->isEmpty()) {
            return TourCatalog::highlightedReviewsFallback();
        }

        return $reviews->map(fn (Testimonial $t) => [
            'name' => $t->reviewer_name,
            'place' => $t->place ?? '',
            'rating' => $t->rating,
            'title' => $t->title ?? 'Excellent',
            'text' => $t->quote,
            'date' => $t->review_date_label ?? '',
        ])->all();
    }

    public function homeCities(): array
    {
        if (! Schema::hasTable('cities') || ! City::query()->active()->exists()) {
            return [];
        }

        $images = MediaHelper::resolve(config('site.images'));

        return City::query()
            ->active()
            ->with(['categories' => fn ($q) => $q->active()->with(['packages' => fn ($pq) => $pq->active()->limit(3)])])
            ->get()
            ->map(function (City $city) use ($images) {
                $category = $this->primaryCategoryForCity($city);
                $categorySlug = $category?->slug ?? $this->categorySlugForKey($city->key);

                $tours = $category
                    ? $category->packages->take(3)->map(fn (TourPackage $p) => [
                        'title' => $p->title,
                        'duration' => $p->duration,
                        'price' => $p->price_formatted,
                        'rating' => (float) $p->rating,
                        'tag' => $p->tag,
                        'package_url' => TourCatalog::packageUrl($categorySlug, $p->title),
                    ])->all()
                    : [];

                $alt = $city->name.' tours';

                return [
                    'key' => $city->key,
                    'name' => $city->name,
                    'tagline' => $city->tagline,
                    'slug' => $city->slug,
                    'route' => $city->route_name ?: TourCatalog::routeForSlug($categorySlug),
                    'icon' => $city->icon,
                    'description' => $city->description,
                    'highlights' => $city->home_highlights ?? [],
                    'tour_count' => $city->tour_count_label,
                    'category_slug' => $categorySlug,
                    'image' => [
                        'url' => $city->card_image ? MediaHelper::url($city->card_image) : ($images['cities'][$city->key]['card'] ?? ''),
                        'alt' => $alt,
                    ],
                    'banner' => [
                        'url' => $city->banner_image ? MediaHelper::url($city->banner_image) : ($images['cities'][$city->key]['banner'] ?? ''),
                        'alt' => $alt,
                    ],
                    'tours' => $tours,
                ];
            })
            ->all();
    }

    public function popularTours(): array
    {
        if (! $this->useDatabase()) {
            return [];
        }

        return TourPackage::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->where('featured_section', 'popular')
            ->with('category')
            ->orderBy('featured_order')
            ->get()
            ->map(function (TourPackage $package) {
                $category = $package->category;

                return [
                    'title' => $package->title,
                    'city' => $category->city_name,
                    'city_icon' => $category->icon,
                    'category_slug' => $category->slug,
                    'duration' => $package->duration,
                    'price' => $package->price_formatted,
                    'rating' => (float) $package->rating,
                    'tag' => $package->tag,
                    'image' => $package->image ? MediaHelper::url($package->image) : MediaHelper::url($category->banner_image ?? ''),
                    'package_url' => TourCatalog::packageUrl($category->slug, $package->title),
                ];
            })
            ->all();
    }

    public function activeCategorySlugs(): array
    {
        if (! $this->useDatabase()) {
            return array_keys(config('tours.categories', []));
        }

        return TourCategory::query()->active()->pluck('slug')->all();
    }

    private function categoryToArray(TourCategory $cat): array
    {
        $cat->loadMissing(['monuments', 'packages' => fn ($q) => $q->active()]);

        return [
            'key' => $cat->key,
            'slug' => $cat->slug,
            'city' => $cat->city_name,
            'title' => $cat->title,
            'heading' => $cat->heading,
            'tagline' => $cat->tagline,
            'icon' => $cat->icon,
            'description' => $cat->description,
            'banner' => $cat->banner_image,
            'card' => $cat->card_image,
            'tour_count' => $cat->tour_count_label,
            'monuments' => $cat->monuments->map(fn ($m) => [
                'name' => $m->name,
                'image' => $m->image,
                'desc' => $m->description,
            ])->values()->all(),
            'tours' => $cat->packages->map(fn (TourPackage $p) => [
                'title' => $p->title,
                'duration' => $p->duration,
                'price' => $p->price_formatted,
                'rating' => (float) $p->rating,
                'tag' => $p->tag,
                'image' => $p->image,
                'highlights' => $p->highlights()->pluck('text')->all(),
            ])->values()->all(),
        ];
    }

    private function enrichPackageFromDb(TourCategory $category, TourPackage $package): array
    {
        $categoryArr = $this->categoryToArray($category);
        $base = [
            'title' => $package->title,
            'duration' => $package->duration,
            'price' => $package->price_formatted,
            'rating' => (float) $package->rating,
            'tag' => $package->tag,
            'image' => $package->image,
            'highlights' => $package->highlights->pluck('text')->all(),
        ];

        $enriched = TourCatalog::enrichPackageData($categoryArr, $base, $package->slug);

        if ($package->summary) {
            $enriched['summary'] = $package->summary;
        }
        if ($package->description) {
            $enriched['description'] = $package->description;
        }
        if ($package->full_description) {
            $enriched['full_description'] = $package->full_description;
        }
        if ($package->review_count) {
            $enriched['review_count'] = number_format($package->review_count).'+';
        }

        if ($package->itineraries->isNotEmpty()) {
            $enriched['itinerary'] = $package->itineraries->map(fn ($i) => [
                'title' => $i->title,
                'desc' => $i->description,
            ])->all();
        }

        if ($package->inclusions->isNotEmpty()) {
            $enriched['inclusions'] = $package->inclusions->pluck('text')->all();
        }

        if ($package->exclusions->isNotEmpty()) {
            $enriched['exclusions'] = $package->exclusions->pluck('text')->all();
        }

        if ($package->faqs->isNotEmpty()) {
            $enriched['faqs'] = $package->faqs->map(fn ($f) => ['q' => $f->question, 'a' => $f->answer])->all();
        }

        if ($package->locationTags->isNotEmpty()) {
            $enriched['location_tags'] = $package->locationTags->pluck('tag')->all();
        }

        if ($category->map_query) {
            $enriched['map_query'] = $category->map_query;
        }

        $enriched['id'] = $package->id;

        return $enriched;
    }

    private function primaryCategoryForCity(City $city): ?TourCategory
    {
        $category = $city->categories->first();

        if ($category) {
            return $category;
        }

        $slug = $this->categorySlugForKey($city->key);

        return TourCategory::query()->where('slug', $slug)->active()->with(['packages' => fn ($q) => $q->active()->limit(3)])->first();
    }

    private function categorySlugForKey(string $key): string
    {
        return match ($key) {
            'agra' => 'taj-mahal',
            default => $key,
        };
    }

    private function relatedFromConfig(string $slug): array
    {
        $map = [
            'taj-mahal' => ['delhi', 'golden-triangle', 'jaipur'],
            'delhi' => ['taj-mahal', 'golden-triangle', 'jaipur'],
            'jaipur' => ['taj-mahal', 'golden-triangle', 'delhi'],
            'varanasi' => ['taj-mahal', 'delhi', 'golden-triangle'],
            'golden-triangle' => ['taj-mahal', 'delhi', 'jaipur'],
        ];

        return collect($map[$slug] ?? ['taj-mahal', 'delhi', 'jaipur'])
            ->map(fn ($s) => $this->findCategory($s))
            ->filter()
            ->values()
            ->all();
    }

    private function fallbackHomeReviews(): array
    {
        $images = MediaHelper::resolve(config('site.images'));

        return [
            ['quote' => 'Sunrise at the Taj with Kranbstour was magical. Our Agra guide knew every perfect photo spot.', 'name' => 'Traveler0808', 'place' => 'United Kingdom', 'city' => 'Agra', 'rating' => 5, 'avatar' => $images['avatars'][0] ?? ''],
            ['quote' => 'Old Delhi food walk was incredible — chaat, parathas and stories we never would have found alone.', 'name' => 'SanVir', 'place' => 'Singapore', 'city' => 'Delhi', 'rating' => 5, 'avatar' => $images['avatars'][1] ?? ''],
        ];
    }
}
