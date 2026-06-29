<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private CatalogService $catalog) {}

    public function index(): View
    {
        $images = $this->catalog->siteImages();
        $hero = $this->catalog->heroSection();

        return view('home.index', [
            'images' => $images,
            'hero' => $hero,
            'stats' => $this->catalog->homeStats(),
            'highlights' => $this->catalog->homeHighlights(),
            'reviews' => $this->catalog->homeReviews(),
            'cities' => $this->catalog->homeCities(),
            'popularTours' => $this->catalog->popularTours(),
            'spotlightCities' => $this->catalog->spotlightCities(),
            'secondaryCities' => $this->catalog->secondaryCities(),
            'goldenTriangle' => $this->catalog->promoSection('golden_triangle'),
            'spotlightSection' => $this->catalog->promoSection('spotlight'),
            'storySection' => $this->catalog->promoSection('story'),
            'ctaSection' => $this->catalog->promoSection('cta'),
            'processSteps' => $this->catalog->processSteps(),
        ]);
    }
}
