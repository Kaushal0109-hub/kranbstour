<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Services\CatalogService;
use App\Support\GoogleAuth;
use App\Support\SiteConfig;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($appUrl = config('app.url')) {
            URL::forceRootUrl($appUrl);
        }

        GoogleAuth::boot();
        SiteConfig::boot();

        View::share('site', config('site'));

        View::composer(['auth.login', 'auth.register', 'auth.partials.google-login'], function ($view) {
            $view->with('googleAuthEnabled', GoogleAuth::isConfigured());
        });

        View::composer(['partials.header', 'partials.footer'], function ($view) {
            if (! Schema::hasTable('tour_categories')) {
                return;
            }
            try {
                $catalog = app(CatalogService::class);
                $view->with([
                    'navTourLinks' => $catalog->navTourLinks(),
                    'footerServiceLinks' => $catalog->footerServiceLinks(),
                    'footerCompanyLinks' => $catalog->footerCompanyLinks(),
                    'socialLinks' => $catalog->socialLinks(),
                ]);
            } catch (\Throwable) {
                //
            }
        });
    }
}
