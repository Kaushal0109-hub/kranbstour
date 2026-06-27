<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
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
        if (Schema::hasTable('site_settings')) {
            try {
                foreach (SiteSetting::query()->where('group', 'site')->get() as $setting) {
                    config(["site.{$setting->key}" => $setting->value]);
                }
            } catch (\Throwable) {
                // Ignore during fresh install
            }
        }

        View::share('site', config('site'));
    }
}
