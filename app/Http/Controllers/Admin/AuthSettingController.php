<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\GoogleAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthSettingController extends Controller
{
    public function edit(): View
    {
        $clientId = SiteSetting::get('google_client_id', config('services.google.client_id', ''));
        $clientSecret = SiteSetting::get('google_client_secret', config('services.google.client_secret', ''));

        return view('admin.master.auth-settings.edit', [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => GoogleAuth::redirectUri(),
            'isConfigured' => GoogleAuth::isConfigured(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        if ($request->boolean('clear_credentials')) {
            SiteSetting::set('google_client_id', '', 'site');
            SiteSetting::set('google_client_secret', '', 'site');

            GoogleAuth::boot();

            return back()->with('success', 'Google login credentials removed.');
        }

        $validated = $request->validate([
            'google_client_id' => ['nullable', 'string', 'max:255'],
            'google_client_secret' => ['nullable', 'string', 'max:255'],
        ]);

        SiteSetting::set('google_client_id', trim($validated['google_client_id'] ?? ''), 'site');
        SiteSetting::set('google_client_secret', trim($validated['google_client_secret'] ?? ''), 'site');

        GoogleAuth::boot();

        return back()->with('success', 'Google login settings saved.');
    }
}
