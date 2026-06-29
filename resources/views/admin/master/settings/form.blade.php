@extends('layouts.admin')

@section('title', 'Site Settings')
@section('heading', 'Site Settings')

@section('content')
    <form action="{{ route('admin.master.settings.update') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf @method('PUT')

        {{-- General --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
            <h3 class="font-extrabold text-ink text-sm border-b border-slate-100 pb-3">General</h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-ink-muted mb-1">Site Name *</label>
                    <input name="name" value="{{ old('name', $settings['name'] ?? '') }}" required
                           class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-ink-muted mb-1">Tagline</label>
                    <input name="tagline" value="{{ old('tagline', $settings['tagline'] ?? '') }}"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-ink-muted mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('description', $settings['description'] ?? '') }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-ink-muted mb-1">Footer Description</label>
                    <textarea name="footer_description" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('footer_description', $settings['footer_description'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Contact --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
            <h3 class="font-extrabold text-ink text-sm border-b border-slate-100 pb-3">Contact (shown site-wide)</h3>
            <p class="text-xs text-ink-muted">Phone and email appear in header, footer, contact page, tour pages, WhatsApp button, and booking emails.</p>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-ink-muted mb-1">Phone (for call links)</label>
                    <input name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="+919876543210"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink-muted mb-1">Phone display text</label>
                    <input name="phone_display" value="{{ old('phone_display', $settings['phone_display'] ?? '') }}" placeholder="+91-98765-43210"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink-muted mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}" placeholder="info@yoursite.com"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink-muted mb-1">WhatsApp number</label>
                    <input name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" placeholder="Same as phone if empty"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
            </div>
        </div>

        {{-- Branding --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
            <h3 class="font-extrabold text-ink text-sm border-b border-slate-100 pb-3">Branding & Images</h3>
            <x-admin-image-field name="logo_default" label="Logo (default)" :value="old('logo_default', $settings['logo_default'] ?? '')" />
            <x-admin-image-field name="logo_white" label="Logo (white)" :value="old('logo_white', $settings['logo_white'] ?? '')" />
            <x-admin-image-field name="logo_icon" label="Favicon / Icon" :value="old('logo_icon', $settings['logo_icon'] ?? '')" size="sm" />
            <x-admin-image-field name="hero_main_image" label="Hero Background" :value="old('hero_main_image', $settings['hero_main_image'] ?? '')" size="wide" />
            <x-admin-image-field name="image_fallback" label="Fallback Image" :value="old('image_fallback', $settings['image_fallback'] ?? '')" />
        </div>

        <button type="submit" class="px-6 py-2.5 bg-brand text-white font-bold rounded-xl text-sm">Save Settings</button>
    </form>
@endsection
