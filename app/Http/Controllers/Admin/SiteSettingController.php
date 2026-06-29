<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\SiteConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    use HandlesImageUploads;

    public function edit(): View
    {
        $keys = ['name', 'tagline', 'description', 'phone', 'phone_display', 'email', 'whatsapp',
            'footer_description', 'logo_default', 'logo_white', 'logo_icon', 'hero_main_image', 'image_fallback'];
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
            'footer_description' => ['nullable', 'string'],
            'logo_default' => ['nullable', 'string', 'max:255'],
            'logo_white' => ['nullable', 'string', 'max:255'],
            'logo_icon' => ['nullable', 'string', 'max:255'],
            'hero_main_image' => ['nullable', 'string', 'max:255'],
            'image_fallback' => ['nullable', 'string', 'max:255'],
        ]);

        $imageFields = ['logo_default', 'logo_white', 'logo_icon', 'hero_main_image', 'image_fallback'];
        $data = $this->mergeUploadedImages($request, $data, $imageFields, 'site');

        if (empty($data['phone_display']) && ! empty($data['phone'])) {
            $data['phone_display'] = $data['phone'];
        }

        if (empty($data['whatsapp']) && ! empty($data['phone'])) {
            $data['whatsapp'] = $data['phone'];
        }

        foreach ($data as $key => $value) {
            SiteSetting::set($key, $value, 'site');
        }

        SiteConfig::boot();

        return back()->with('success', 'Site settings updated. Contact details are now live across the website.');
    }
}
