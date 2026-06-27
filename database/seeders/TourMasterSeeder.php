<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\HomeHighlight;
use App\Models\HomeStat;
use App\Models\Monument;
use App\Models\PackageExclusion;
use App\Models\PackageFaq;
use App\Models\PackageHighlight;
use App\Models\PackageInclusion;
use App\Models\PackageItinerary;
use App\Models\PackageLocationTag;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\TourCategory;
use App\Models\TourPackage;
use App\Services\TourCatalog;
use Illuminate\Database\Seeder;

class TourMasterSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSiteSettings();
        $this->seedHomepage();
        $this->seedCitiesAndCategories();
    }

    private function seedSiteSettings(): void
    {
        $site = config('site');
        foreach ([
            'name' => $site['name'],
            'tagline' => $site['tagline'],
            'description' => $site['description'],
            'phone' => $site['phone'],
            'phone_display' => $site['phone_display'],
            'email' => $site['email'],
            'whatsapp' => $site['whatsapp'],
        ] as $key => $value) {
            SiteSetting::set($key, $value, 'site');
        }
    }

    private function seedHomepage(): void
    {
        HomeStat::query()->delete();
        foreach ([
            ['4', 'Heritage Cities', 1],
            ['10K+', 'Happy Travelers', 2],
            ['4.9', 'Average Rating', 3],
            ['80+', 'City Tours', 4],
        ] as [$value, $label, $order]) {
            HomeStat::create(['value' => $value, 'label' => $label, 'sort_order' => $order, 'is_active' => true]);
        }

        HomeHighlight::query()->delete();
        foreach ([
            ['fa-map-marked-alt', '4 City Specialists', 'Dedicated Agra, Delhi, Jaipur & Varanasi teams — not generic India packages.', 1],
            ['fa-user-tie', 'Licensed Local Guides', 'City experts who know every monument, lane and hidden story.', 2],
            ['fa-shield-alt', 'Safe & Flexible', 'Private AC cars, free cancellation & 24/7 trip support.', 3],
            ['fa-tags', 'Best Local Prices', 'Direct operator rates — no middleman markup on city tours.', 4],
        ] as [$icon, $title, $text, $order]) {
            HomeHighlight::create(compact('icon', 'title', 'text') + ['sort_order' => $order, 'is_active' => true]);
        }

        Testimonial::query()->delete();
        $avatars = config('site.images.avatars', []);
        foreach ([
            ['Sunrise at the Taj with Kranbstour was magical. Our Agra guide knew every perfect photo spot.', 'Traveler0808', 'United Kingdom', 'Agra', 5, true, false],
            ['Old Delhi food walk was incredible — chaat, parathas and stories we never would have found alone.', 'SanVir', 'Singapore', 'Delhi', 5, true, false],
            ['Amer Fort at sunset and the Jaipur bazaars — perfectly organized, zero hassle.', 'Damien', 'France', 'Jaipur', 5, true, false],
            ['Ganga Aarti in Varanasi gave me chills. The boat ride at dawn was the highlight of our India trip.', 'Maria', 'Spain', 'Varanasi', 5, true, false],
            ['Well organized from hotel pickup to drop-off. Guide was knowledgeable and friendly.', 'SanVir', 'Singapore', '', 5, false, true],
            ['Sunrise at the Taj with our guide was magical. Every photo spot was perfect.', 'Traveler0808', 'United Kingdom', '', 5, false, true],
        ] as $i => [$quote, $reviewer_name, $place, $city, $rating, $home, $pkg]) {
            Testimonial::create([
                'quote' => $quote,
                'reviewer_name' => $reviewer_name,
                'place' => $place,
                'city' => $city,
                'rating' => $rating,
                'title' => 'Excellent',
                'avatar_image' => $avatars[$i % count($avatars)] ?? null,
                'review_date_label' => ($i % 2 === 0) ? '3 days ago' : '1 week ago',
                'show_on_home' => $home,
                'show_on_package' => $pkg,
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedCitiesAndCategories(): void
    {
        $cityMap = [
            'agra' => ['name' => 'Agra', 'slug' => 'agra', 'tagline' => 'City of the Taj Mahal', 'icon' => 'fa-monument',
                'description' => 'Witness the Taj Mahal at sunrise, explore Agra Fort, Itimad-ud-Daulah & Fatehpur Sikri with expert local guides.',
                'highlights' => ['Taj Mahal Sunrise', 'Agra Fort & Baby Taj', 'Fatehpur Sikri Day Trip'],
                'card' => 'cities/agra-card.jpg', 'banner' => 'cities/agra-banner.jpg', 'route' => 'tours.taj-mahal', 'spotlight' => true, 'order' => 1],
            'delhi' => ['name' => 'Delhi', 'slug' => 'new-delhi', 'tagline' => 'Capital of India', 'icon' => 'fa-landmark',
                'description' => "Discover Old Delhi's lanes, Red Fort, Qutub Minar, Humayun's Tomb & modern New Delhi with private guided tours.",
                'highlights' => ['Old & New Delhi Combo', 'Red Fort & Chandni Chowk', 'Same-day Agra Transfers'],
                'card' => 'cities/delhi-card.jpg', 'banner' => 'cities/delhi-banner.jpg', 'route' => 'tours.delhi', 'spotlight' => true, 'order' => 2],
            'jaipur' => ['name' => 'Jaipur', 'slug' => 'jaipur', 'tagline' => 'The Pink City', 'icon' => 'fa-fort-awesome',
                'description' => "Walk through royal palaces, Amber Fort, Hawa Mahal, City Palace & vibrant bazaars in Rajasthan's pink capital.",
                'highlights' => ['Amber Fort & Palaces', 'Hawa Mahal & City Palace', 'Jaipur Shopping & Culture'],
                'card' => 'cities/jaipur-card.jpg', 'banner' => 'cities/jaipur-banner.jpg', 'route' => 'tours.jaipur', 'spotlight' => false, 'order' => 3],
            'varanasi' => ['name' => 'Varanasi', 'slug' => 'varanasi', 'tagline' => 'Spiritual Capital of India', 'icon' => 'fa-om',
                'description' => 'Experience Ganga Aarti, sunrise boat rides, ancient temples & the sacred ghats of Kashi with knowledgeable guides.',
                'highlights' => ['Evening Ganga Aarti', 'Sunrise Boat Ride', 'Temple & Ghats Walk'],
                'card' => 'cities/varanasi-card.jpg', 'banner' => 'cities/varanasi-banner.jpg', 'route' => 'tours.varanasi', 'spotlight' => false, 'order' => 4],
        ];

        $cities = [];
        foreach ($cityMap as $key => $data) {
            $cities[$key] = City::updateOrCreate(
                ['key' => $key],
                [
                    'slug' => $data['slug'],
                    'name' => $data['name'],
                    'tagline' => $data['tagline'],
                    'icon' => $data['icon'],
                    'description' => $data['description'],
                    'home_highlights' => $data['highlights'],
                    'tour_count_label' => null,
                    'card_image' => $data['card'],
                    'banner_image' => $data['banner'],
                    'route_name' => $data['route'],
                    'sort_order' => $data['order'],
                    'is_spotlight' => $data['spotlight'],
                    'is_active' => true,
                ]
            );
        }

        $relatedMap = [
            'taj-mahal' => ['delhi', 'golden-triangle', 'jaipur'],
            'delhi' => ['taj-mahal', 'golden-triangle', 'jaipur'],
            'jaipur' => ['taj-mahal', 'golden-triangle', 'delhi'],
            'varanasi' => ['taj-mahal', 'delhi', 'golden-triangle'],
            'golden-triangle' => ['taj-mahal', 'delhi', 'jaipur'],
        ];

        $categories = [];
        $order = 0;
        foreach (config('tours.categories') as $slug => $cat) {
            $order++;
            $cityId = isset($cities[$cat['key']]) ? $cities[$cat['key']]->id : null;
            $routeName = TourCatalog::routeForSlug($slug);

            $category = TourCategory::updateOrCreate(
                ['slug' => $slug],
                [
                    'city_id' => $cityId,
                    'key' => $cat['key'],
                    'city_name' => $cat['city'],
                    'title' => $cat['title'],
                    'heading' => $cat['heading'],
                    'tagline' => $cat['tagline'],
                    'icon' => $cat['icon'],
                    'description' => $cat['description'],
                    'banner_image' => $cat['banner'],
                    'card_image' => $cat['card'],
                    'tour_count_label' => $cat['tour_count'],
                    'route_name' => $routeName,
                    'map_query' => $this->mapQueryFor($cat['key']),
                    'sort_order' => $order,
                    'is_active' => true,
                ]
            );

            if ($cityId && $cities[$cat['key']]) {
                $cities[$cat['key']]->update(['tour_count_label' => $cat['tour_count']]);
            }

            $categories[$slug] = $category;

            Monument::where('category_id', $category->id)->delete();
            foreach ($cat['monuments'] as $i => $mon) {
                Monument::create([
                    'category_id' => $category->id,
                    'name' => $mon['name'],
                    'description' => $mon['desc'],
                    'image' => $mon['image'],
                    'sort_order' => $i + 1,
                ]);
            }

            $featuredTitles = $this->featuredPackageTitles();
            foreach ($cat['tours'] as $pi => $pkg) {
                $pkgSlug = TourCatalog::slugify($pkg['title']);
                $price = (float) str_replace(',', '', $pkg['price']);

                $package = TourPackage::updateOrCreate(
                    ['category_id' => $category->id, 'slug' => $pkgSlug],
                    [
                        'title' => $pkg['title'],
                        'duration' => $pkg['duration'],
                        'price' => $price,
                        'price_display' => $pkg['price'],
                        'rating' => $pkg['rating'],
                        'tag' => $pkg['tag'],
                        'image' => $pkg['image'],
                        'summary' => null,
                        'description' => null,
                        'full_description' => null,
                        'review_count' => (int) (1200 + ($pkg['rating'] * 100)),
                        'is_featured' => in_array($pkg['title'], $featuredTitles, true),
                        'featured_section' => in_array($pkg['title'], $featuredTitles, true) ? 'popular' : null,
                        'featured_order' => array_search($pkg['title'], $featuredTitles, true) ?: null,
                        'sort_order' => $pi + 1,
                        'is_active' => true,
                    ]
                );

                $this->seedPackageDetails($package, $category, $pkg);
            }
        }

        foreach ($relatedMap as $slug => $relatedSlugs) {
            if (! isset($categories[$slug])) {
                continue;
            }
            $sync = [];
            foreach ($relatedSlugs as $i => $relSlug) {
                if (isset($categories[$relSlug])) {
                    $sync[$categories[$relSlug]->id] = ['sort_order' => $i + 1];
                }
            }
            $categories[$slug]->relatedCategories()->sync($sync);
        }
    }

    private function seedPackageDetails(TourPackage $package, TourCategory $category, array $pkg): void
    {
        $package->highlights()->delete();
        foreach ($pkg['highlights'] ?? [] as $i => $text) {
            PackageHighlight::create(['package_id' => $package->id, 'text' => $text, 'sort_order' => $i + 1]);
        }

        $package->locationTags()->delete();
        foreach ($this->locationTagsFor($category->key) as $i => $tag) {
            PackageLocationTag::create(['package_id' => $package->id, 'tag' => $tag, 'sort_order' => $i + 1]);
        }

        $enriched = TourCatalog::enrichFromConfig($category->slug, $pkg['title']);
        if (! $enriched) {
            return;
        }

        $p = $enriched['package'];
        $package->update([
            'summary' => $p['summary'] ?? null,
            'description' => $p['description'] ?? null,
            'full_description' => $p['full_description'] ?? null,
        ]);

        $package->itineraries()->delete();
        foreach ($p['itinerary'] ?? [] as $i => $step) {
            PackageItinerary::create([
                'package_id' => $package->id,
                'day_number' => $i + 1,
                'title' => $step['title'],
                'description' => $step['desc'],
                'sort_order' => $i + 1,
            ]);
        }

        $package->inclusions()->delete();
        foreach ($p['inclusions'] ?? [] as $i => $text) {
            PackageInclusion::create(['package_id' => $package->id, 'text' => $text, 'sort_order' => $i + 1]);
        }

        $package->exclusions()->delete();
        foreach ($p['exclusions'] ?? [] as $i => $text) {
            PackageExclusion::create(['package_id' => $package->id, 'text' => $text, 'sort_order' => $i + 1]);
        }

        $package->faqs()->delete();
        foreach ($p['faqs'] ?? [] as $i => $faq) {
            PackageFaq::create([
                'package_id' => $package->id,
                'question' => $faq['q'],
                'answer' => $faq['a'],
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function featuredPackageTitles(): array
    {
        return [
            'Sunrise Taj Mahal Tour from Delhi',
            'Full Day Old & New Delhi Heritage Tour',
            'Taj Mahal, Agra Fort & Fatehpur Sikri',
            'Delhi Food Walk & Hidden Gems',
            'Jaipur Pink City Full Day Private Tour',
            'Varanasi Ganga Aarti & Evening Tour',
        ];
    }

    private function locationTagsFor(string $key): array
    {
        return match ($key) {
            'golden-triangle' => ['Delhi', 'Agra', 'Jaipur'],
            'agra', 'taj-mahal' => ['Agra', 'Delhi'],
            'delhi' => ['Delhi', 'New Delhi'],
            'jaipur' => ['Jaipur', 'Rajasthan'],
            'varanasi' => ['Varanasi', 'Ganges'],
            default => [],
        };
    }

    private function mapQueryFor(string $key): string
    {
        return match ($key) {
            'agra', 'taj-mahal' => 'Taj Mahal,Agra,India',
            'delhi' => 'Humayun Tomb,Delhi,India',
            'jaipur' => 'Amber Fort,Jaipur,India',
            'varanasi' => 'Dashashwamedh Ghat,Varanasi,India',
            default => 'India',
        };
    }
}
