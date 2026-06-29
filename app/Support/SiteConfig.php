<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

class SiteConfig
{
    /** @var list<string> */
    private const FLAT_KEYS = [
        'name',
        'tagline',
        'description',
        'phone',
        'phone_display',
        'email',
        'whatsapp',
        'footer_description',
        'hero_main_image',
        'hero_main_alt',
        'image_fallback',
    ];

    /** @var array<string, string> */
    private const NESTED_KEYS = [
        'logo_default' => 'site.logo.default',
        'logo_white' => 'site.logo.white',
        'logo_icon' => 'site.logo.icon',
    ];

    /** @var list<string> */
    private const SKIP_KEYS = [
        'google_maps_api_key',
        'google_client_id',
        'google_client_secret',
    ];

    public static function boot(): void
    {
        if (! Schema::hasTable('site_settings')) {
            self::applyFallbacks();

            return;
        }

        try {
            foreach (SiteSetting::query()->where('group', 'site')->get() as $setting) {
                if (in_array($setting->key, self::SKIP_KEYS, true) || ! filled($setting->value)) {
                    continue;
                }

                if (isset(self::NESTED_KEYS[$setting->key])) {
                    config([self::NESTED_KEYS[$setting->key] => $setting->value]);

                    continue;
                }

                if (in_array($setting->key, self::FLAT_KEYS, true)) {
                    config(["site.{$setting->key}" => $setting->value]);
                }
            }

            if ($mapsKey = SiteSetting::get('google_maps_api_key')) {
                config(['site.maps.google_api_key' => $mapsKey]);
            }
        } catch (\Throwable) {
            //
        }

        self::applyFallbacks();
    }

    private static function applyFallbacks(): void
    {
        if (! filled(config('site.whatsapp')) && filled(config('site.phone'))) {
            config(['site.whatsapp' => config('site.phone')]);
        }

        if (! filled(config('site.phone_display')) && filled(config('site.phone'))) {
            config(['site.phone_display' => config('site.phone')]);
        }

        if ($email = config('site.email')) {
            config([
                'mail.from.address' => $email,
                'mail.from.name' => config('site.name'),
            ]);
        }
    }
}
