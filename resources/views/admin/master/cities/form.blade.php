@extends('layouts.admin')

@section('title', ($city->exists ? 'Edit' : 'Add').' City — Master')
@section('heading', ($city->exists ? 'Edit' : 'Add').' City')

@section('content')
    <form action="{{ $city->exists ? route('admin.master.cities.update', $city) : route('admin.master.cities.store') }}" method="POST" class="max-w-3xl space-y-6">
        @csrf
        @if ($city->exists) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-100 p-6 grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Key *</label>
                <input name="key" value="{{ old('key', $city->key) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Slug *</label>
                <input name="slug" value="{{ old('slug', $city->slug) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Name *</label>
                <input name="name" value="{{ old('name', $city->name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Tagline</label>
                <input name="tagline" value="{{ old('tagline', $city->tagline) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Icon (FontAwesome)</label>
                <input name="icon" value="{{ old('icon', $city->icon ?? 'fa-map-marker-alt') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Tour Count Label</label>
                <input name="tour_count_label" value="{{ old('tour_count_label', $city->tour_count_label) }}" placeholder="30+ tours" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('description', $city->description) }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Home Highlights (one per line)</label>
                <textarea name="home_highlights" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('home_highlights', implode("\n", $city->home_highlights ?? [])) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Card Image Path</label>
                <input name="card_image" value="{{ old('card_image', $city->card_image) }}" placeholder="cities/agra-card.jpg" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Banner Image Path</label>
                <input name="banner_image" value="{{ old('banner_image', $city->banner_image) }}" placeholder="cities/agra-banner.jpg" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Route Name</label>
                <input name="route_name" value="{{ old('route_name', $city->route_name) }}" placeholder="tours.taj-mahal" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $city->sort_order ?? 0) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2 flex gap-6">
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_spotlight" value="1" @checked(old('is_spotlight', $city->is_spotlight))> Spotlight on home</label>
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $city->is_active ?? true))> Active</label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 bg-brand text-white font-bold rounded-xl">Save City</button>
            <a href="{{ route('admin.master.cities.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl font-semibold">Cancel</a>
        </div>
    </form>
@endsection
