<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        $keys = ['name', 'tagline', 'description', 'phone', 'phone_display', 'email', 'whatsapp'];
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = SiteSetting::get($key, config("site.{$key}"));
        }

        return view('admin.master.settings.form', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'phone_display' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
        ]);

        foreach ($data as $key => $value) {
            SiteSetting::set($key, $value, 'site');
        }

        return back()->with('success', 'Site settings updated.');
    }
}
