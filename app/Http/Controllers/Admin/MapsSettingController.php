<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapsSettingController extends Controller
{
    public function edit(): View
    {
        $apiKey = SiteSetting::get('google_maps_api_key', config('site.maps.google_api_key', ''));

        return view('admin.master.maps-settings.edit', [
            'apiKey' => $apiKey,
            'isConfigured' => filled($apiKey),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        if ($request->boolean('clear_key')) {
            SiteSetting::set('google_maps_api_key', '', 'site');

            return back()->with('success', 'Google Maps API key removed.');
        }

        $validated = $request->validate([
            'google_maps_api_key' => ['nullable', 'string', 'max:255'],
        ]);

        SiteSetting::set('google_maps_api_key', trim($validated['google_maps_api_key'] ?? ''), 'site');

        return back()->with('success', 'Google Maps API key saved.');
    }
}
