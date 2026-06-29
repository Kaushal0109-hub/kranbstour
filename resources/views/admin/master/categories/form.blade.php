@extends('layouts.admin')

@section('title', ($category->exists ? 'Edit' : 'Add').' Category')
@section('heading', ($category->exists ? 'Edit' : 'Add').' Tour Category')

@section('content')
    <form action="{{ $category->exists ? route('admin.master.categories.update', $category) : route('admin.master.categories.store') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-100 p-6 grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Linked City</label>
                <select name="city_id" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    <option value="">— None —</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" @selected(old('city_id', $category->city_id) == $city->id)>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Key *</label>
                <input name="key" value="{{ old('key', $category->key) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Slug *</label>
                <input name="slug" value="{{ old('slug', $category->slug) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">City Name *</label>
                <input name="city_name" value="{{ old('city_name', $category->city_name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Title *</label>
                <input name="title" value="{{ old('title', $category->title) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Heading *</label>
                <input name="heading" value="{{ old('heading', $category->heading) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('description', $category->description) }}</textarea>
            </div>
            <div>
                <x-admin-image-field name="banner_image" label="Banner Image" :value="old('banner_image', $category->banner_image)" />
            </div>
            <div>
                <x-admin-image-field name="card_image" label="Card Image" :value="old('card_image', $category->card_image)" />
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Tour Count</label>
                <input name="tour_count_label" value="{{ old('tour_count_label', $category->tour_count_label) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Map Query</label>
                <input name="map_query" value="{{ old('map_query', $category->map_query) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2 flex flex-wrap gap-6">
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="show_in_nav" value="1" @checked(old('show_in_nav', $category->show_in_nav ?? true))> Show in navigation</label>
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))> Active (visible on website)</label>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Navigation Label</label>
                <input name="nav_label" value="{{ old('nav_label', $category->nav_label) }}" placeholder="Same as title if empty" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="px-5 py-2.5 bg-brand text-white font-bold rounded-xl">Save Category</button>
            <a href="{{ route('admin.master.categories.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl font-semibold">Cancel</a>
        </div>
    </form>

    @if ($category->exists)
        <div class="flex justify-end mt-4">
            <x-admin-delete-form :action="route('admin.master.categories.destroy', $category)" label="Delete Category" />
        </div>
    @endif
@endsection
