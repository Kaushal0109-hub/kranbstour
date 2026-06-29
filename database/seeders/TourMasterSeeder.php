<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\CmsPage;
use App\Models\HomeHero;
use App\Models\HomeHighlight;
use App\Models\HomeProcessStep;
use App\Models\HomePromoSection;
use App\Models\HomeStat;
use App\Models\Monument;
use App\Models\PackageExclusion;
use App\Models\PackageFaq;
use App\Models\PackageFeature;
use App\Models\PackageGalleryImage;
use App\Models\PackageHighlight;
use App\Models\PackageImportantInfo;
use App\Models\PackageInclusion;
use App\Models\PackageItinerary;
use App\Models\PackageLocationTag;
use App\Models\SiteSetting;
use App\Models\SocialLink;
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
        $this->seedExtendedCms();
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
            'footer_description' => $site['name'].' is a dynamic and experienced tour operator company, dedicated to providing our clients with unforgettable travel experiences.',
            'logo_default' => $site['logo']['default'],
            'logo_white' => $site['logo']['white'],
            'logo_icon' => $site['logo']['icon'],
            'hero_main_image' => $site['images']['hero']['main']['url'] ?? 'cities/hero-main.jpg',
            'hero_main_alt' => $site['images']['hero']['main']['alt'] ?? 'Taj Mahal, Agra',
            'image_fallback' => $site['images']['fallback'] ?? 'cities/fallback.jpg',
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

    private function seedExtendedCms(): void
    {
        HomeHero::query()->delete();
        HomeHero::create([
            'badge_text' => 'Agra · Delhi · Jaipur · Varanasi',
            'rating_text' => '4.9 · 2,260+ reviews',
            'heading_line1' => "Discover India’s heritage",
            'heading_line2' => 'with local experts',
            'subtitle' => 'Private Taj Mahal sunrises, Old Delhi walks, Jaipur palaces & Varanasi Ganga aarti — curated by '.config('site.name').'.',
            'search_placeholder' => 'Taj Mahal, Old Delhi, Jaipur...',
            'background_image' => 'cities/hero-main.jpg',
            'thumbnail_keys' => ['agra', 'delhi', 'jaipur', 'varanasi'],
            'is_active' => true,
        ]);

        HomeProcessStep::query()->delete();
        foreach ([
            ['fa-map-marked-alt', 'bg-orange-50 border-orange-200 text-accent', '01', 'Pick your city', 'Browse tours in Agra, Delhi, Jaipur or Varanasi.', 1],
            ['fa-calendar-check', 'bg-brand-50 border-brand-200 text-brand', '02', 'Select date & book', 'Choose date, group size & extras. Instant confirmation.', 2],
            ['fa-route', 'bg-emerald-50 border-emerald-200 text-brand-700', '03', 'Explore with guide', 'Your local guide handles transport, tickets & timing.', 3],
        ] as [$icon, $color, $num, $title, $text, $order]) {
            HomeProcessStep::create([
                'icon' => $icon, 'color_classes' => $color, 'step_number' => $num,
                'title' => $title, 'text' => $text, 'sort_order' => $order, 'is_active' => true,
            ]);
        }

        HomePromoSection::query()->delete();
        foreach ([
            [
                'key' => 'golden_triangle',
                'badge' => 'Combo Package',
                'title' => 'Golden Triangle: Delhi + Agra + Jaipur',
                'description' => 'Cover all three royal & historic cities in one seamless journey — Taj Mahal, Delhi monuments & Jaipur palaces with private car, guide & flexible itinerary.',
                'tags' => ['3 Cities', '3–7 Days', 'Private Car', 'From $8,500'],
                'price_label' => 'From $8,500',
                'cta_label' => 'View Golden Triangle Tours',
                'cta_route' => 'tours.golden-triangle',
                'category_slug' => 'golden-triangle',
                'city_keys' => ['agra', 'delhi', 'jaipur'],
            ],
            [
                'key' => 'cta',
                'badge' => 'Agra · Delhi · Jaipur · Varanasi',
                'title' => 'Ready to explore India’s finest cities?',
                'description' => 'Tell us which city you want to visit — custom quote within 2 hours, free cancellation on most tours.',
                'cta_label' => 'Get a Free Quote',
                'cta_route' => 'contact',
                'secondary_cta_label' => 'Browse all tours',
                'secondary_cta_route' => 'tours.packages',
            ],
            [
                'key' => 'spotlight',
                'badge' => 'Delhi & Agra',
                'title' => 'Where most travelers start',
                'subtitle' => 'Capital heritage meets the Taj — our two most booked destinations',
            ],
            [
                'key' => 'story',
                'badge' => 'Why '.config('site.name').'?',
                'title' => 'Your trusted North India tour partner',
                'subtitle' => 'Local experts, private tours & honest pricing — everything you need for a hassle-free trip.',
            ],
        ] as $data) {
            HomePromoSection::create($data + ['is_active' => true]);
        }

        SocialLink::query()->delete();
        foreach ([
            ['fab fa-youtube', 'YouTube', '#', 1],
            ['fab fa-facebook-f', 'Facebook', '#', 2],
            ['fab fa-twitter', 'Twitter', '#', 3],
            ['fab fa-instagram', 'Instagram', '#', 4],
            ['fab fa-pinterest-p', 'Pinterest', '#', 5],
        ] as [$icon, $label, $url, $order]) {
            SocialLink::create(compact('icon', 'label', 'url') + ['sort_order' => $order, 'is_active' => true]);
        }

        CmsPage::query()->delete();
        $contactUrl = route('contact');
        foreach ([
            [
                'about', 'About Us', 'About Us',
                '<p>'.config('site.name').' is a specialist tour operator for Agra, Delhi, Jaipur, Varanasi and the Golden Triangle. Since our founding, we have helped thousands of travellers experience India\'s heritage with comfort, safety and authentic local insight.</p>
                <h2>Who we are</h2>
                <p>We are a team of licensed guides, experienced drivers and travel planners based in North India. Every itinerary is private — your vehicle, your schedule, your pace.</p>
                <h2>What we offer</h2>
                <ul>
                    <li>Same-day and multi-day private tours with hotel pickup</li>
                    <li>Expert English-speaking guides at monuments and cities</li>
                    <li>Flexible payment — pay on arrival, deposit or full online checkout</li>
                    <li>24/7 support before and during your trip</li>
                </ul>
                <h2>Our promise</h2>
                <p>Transparent pricing, no hidden fees, and a commitment to memorable experiences — from Taj Mahal sunrise to Varanasi ghats.</p>',
                true, 1,
            ],
            [
                'awards', 'Our Awards', 'Our Awards',
                '<p>Recognized for excellence in heritage tourism across North India. Our team takes pride in consistent guest satisfaction and responsible travel practices.</p>',
                true, 2,
            ],
            [
                'terms', 'Terms of Service', 'Terms of Service',
                '<p>These terms govern bookings made with '.config('site.name').'. By confirming a tour, you agree to the following.</p>
                <h2>Bookings &amp; confirmation</h2>
                <p>A booking is confirmed once you receive our confirmation email. We reserve the right to decline bookings that cannot be fulfilled.</p>
                <h2>Payments</h2>
                <p>You may pay on arrival, a 30% deposit to reserve, or 100% online where available. Prices are quoted in the currency shown at checkout unless stated otherwise.</p>
                <h2>Cancellations &amp; refunds</h2>
                <ul>
                    <li>Free cancellation up to 24 hours before tour start for most day tours</li>
                    <li>Deposits may be non-refundable within 24 hours of travel date</li>
                    <li>Refunds for online payments are processed within 5–10 business days</li>
                </ul>
                <h2>Guest responsibilities</h2>
                <p>Guests must carry valid ID, arrive on time at the agreed pickup point, and follow monument and local rules. We are not liable for delays caused by traffic, weather or site closures beyond our control.</p>
                <h2>Contact</h2>
                <p>Questions about these terms? Use our <a href="'.$contactUrl.'">contact page</a>.</p>',
                false, 3,
            ],
            [
                'privacy', 'Privacy Policy', 'Privacy Policy',
                '<p>'.config('site.name').' respects your privacy. This policy explains what data we collect and how we use it.</p>
                <h2>Information we collect</h2>
                <ul>
                    <li>Name, email, phone and country when you enquire or book</li>
                    <li>Travel dates, pickup location and special requests</li>
                    <li>Payment details processed securely by our payment partners (we do not store full card numbers)</li>
                </ul>
                <h2>How we use your data</h2>
                <p>We use your information to confirm bookings, send itineraries, provide customer support, and improve our services. We do not sell your personal data to third parties.</p>
                <h2>Cookies &amp; analytics</h2>
                <p>Our website may use cookies for basic functionality and analytics. You can control cookies through your browser settings.</p>
                <h2>Data retention &amp; rights</h2>
                <p>We retain booking records as required for legal and accounting purposes. You may request access or correction of your data by contacting us.</p>
                <h2>Contact</h2>
                <p>For privacy requests, reach us via the <a href="'.$contactUrl.'">contact page</a>.</p>',
                false, 4,
            ],
            [
                'taxi', 'Taxi Service & Transfers', 'Taxi Service & Transfers',
                '<p>Private taxi and transfer services between Delhi, Agra, Jaipur, airports and hotels. Contact us for intercity quotes and airport pickups.</p>',
                false, 5,
            ],
        ] as [$slug, $title, $heading, $content, $footer, $order]) {
            CmsPage::create([
                'slug' => $slug, 'title' => $title, 'heading' => $heading,
                'content' => $content, 'show_in_footer' => $footer, 'sort_order' => $order, 'is_active' => true,
            ]);
        }

        $this->seedBlogPosts();
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
                    'show_in_nav' => true,
                    'nav_label' => $cat['title'],
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
                    'slug' => TourCatalog::slugify($mon['name']),
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

        $package->galleryImages()->delete();
        $catConfig = config("tours.categories.{$category->slug}", []);
        $gallery = [['src' => $pkg['image'], 'alt' => $pkg['title']]];
        foreach ($catConfig['monuments'] ?? [] as $monument) {
            $gallery[] = ['src' => $monument['image'], 'alt' => $monument['name'] ?? 'Tour photo'];
        }
        if (! empty($catConfig['banner'])) {
            $gallery[] = ['src' => $catConfig['banner'], 'alt' => ($catConfig['city'] ?? '').' tour'];
        }
        foreach (array_slice($gallery, 0, 6) as $i => $img) {
            PackageGalleryImage::create([
                'package_id' => $package->id,
                'image' => $img['src'],
                'alt' => $img['alt'] ?? $package->title,
                'sort_order' => $i + 1,
            ]);
        }

        $package->features()->delete();
        foreach ($p['features'] ?? [] as $i => $feature) {
            PackageFeature::create([
                'package_id' => $package->id,
                'icon' => $feature['icon'],
                'color_classes' => $feature['color'],
                'title' => $feature['title'],
                'description' => $feature['desc'],
                'sort_order' => $i + 1,
            ]);
        }

        $package->importantInfos()->delete();
        $sort = 0;
        foreach ($p['important_info'] ?? [] as $heading => $items) {
            foreach ($items as $item) {
                $sort++;
                PackageImportantInfo::create([
                    'package_id' => $package->id,
                    'heading' => $heading,
                    'item_text' => $item,
                    'sort_order' => $sort,
                ]);
            }
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

    private function seedBlogPosts(): void
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('blog_posts')) {
            return;
        }

        BlogPost::query()->delete();

        $posts = [
            [
                'title' => 'Best Time to Visit the Taj Mahal',
                'slug' => 'best-time-to-visit-taj-mahal',
                'excerpt' => 'Sunrise, winter mornings and weekdays — our guide\'s tips for the perfect Taj Mahal visit.',
                'featured_image' => 'cities/agra-banner.jpg',
                'content' => '<p>The Taj Mahal is stunning at any hour, but sunrise offers the softest light and smaller crowds. Arrive 30 minutes before opening for security and ticket lines.</p>
                    <h2>Season guide</h2>
                    <ul>
                        <li><strong>October–March:</strong> Best weather, ideal for photography</li>
                        <li><strong>April–June:</strong> Hot days — plan early morning or late afternoon</li>
                        <li><strong>July–September:</strong> Monsoon — fewer tourists, dramatic skies</li>
                    </ul>
                    <p>Book a private sunrise tour with hotel pickup to skip transport stress.</p>',
                'days_ago' => 5,
            ],
            [
                'title' => 'Golden Triangle in 3 Days: A Practical Itinerary',
                'slug' => 'golden-triangle-3-day-itinerary',
                'excerpt' => 'Delhi, Agra and Jaipur in three days — how to cover highlights without rushing.',
                'featured_image' => 'cities/delhi-banner.jpg',
                'content' => '<p>The Golden Triangle is India\'s most popular route for first-time visitors. Here is a balanced 3-day plan with private car and guide.</p>
                    <h2>Day 1 — Delhi</h2>
                    <p>Old Delhi bazaar walk, Red Fort exterior, India Gate and Humayun\'s Tomb.</p>
                    <h2>Day 2 — Agra</h2>
                    <p>Early drive to Agra, Taj Mahal, Agra Fort and optional Mehtab Bagh sunset.</p>
                    <h2>Day 3 — Jaipur</h2>
                    <p>Amber Fort, City Palace, Hawa Mahal and return to Delhi or overnight in Jaipur.</p>',
                'days_ago' => 12,
            ],
            [
                'title' => 'Jaipur Food & Culture: What Not to Miss',
                'slug' => 'jaipur-food-and-culture-guide',
                'excerpt' => 'From dal baati churma to block-print bazaars — taste and shop like a local in the Pink City.',
                'featured_image' => 'cities/jaipur-banner.jpg',
                'content' => '<p>Jaipur blends royal history with vibrant street life. After palace visits, save time for local flavours and crafts.</p>
                    <h2>Must-try dishes</h2>
                    <ul>
                        <li>Dal baati churma at a heritage haveli restaurant</li>
                        <li>Pyaaz kachori from a Old City stall</li>
                        <li>Lassi on MI Road after sightseeing</li>
                    </ul>
                    <h2>Shopping tips</h2>
                    <p>Johari Bazaar for jewellery, Bapu Bazaar for textiles. Ask your guide for fair-price shops.</p>',
                'days_ago' => 20,
            ],
        ];

        foreach ($posts as $i => $post) {
            BlogPost::create([
                'title' => $post['title'],
                'slug' => $post['slug'],
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'featured_image' => $post['featured_image'],
                'author_name' => config('site.name'),
                'published_at' => now()->subDays($post['days_ago']),
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}
