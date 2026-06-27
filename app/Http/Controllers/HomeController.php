<?php

namespace App\Http\Controllers;

use App\Helpers\MediaHelper;
use App\Services\CatalogService;
use App\Services\TourCatalog;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private CatalogService $catalog) {}

    public function index(): View
    {
        $images = MediaHelper::resolve(config('site.images'));

        $stats = $this->catalog->homeStats();
        $highlights = $this->catalog->homeHighlights();
        $reviews = $this->catalog->homeReviews();

        $cities = $this->catalog->homeCities();
        if (empty($cities)) {
            $cities = $this->legacyCities($images);
        }

        $cityByKey = collect($cities)->keyBy('key');

        $popularTours = $this->catalog->popularTours();
        if (empty($popularTours)) {
            $popularTours = $this->legacyPopularTours($images);
        }

        $spotlightCities = collect(['delhi', 'agra'])->map(function (string $key) use ($cityByKey) {
            $city = $cityByKey[$key];

            return array_merge($city, [
                'tours' => array_slice($city['tours'], 0, 2),
            ]);
        })->all();

        $secondaryCities = [
            $cityByKey['jaipur'],
            $cityByKey['varanasi'],
        ];

        return view('home.index', compact(
            'images',
            'stats',
            'cities',
            'highlights',
            'reviews',
            'popularTours',
            'spotlightCities',
            'secondaryCities',
        ));
    }

    private function legacyCities(array $images): array
    {
        $cities = [
            [
                'key' => 'agra', 'name' => 'Agra', 'tagline' => 'City of the Taj Mahal', 'slug' => 'agra',
                'route' => 'tours.taj-mahal', 'icon' => 'fa-monument',
                'description' => 'Witness the Taj Mahal at sunrise, explore Agra Fort, Itimad-ud-Daulah & Fatehpur Sikri with expert local guides.',
                'highlights' => ['Taj Mahal Sunrise', 'Agra Fort & Baby Taj', 'Fatehpur Sikri Day Trip'],
                'tour_count' => '30+ tours', 'category_slug' => 'taj-mahal',
                'image' => ['url' => $images['cities']['agra']['card'], 'alt' => $images['cities']['agra']['alt']],
                'banner' => ['url' => $images['cities']['agra']['banner'], 'alt' => $images['cities']['agra']['alt']],
                'tours' => [
                    ['title' => 'Sunrise Taj Mahal Tour from Delhi', 'duration' => '1 Day', 'price' => '1,750', 'rating' => 4.9, 'tag' => 'Best Seller'],
                    ['title' => 'Taj Mahal, Agra Fort & Fatehpur Sikri', 'duration' => '1 Day', 'price' => '4,580', 'rating' => 4.8, 'tag' => null],
                ],
            ],
            [
                'key' => 'delhi', 'name' => 'Delhi', 'tagline' => 'Capital of India', 'slug' => 'new-delhi',
                'route' => 'tours.delhi', 'icon' => 'fa-landmark',
                'description' => "Discover Old Delhi's lanes, Red Fort, Qutub Minar, Humayun's Tomb & modern New Delhi with private guided tours.",
                'highlights' => ['Old & New Delhi Combo', 'Red Fort & Chandni Chowk', 'Same-day Agra Transfers'],
                'tour_count' => '25+ tours', 'category_slug' => 'delhi',
                'image' => ['url' => $images['cities']['delhi']['card'], 'alt' => $images['cities']['delhi']['alt']],
                'banner' => ['url' => $images['cities']['delhi']['banner'], 'alt' => $images['cities']['delhi']['alt']],
                'tours' => [
                    ['title' => 'Full Day Old & New Delhi Heritage Tour', 'duration' => '1 Day', 'price' => '1,450', 'rating' => 4.8, 'tag' => 'Best Seller'],
                    ['title' => 'Delhi Food Walk & Hidden Gems', 'duration' => 'Half Day', 'price' => '980', 'rating' => 4.7, 'tag' => null],
                ],
            ],
            [
                'key' => 'jaipur', 'name' => 'Jaipur', 'tagline' => 'The Pink City', 'slug' => 'jaipur',
                'route' => 'tours.jaipur', 'icon' => 'fa-fort-awesome',
                'description' => "Walk through royal palaces, Amber Fort, Hawa Mahal, City Palace & vibrant bazaars in Rajasthan's pink capital.",
                'highlights' => ['Amber Fort & Palaces', 'Hawa Mahal & City Palace', 'Jaipur Shopping & Culture'],
                'tour_count' => '22+ tours', 'category_slug' => 'jaipur',
                'image' => ['url' => $images['cities']['jaipur']['card'], 'alt' => $images['cities']['jaipur']['alt']],
                'banner' => ['url' => $images['cities']['jaipur']['banner'], 'alt' => $images['cities']['jaipur']['alt']],
                'tours' => [
                    ['title' => 'Jaipur Pink City Full Day Private Tour', 'duration' => '1 Day', 'price' => '2,100', 'rating' => 4.9, 'tag' => 'Best Seller'],
                ],
            ],
            [
                'key' => 'varanasi', 'name' => 'Varanasi', 'tagline' => 'Spiritual Capital of India', 'slug' => 'varanasi',
                'route' => 'tours.varanasi', 'icon' => 'fa-om',
                'description' => 'Experience Ganga Aarti, sunrise boat rides, ancient temples & the sacred ghats of Kashi with knowledgeable guides.',
                'highlights' => ['Evening Ganga Aarti', 'Sunrise Boat Ride', 'Temple & Ghats Walk'],
                'tour_count' => '18+ tours', 'category_slug' => 'varanasi',
                'image' => ['url' => $images['cities']['varanasi']['card'], 'alt' => $images['cities']['varanasi']['alt']],
                'banner' => ['url' => $images['cities']['varanasi']['banner'], 'alt' => $images['cities']['varanasi']['alt']],
                'tours' => [
                    ['title' => 'Varanasi Ganga Aarti & Evening Tour', 'duration' => 'Half Day', 'price' => '890', 'rating' => 5.0, 'tag' => 'Best Seller'],
                ],
            ],
        ];

        return array_map(function (array $city) {
            $city['tours'] = array_map(fn (array $tour) => array_merge($tour, [
                'package_url' => TourCatalog::packageUrl($city['category_slug'], $tour['title']),
            ]), $city['tours']);

            return $city;
        }, $cities);
    }

    private function legacyPopularTours(array $images): array
    {
        $tours = [
            ['title' => 'Sunrise Taj Mahal Tour from Delhi', 'city' => 'Agra', 'city_icon' => 'fa-monument', 'category_slug' => 'taj-mahal', 'duration' => '1 Day', 'price' => '1,750', 'rating' => 4.9, 'tag' => 'Best Seller', 'image' => $images['cities']['agra']['banner']],
            ['title' => 'Full Day Old & New Delhi Heritage Tour', 'city' => 'Delhi', 'city_icon' => 'fa-landmark', 'category_slug' => 'delhi', 'duration' => '1 Day', 'price' => '1,450', 'rating' => 4.8, 'tag' => 'Top Rated', 'image' => $images['cities']['delhi']['banner']],
        ];

        return array_map(fn (array $tour) => array_merge($tour, [
            'package_url' => TourCatalog::packageUrl($tour['category_slug'], $tour['title']),
        ]), $tours);
    }
}
