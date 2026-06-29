<?php

namespace App\Services;

use App\Helpers\CurrencyHelper;
use App\Helpers\MediaHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TourCatalog
{
    public static function slugify(string $title): string
    {
        return Str::slug($title);
    }

    public static function categories(): Collection
    {
        return collect(config('tours.categories', []));
    }

    public static function findCategory(string $slug): ?array
    {
        return app(CatalogService::class)->findCategory($slug);
    }

    public static function findPackage(string $categorySlug, string $packageSlug): ?array
    {
        return app(CatalogService::class)->findPackage($categorySlug, $packageSlug);
    }

    public static function findMonument(string $categorySlug, string $monumentSlug): ?array
    {
        return app(CatalogService::class)->findMonument($categorySlug, $monumentSlug);
    }

    public static function allPackages(): Collection
    {
        return app(CatalogService::class)->allPackages();
    }

    public static function highlightedReviews(): array
    {
        return app(CatalogService::class)->highlightedReviews();
    }

    public static function enrichFromConfig(string $categorySlug, string $packageTitle): ?array
    {
        $category = config("tours.categories.{$categorySlug}");
        if (! $category) {
            return null;
        }

        foreach ($category['tours'] as $package) {
            if ($package['title'] === $packageTitle) {
                $slug = self::slugify($packageTitle);

                return [
                    'category' => MediaHelper::resolve($category),
                    'package' => self::enrichPackageData($category, $package, $slug),
                ];
            }
        }

        return null;
    }

    public static function enrichPackageData(array $category, array $package, string $packageSlug): array
    {
        return self::enrichPackage($category, $package, $packageSlug);
    }

    private static function enrichPackage(array $category, array $package, string $packageSlug): array
    {
        $resolved = MediaHelper::resolve($package);
        $highlights = $package['highlights'] ?? [];

        return array_merge($resolved, [
            'slug' => $packageSlug,
            'summary' => self::packageSummary($category, $package),
            'description' => self::packageDescription($category, $package),
            'full_description' => self::fullDescription($category, $package),
            'highlights_list' => self::highlightsList($category, $package, $highlights),
            'gallery' => self::galleryImages($category, $package),
            'features' => self::tourFeatures($category, $package),
            'itinerary' => self::itinerary($category, $package),
            'inclusions' => self::inclusions($package),
            'exclusions' => self::defaultExclusions(),
            'important_info' => self::importantInfo(),
            'faqs' => self::faqs($category, $package),
            'review_count' => self::reviewCount($package['rating'] ?? 4.8),
            'location_tags' => self::locationTags($category),
            'map_query' => self::mapQuery($category),
        ]);
    }

    public static function routeForSlug(string $slug): string
    {
        return match ($slug) {
            'taj-mahal' => 'tours.taj-mahal',
            'golden-triangle' => 'tours.golden-triangle',
            default => "tours.{$slug}",
        };
    }

    public static function packageUrl(string $categorySlug, array|string $packageOrTitle, ?string $slug = null): string
    {
        if (is_array($packageOrTitle)) {
            $packageSlug = $packageOrTitle['slug'] ?? self::slugify($packageOrTitle['title'] ?? '');
        } else {
            $packageSlug = $slug ?? self::slugify($packageOrTitle);
        }

        return route('tours.package', [
            'category' => $categorySlug,
            'packageSlug' => $packageSlug,
        ]);
    }

    public static function monumentUrl(string $categorySlug, array|string $monumentOrName, ?string $slug = null): string
    {
        if (is_array($monumentOrName)) {
            $monumentSlug = $monumentOrName['slug'] ?? self::slugify($monumentOrName['name'] ?? '');
        } else {
            $monumentSlug = $slug ?? self::slugify($monumentOrName);
        }

        return route('tours.monument', [
            'category' => $categorySlug,
            'monumentSlug' => $monumentSlug,
        ]);
    }

    public static function bookUrl(string $categorySlug, string $packageSlug): string
    {
        return route('tours.book', [
            'category' => $categorySlug,
            'packageSlug' => $packageSlug,
        ]);
    }

    public static function highlightedReviewsFallback(): array
    {
        return [
            [
                'name' => 'Traveler0808',
                'place' => 'United Kingdom',
                'rating' => 5,
                'title' => 'Excellent',
                'text' => 'Sunrise at the Taj with our guide was magical. Every photo spot was perfect and the car was spotless. Highly recommend Kranbstour for Agra trips.',
                'date' => '3 days ago',
            ],
            [
                'name' => 'SanVir',
                'place' => 'Singapore',
                'rating' => 5,
                'title' => 'Excellent',
                'text' => 'Well organized from hotel pickup to drop-off. Guide was knowledgeable and friendly. Skip-the-line access saved us hours at the monuments.',
                'date' => '1 week ago',
            ],
        ];
    }

    private static function packageSummary(array $category, array $package): string
    {
        return "{$package['title']} is a {$package['duration']} tour in {$category['city']} with skip-the-line access and pickup from your hotel or airport. "
            .CurrencyHelper::startingFrom(null, $package['price']).' per person with free cancellation up to 24 hours in advance.';
    }

    private static function packageDescription(array $category, array $package): string
    {
        $highlights = implode(', ', $package['highlights'] ?? []);

        return "Experience {$package['title']} in {$category['city']} — {$category['tagline']}. "
            ."This {$package['duration']} private tour covers {$highlights}. "
            .'Expert local guides, flexible timing, and transparent pricing from '.config('site.name').'.';
    }

    private static function fullDescription(array $category, array $package): string
    {
        $city = $category['city'];

        return "Discover the best of {$city} on this carefully crafted {$package['duration']} experience. "
            ."{$package['title']} takes you through the region's most iconic landmarks with a private air-conditioned vehicle and an expert English-speaking guide. "
            ."Whether you're a first-time visitor or returning traveler, this tour is designed for comfort, culture, and unforgettable memories. "
            ."Pickup is available from your hotel or the airport in {$city} and nearby areas. "
            .'We handle logistics so you can focus on exploring — from monument entry assistance to the best local lunch recommendations.';
    }

    private static function highlightsList(array $category, array $package, array $highlights): array
    {
        $items = array_map(fn ($h) => "Experience {$h} with a private local guide", $highlights);

        if ($category['key'] === 'agra' || $category['slug'] === 'taj-mahal') {
            $items[] = 'Visit the UNESCO World Heritage Site of the Taj Mahal';
            $items[] = 'Explore Agra Fort and Mughal heritage architecture';
        }

        if ($category['key'] === 'delhi') {
            $items[] = 'Discover Old & New Delhi heritage monuments';
            $items[] = 'Walk through Chandni Chowk and historic lanes';
        }

        if ($category['key'] === 'jaipur') {
            $items[] = 'Tour Amber Fort and the Pink City palaces';
        }

        if ($category['key'] === 'varanasi') {
            $items[] = 'Witness the sacred Ganga Aarti ceremony at the ghats';
        }

        return array_slice(array_unique($items), 0, 6);
    }

    private static function galleryImages(array $category, array $package): array
    {
        $images = [
            ['src' => $package['image'], 'alt' => $package['title']],
        ];

        foreach ($category['monuments'] ?? [] as $monument) {
            $images[] = [
                'src' => is_array($monument['image'] ?? null) ? ($monument['image']['url'] ?? $monument['image']) : ($monument['image'] ?? ''),
                'alt' => $monument['name'] ?? 'Tour photo',
            ];
        }

        if (! empty($category['banner'])) {
            $images[] = ['src' => $category['banner'], 'alt' => $category['city'].' tour'];
        }

        return MediaHelper::resolve(array_slice($images, 0, 6));
    }

    private static function tourFeatures(array $category, array $package): array
    {
        return [
            ['icon' => 'fa-calendar-check', 'color' => 'text-blue-600 bg-blue-50', 'title' => 'Free cancellation', 'desc' => 'Cancel up to 24 hours in advance for a full refund'],
            ['icon' => 'fa-wallet', 'color' => 'text-emerald-600 bg-emerald-50', 'title' => 'Reserve now & pay later', 'desc' => 'Keep your travel plans flexible — book your spot and pay nothing today'],
            ['icon' => 'fa-clock', 'color' => 'text-blue-600 bg-blue-50', 'title' => "Duration {$package['duration']}", 'desc' => 'Check availability to see starting times'],
            ['icon' => 'fa-ticket-alt', 'color' => 'text-orange-600 bg-orange-50', 'title' => 'Skip the ticket line', 'desc' => null],
            ['icon' => 'fa-user-tie', 'color' => 'text-blue-600 bg-blue-50', 'title' => 'Live tour guide', 'desc' => 'English, Hindi — other languages on request'],
            ['icon' => 'fa-map-marker-alt', 'color' => 'text-red-600 bg-red-50', 'title' => 'Pickup included', 'desc' => "Pickup offered from hotels in {$category['city']} and nearby areas"],
            ['icon' => 'fa-wheelchair', 'color' => 'text-blue-600 bg-blue-50', 'title' => 'Wheelchair accessible', 'desc' => null],
            ['icon' => 'fa-users', 'color' => 'text-indigo-600 bg-indigo-50', 'title' => 'Private group', 'desc' => 'Your group only — no shared tours'],
        ];
    }

    private static function itinerary(array $category, array $package): array
    {
        $city = $category['city'];
        $duration = strtolower($package['duration']);

        if (str_contains($duration, 'half')) {
            return [
                ['title' => "Hotel pickup in {$city}", 'desc' => 'Your private driver and guide pick you up from your hotel or preferred location.'],
                ['title' => 'Guided monument visit', 'desc' => 'Explore the main highlights with skip-the-line entry assistance and expert commentary.'],
                ['title' => 'Return transfer', 'desc' => 'Comfortable drop-off back to your hotel or next destination.'],
            ];
        }

        if (str_contains($duration, '2 day') || str_contains($duration, '3 day') || str_contains($duration, '4 day') || str_contains($duration, '5 day') || str_contains($duration, '6 day')) {
            $days = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT) ?: 2;
            $steps = [];
            for ($i = 1; $i <= min($days, 4); $i++) {
                $steps[] = [
                    'title' => "Day {$i}: ".($i === 1 ? "Pickup & travel to {$city}" : "Explore {$city} highlights"),
                    'desc' => $i === 1
                        ? 'Pickup from Delhi or your city, drive to destination with comfort stops. Check-in and evening at leisure.'
                        : 'Full day guided tour covering major monuments, local culture, and photo stops with flexible pacing.',
                ];
            }
            if ($days > 4) {
                $steps[] = ['title' => "Day {$days}: Return journey", 'desc' => 'Final sightseeing and comfortable return transfer to your drop-off point.'];
            }

            return $steps;
        }

        return [
            ['title' => "Morning pickup from {$city}", 'desc' => 'Private AC car picks you up from hotel, airport, or railway station.'],
            ['title' => 'Full day guided sightseeing', 'desc' => 'Visit all major monuments listed in the package with your expert local guide.'],
            ['title' => 'Lunch break (optional)', 'desc' => 'Stop at a recommended local restaurant — meals at your own expense unless included.'],
            ['title' => 'Evening drop-off', 'desc' => 'Return to your hotel or onward destination in Delhi/Agra/Jaipur as per itinerary.'],
        ];
    }

    private static function inclusions(array $package): array
    {
        $base = [
            'Airport / Hotel pick-up & drop-off',
            'Private air-conditioned car with driver',
            'Live tour guide service',
            'Mineral water bottles',
            'Driver allowances & fuel',
            'All applicable taxes and charges',
        ];

        if (str_contains(strtolower($package['duration']), '2 day') || str_contains(strtolower($package['duration']), 'overnight')) {
            $base[] = '1 night hotel accommodation with breakfast';
        }

        return $base;
    }

    private static function defaultExclusions(): array
    {
        return [
            'Monument entry tickets (unless stated)',
            'Meals and drinks',
            'Personal expenses',
            'Camera fees at monuments',
            'Tips (optional)',
        ];
    }

    private static function importantInfo(): array
    {
        return [
            'What to bring' => ['Sunglasses & comfortable shoes', 'Passport or ID card (copy accepted)', 'Sunscreen & hat'],
            'Not allowed' => ['Alcohol and drugs inside monuments', 'Drones at protected heritage sites'],
            'Know before you book' => [
                'Confirmation received at time of booking',
                'Most travelers can participate',
                'Children must be accompanied by an adult',
            ],
        ];
    }

    private static function faqs(array $category, array $package): array
    {
        $title = $package['title'];

        return [
            ['q' => "What is the duration of the {$title}?", 'a' => "This tour is {$package['duration']}. Starting times vary — contact us or book to confirm your preferred slot."],
            ['q' => "Is pickup included in the {$title}?", 'a' => "Yes, complimentary pickup and drop-off from hotels in {$category['city']} and nearby areas is included."],
            ['q' => "What is included in the {$title}?", 'a' => 'Private AC vehicle, professional guide, bottled water, driver charges, and taxes. Monument tickets and meals are excluded unless stated.'],
            ['q' => "What is the cancellation policy?", 'a' => 'Free cancellation up to 24 hours before the tour start time for a full refund.'],
            ['q' => 'Can I customize the itinerary?', 'a' => 'Yes! This is a private tour — tell us your preferences when booking and we will adjust the schedule.'],
            ['q' => 'Is this tour suitable for families?', 'a' => 'Absolutely. We welcome travelers of all ages and can arrange child-friendly pacing and stops.'],
        ];
    }

    public static function reviewCount(float $rating): string
    {
        $base = (int) (1200 + ($rating * 100));

        return number_format($base).'+';
    }

    private static function locationTags(array $category): array
    {
        return match ($category['key'] ?? $category['slug']) {
            'golden-triangle' => ['Delhi', 'Agra', 'Jaipur'],
            'agra', 'taj-mahal' => ['Agra', 'Delhi'],
            'delhi' => ['Delhi', 'New Delhi'],
            'jaipur' => ['Jaipur', 'Rajasthan'],
            'varanasi' => ['Varanasi', 'Ganges'],
            default => [$category['city']],
        };
    }

    private static function mapQuery(array $category): string
    {
        return match ($category['key'] ?? $category['slug']) {
            'agra', 'taj-mahal' => 'Taj Mahal,Agra,India',
            'delhi' => 'Humayun Tomb,Delhi,India',
            'jaipur' => 'Amber Fort,Jaipur,India',
            'varanasi' => 'Dashashwamedh Ghat,Varanasi,India',
            default => $category['city'].',India',
        };
    }
}
