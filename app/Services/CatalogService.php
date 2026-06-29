<?php

namespace App\Services;

use App\Helpers\MediaHelper;
use App\Models\City;
use App\Models\CmsPage;
use App\Models\HomeHero;
use App\Models\HomeHighlight;
use App\Models\HomeProcessStep;
use App\Models\HomePromoSection;
use App\Models\HomeStat;
use App\Models\Monument;
use App\Models\SiteSetting;
use App\Models\SocialLink;
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
            ?? $category->packages->first(fn (TourPackage $p) => TourCatalog::slugify($p->title) === $packageSlug)
            ?? TourPackage::query()
                ->where('category_id', $category->id)
                ->where('slug', $packageSlug)
                ->where('is_active', true)
                ->first();

        if (! $package) {
            return null;
        }

        $package->load(['highlights', 'itineraries', 'inclusions', 'exclusions', 'faqs', 'locationTags', 'galleryImages', 'features', 'importantInfos']);

        return [
            'category' => MediaHelper::resolve($this->categoryToArray($category)),
            'package' => MediaHelper::resolve($this->enrichPackageFromDb($category, $package)),
            'package_model' => $package,
        ];
    }

    public function findMonument(string $categorySlug, string $monumentSlug): ?array
    {
        if (! $this->useDatabase()) {
            $category = $this->findCategory($categorySlug);
            if (! $category) {
                return null;
            }

            foreach ($category['monuments'] as $monument) {
                $slug = $monument['slug'] ?? TourCatalog::slugify($monument['name']);
                if ($slug === $monumentSlug) {
                    return [
                        'category' => $category,
                        'monument' => MediaHelper::resolve(array_merge($monument, [
                            'slug' => $slug,
                            'desc' => $monument['desc'] ?? $monument['description'] ?? '',
                        ])),
                    ];
                }
            }

            return null;
        }

        $category = TourCategory::query()
            ->where('slug', $categorySlug)
            ->where('is_active', true)
            ->with('monuments')
            ->first();

        if (! $category) {
            return null;
        }

        $monument = $category->monuments()
            ->where('slug', $monumentSlug)
            ->first()
            ?? $category->monuments()->get()->first(
                fn ($m) => TourCatalog::slugify($m->name) === $monumentSlug
            );

        if (! $monument) {
            return null;
        }

        return [
            'category' => MediaHelper::resolve($this->categoryToArray($category)),
            'monument' => MediaHelper::resolve([
                'id' => $monument->id,
                'name' => $monument->name,
                'slug' => $monument->slug,
                'image' => $monument->image,
                'desc' => $monument->description,
                'description' => $monument->description,
            ]),
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
                        'package_url' => TourCatalog::packageUrl($categorySlug, ['slug' => $p->slug, 'title' => $p->title]),
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
                    'is_spotlight' => $city->is_spotlight,
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
                    'package_url' => TourCatalog::packageUrl($category->slug, ['slug' => $package->slug, 'title' => $package->title]),
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

    public function navTourLinks(): array
    {
        if (! $this->useDatabase()) {
            return $this->fallbackNavLinks();
        }

        return TourCategory::query()
            ->active()
            ->where('show_in_nav', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (TourCategory $cat) => [
                'label' => $cat->nav_label ?: $cat->title,
                'route' => $cat->route_name ?: TourCatalog::routeForSlug($cat->slug),
            ])
            ->all();
    }

    public function footerServiceLinks(): array
    {
        $links = $this->navTourLinks();
        $links[] = ['label' => 'Tour Packages', 'route' => 'tours.packages'];

        return $links;
    }

    public function footerCompanyLinks(): array
    {
        if (Schema::hasTable('cms_pages')) {
            $pages = CmsPage::query()->active()->where('show_in_footer', true)->get();
            if ($pages->isNotEmpty()) {
                $links = $pages->map(fn (CmsPage $p) => [
                    'label' => $p->title,
                    'route' => $p->slug,
                ])->all();

                if (! collect($links)->contains(fn (array $l) => $l['route'] === 'blog')) {
                    array_unshift($links, ['label' => 'Blog', 'route' => 'blog']);
                }

                return $links;
            }
        }

        return [
            ['label' => 'Blog', 'route' => 'blog'],
            ['label' => 'About Us', 'route' => 'about'],
            ['label' => 'Contact Us', 'route' => 'contact'],
            ['label' => 'Our Awards', 'route' => 'awards'],
        ];
    }

    public function socialLinks(): array
    {
        if (! Schema::hasTable('social_links') || ! SocialLink::query()->active()->exists()) {
            return [
                ['icon' => 'fab fa-youtube', 'label' => 'YouTube', 'url' => '#'],
                ['icon' => 'fab fa-facebook-f', 'label' => 'Facebook', 'url' => '#'],
                ['icon' => 'fab fa-instagram', 'label' => 'Instagram', 'url' => '#'],
            ];
        }

        return SocialLink::query()->active()->get()
            ->map(fn (SocialLink $s) => ['icon' => $s->icon, 'label' => $s->label, 'url' => $s->url ?? '#'])
            ->all();
    }

    public function heroSection(): array
    {
        if (Schema::hasTable('home_heroes')) {
            $hero = HomeHero::query()->where('is_active', true)->first();
            if ($hero) {
                return [
                    'badge_text' => $hero->badge_text,
                    'rating_text' => $hero->rating_text,
                    'heading_line1' => $hero->heading_line1,
                    'heading_line2' => $hero->heading_line2,
                    'subtitle' => $hero->subtitle,
                    'search_placeholder' => $hero->search_placeholder,
                    'background_image' => $hero->background_image ? MediaHelper::url($hero->background_image) : null,
                    'thumbnail_keys' => $hero->thumbnail_keys ?? ['agra', 'delhi', 'jaipur', 'varanasi'],
                ];
            }
        }

        $images = MediaHelper::resolve(config('site.images'));

        return [
            'badge_text' => 'Agra · Delhi · Jaipur · Varanasi',
            'rating_text' => '4.9 · 2,260+ reviews',
            'heading_line1' => "Discover India’s heritage",
            'heading_line2' => 'with local experts',
            'subtitle' => 'Private Taj Mahal sunrises, Old Delhi walks, Jaipur palaces & Varanasi Ganga aarti — curated by '.config('site.name').'.',
            'search_placeholder' => 'Taj Mahal, Old Delhi, Jaipur...',
            'background_image' => $images['hero']['main']['url'] ?? null,
            'thumbnail_keys' => ['agra', 'delhi', 'jaipur', 'varanasi'],
        ];
    }

    public function processSteps(): array
    {
        if (Schema::hasTable('home_process_steps') && HomeProcessStep::query()->active()->exists()) {
            return HomeProcessStep::query()->active()->get()
                ->map(fn (HomeProcessStep $s) => [
                    'icon' => $s->icon,
                    'color' => $s->color_classes,
                    'num' => $s->step_number,
                    'title' => $s->title,
                    'text' => $s->text,
                ])->all();
        }

        return [
            ['icon' => 'fa-map-marked-alt', 'color' => 'bg-orange-50 border-orange-200 text-accent', 'num' => '01', 'title' => 'Pick your city', 'text' => 'Browse tours in Agra, Delhi, Jaipur or Varanasi.'],
            ['icon' => 'fa-calendar-check', 'color' => 'bg-brand-50 border-brand-200 text-brand', 'num' => '02', 'title' => 'Select date & book', 'text' => 'Choose date, group size & extras. Instant confirmation.'],
            ['icon' => 'fa-route', 'color' => 'bg-emerald-50 border-emerald-200 text-brand-700', 'num' => '03', 'title' => 'Explore with guide', 'text' => 'Your local guide handles transport, tickets & timing.'],
        ];
    }

    public function promoSection(string $key): ?array
    {
        if (! Schema::hasTable('home_promo_sections')) {
            return $this->fallbackPromo($key);
        }

        $section = HomePromoSection::getByKey($key);
        if (! $section) {
            return $this->fallbackPromo($key);
        }

        return [
            'badge' => $section->badge,
            'title' => $section->title,
            'subtitle' => $section->subtitle,
            'description' => $section->description,
            'tags' => $section->tags ?? [],
            'price_label' => $section->price_label,
            'cta_label' => $section->cta_label,
            'cta_route' => $section->cta_route,
            'secondary_cta_label' => $section->secondary_cta_label,
            'secondary_cta_route' => $section->secondary_cta_route,
            'category_slug' => $section->category_slug,
            'city_keys' => $section->city_keys ?? [],
        ];
    }

    public function spotlightCities(): array
    {
        $cities = $this->homeCities();
        if (empty($cities)) {
            return [];
        }

        $spotlight = collect($cities)->filter(fn ($c) => ! empty($c['is_spotlight']));

        if ($spotlight->isEmpty()) {
            $spotlight = collect($cities)->whereIn('key', ['delhi', 'agra']);
        }

        return $spotlight->take(2)->map(fn ($city) => array_merge($city, [
            'tours' => array_slice($city['tours'], 0, 2),
        ]))->values()->all();
    }

    public function secondaryCities(): array
    {
        $cities = $this->homeCities();
        if (empty($cities)) {
            return [];
        }

        $secondary = collect($cities)->reject(fn ($c) => ! empty($c['is_spotlight']))->values();

        if ($secondary->isEmpty()) {
            $secondary = collect($cities)->reject(fn ($c) => in_array($c['key'], ['delhi', 'agra'], true));
        }

        return $secondary->take(2)->all();
    }

    public function cmsPage(string $slug): ?array
    {
        if (! Schema::hasTable('cms_pages')) {
            return null;
        }

        $page = CmsPage::query()->where('slug', $slug)->where('is_active', true)->first();

        return $page ? [
            'slug' => $page->slug,
            'title' => $page->title,
            'heading' => $page->heading,
            'content' => $page->content,
        ] : null;
    }

    public function search(string $query): array
    {
        $query = trim($query);
        if ($query === '') {
            return ['packages' => [], 'categories' => []];
        }

        if (! $this->useDatabase()) {
            return $this->searchConfig($query);
        }

        $like = '%'.$query.'%';

        $packages = TourPackage::query()
            ->where('is_active', true)
            ->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('summary', 'like', $like);
            })
            ->with('category')
            ->orderBy('sort_order')
            ->limit(20)
            ->get()
            ->map(fn (TourPackage $p) => [
                'title' => $p->title,
                'duration' => $p->duration,
                'price' => $p->price_formatted,
                'rating' => (float) $p->rating,
                'image' => $p->image ? MediaHelper::url($p->image) : null,
                'category_slug' => $p->category->slug,
                'url' => TourCatalog::packageUrl($p->category->slug, ['slug' => $p->slug, 'title' => $p->title]),
            ])
            ->all();

        $categories = TourCategory::query()
            ->active()
            ->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('city_name', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->limit(10)
            ->get()
            ->map(fn (TourCategory $c) => [
                'title' => $c->title,
                'city' => $c->city_name,
                'url' => route($c->route_name ?: TourCatalog::routeForSlug($c->slug)),
            ])
            ->all();

        return compact('packages', 'categories');
    }

    public function siteImages(): array
    {
        $images = config('site.images', []);
        if (Schema::hasTable('site_settings')) {
            $map = [
                'hero.main.url' => 'hero_main_image',
                'hero.main.alt' => 'hero_main_alt',
                'fallback' => 'image_fallback',
            ];
            foreach ($map as $path => $key) {
                $val = SiteSetting::get($key);
                if ($val) {
                    data_set($images, $path, $key === 'hero_main_image' || $key === 'image_fallback' ? $val : $val);
                }
            }
            foreach (['logo_default', 'logo_white', 'logo_icon'] as $logoKey) {
                if ($val = SiteSetting::get($logoKey)) {
                    config(["site.logo.".str_replace('logo_', '', str_replace('_default', '', $logoKey)) => $val]);
                }
            }
        }

        return MediaHelper::resolve($images);
    }

    private function fallbackNavLinks(): array
    {
        return [
            ['label' => 'Taj Mahal Tours', 'route' => 'tours.taj-mahal'],
            ['label' => 'Jaipur Tours', 'route' => 'tours.jaipur'],
            ['label' => 'New Delhi Tours', 'route' => 'tours.delhi'],
            ['label' => 'Golden Triangle', 'route' => 'tours.golden-triangle'],
            ['label' => 'Varanasi Tours', 'route' => 'tours.varanasi'],
        ];
    }

    private function fallbackPromo(?string $key): ?array
    {
        return match ($key) {
            'golden_triangle' => [
                'badge' => 'Combo Package',
                'title' => 'Golden Triangle: Delhi + Agra + Jaipur',
                'subtitle' => null,
                'description' => 'Cover all three royal & historic cities in one seamless journey — Taj Mahal, Delhi monuments & Jaipur palaces with private car, guide & flexible itinerary.',
                'tags' => ['3 Cities', '3–7 Days', 'Private Car', 'From $8,500'],
                'price_label' => 'From $8,500',
                'cta_label' => 'View Golden Triangle Tours',
                'cta_route' => 'tours.golden-triangle',
                'category_slug' => 'golden-triangle',
                'city_keys' => ['agra', 'delhi', 'jaipur'],
            ],
            'cta' => [
                'badge' => 'Agra · Delhi · Jaipur · Varanasi',
                'title' => 'Ready to explore India’s finest cities?',
                'subtitle' => null,
                'description' => 'Tell us which city you want to visit — custom quote within 2 hours, free cancellation on most tours.',
                'cta_label' => 'Get a Free Quote',
                'cta_route' => 'contact',
                'secondary_cta_label' => 'Browse all tours',
                'secondary_cta_route' => 'tours.packages',
            ],
            'spotlight' => [
                'badge' => 'Delhi & Agra',
                'title' => 'Where most travelers start',
                'subtitle' => 'Capital heritage meets the Taj — our two most booked destinations',
            ],
            'story' => [
                'badge' => 'Why '.config('site.name').'?',
                'title' => 'Your trusted North India tour partner',
                'subtitle' => 'Local experts, private tours & honest pricing — everything you need for a hassle-free trip.',
            ],
            default => null,
        };
    }

    private function searchConfig(string $query): array
    {
        $q = strtolower($query);
        $packages = $this->allPackages()->filter(function ($p) use ($q) {
            return str_contains(strtolower($p['title']), $q)
                || str_contains(strtolower($p['city'] ?? ''), $q);
        })->take(20)->map(fn ($p) => array_merge($p, [
            'url' => TourCatalog::packageUrl($p['category_slug'], ['slug' => $p['slug'], 'title' => $p['title']]),
        ]))->values()->all();

        $categories = $this->categories()->filter(function ($cat, $slug) use ($q) {
            return str_contains(strtolower($cat['title'] ?? ''), $q)
                || str_contains(strtolower($cat['city'] ?? ''), $q)
                || str_contains($slug, $q);
        })->map(fn ($cat, $slug) => [
            'title' => $cat['title'],
            'city' => $cat['city'],
            'url' => route(TourCatalog::routeForSlug($slug)),
        ])->values()->all();

        return compact('packages', 'categories');
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
                'slug' => $m->slug,
                'image' => $m->image,
                'desc' => $m->description,
                'url' => TourCatalog::monumentUrl($cat->slug, ['slug' => $m->slug, 'name' => $m->name]),
            ])->values()->all(),
            'tours' => $cat->packages->map(fn (TourPackage $p) => [
                'title' => $p->title,
                'slug' => $p->slug,
                'duration' => $p->duration,
                'price' => $p->price_formatted,
                'rating' => (float) $p->rating,
                'tag' => $p->tag,
                'image' => $p->image,
                'highlights' => $p->highlights()->pluck('text')->all(),
                'url' => TourCatalog::packageUrl($cat->slug, ['slug' => $p->slug, 'title' => $p->title]),
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

        if ($package->galleryImages->isNotEmpty()) {
            $enriched['gallery'] = MediaHelper::resolve($package->galleryImages->map(fn ($g) => [
                'src' => $g->image,
                'alt' => $g->alt ?? $package->title,
            ])->all());
        }

        if ($package->features->isNotEmpty()) {
            $enriched['features'] = $package->features->map(fn ($f) => [
                'icon' => $f->icon,
                'color' => $f->color_classes,
                'title' => $f->title,
                'desc' => $f->description,
            ])->all();
        }

        if ($package->importantInfos->isNotEmpty()) {
            $enriched['important_info'] = $package->importantInfos
                ->groupBy('heading')
                ->map(fn ($items) => $items->pluck('item_text')->all())
                ->all();
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
