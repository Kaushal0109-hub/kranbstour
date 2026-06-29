<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

class GoogleAuth
{
    public static function boot(): void
    {
        $clientId = trim((string) env('GOOGLE_CLIENT_ID', ''));
        $clientSecret = trim((string) env('GOOGLE_CLIENT_SECRET', ''));

        if (Schema::hasTable('site_settings')) {
            try {
                if ($id = trim((string) SiteSetting::get('google_client_id', ''))) {
                    $clientId = $id;
                }
                if ($secret = trim((string) SiteSetting::get('google_client_secret', ''))) {
                    $clientSecret = $secret;
                }
            } catch (\Throwable) {
                //
            }
        }

        $redirect = trim((string) env('GOOGLE_REDIRECT_URI', ''));
        if ($redirect === '') {
            $redirect = rtrim((string) config('app.url'), '/').'/auth/google/callback';
        }

        config([
            'services.google.client_id' => $clientId ?: null,
            'services.google.client_secret' => $clientSecret ?: null,
            'services.google.redirect' => $redirect,
        ]);

        $appPath = parse_url((string) config('app.url'), PHP_URL_PATH);
        if (is_string($appPath) && $appPath !== '' && $appPath !== '/') {
            config(['session.path' => rtrim($appPath, '/') ?: '/']);
        }
    }

    public static function isConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'));
    }

    public static function redirectUri(): string
    {
        return (string) config('services.google.redirect');
    }
}
