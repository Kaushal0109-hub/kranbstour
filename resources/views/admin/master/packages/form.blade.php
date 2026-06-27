@extends('layouts.admin')

@section('title', ($package->exists ? 'Edit' : 'Add').' Package')
@section('heading', ($package->exists ? 'Edit' : 'Add').' Tour Package')

@section('content')
    <form action="{{ $package->exists ? route('admin.master.packages.update', $package) : route('admin.master.packages.store') }}" method="POST" class="max-w-4xl space-y-6">
        @csrf
        @if ($package->exists) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-100 p-6 grid sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Category *</label>
                <select name="category_id" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $package->category_id) == $cat->id)>{{ $cat->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Title *</label>
                <input name="title" value="{{ old('title', $package->title) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Slug</label>
                <input name="slug" value="{{ old('slug', $package->slug) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Duration *</label>
                <input name="duration" value="{{ old('duration', $package->duration) }}" placeholder="1 Day" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Price (₹) *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $package->price) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Price Display</label>
                <input name="price_display" value="{{ old('price_display', $package->price_display) }}" placeholder="1,750" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Rating</label>
                <input type="number" step="0.1" name="rating" value="{{ old('rating', $package->rating ?? 4.8) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Tag</label>
                <input name="tag" value="{{ old('tag', $package->tag) }}" placeholder="Best Seller" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Image Path</label>
                <input name="image" value="{{ old('image', $package->image) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Summary</label>
                <textarea name="summary" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('summary', $package->summary) }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('description', $package->description) }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Full Description</label>
                <textarea name="full_description" rows="4" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('full_description', $package->full_description) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Review Count</label>
                <input type="number" name="review_count" value="{{ old('review_count', $package->review_count ?? 1450) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Featured Order</label>
                <input type="number" name="featured_order" value="{{ old('featured_order', $package->featured_order) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2 flex flex-wrap gap-6">
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $package->is_featured))> Featured on homepage</label>
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active ?? true))> Active</label>
            </div>
            <input type="hidden" name="featured_section" value="popular">
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
            <h3 class="font-bold text-ink">Package Details</h3>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Highlights (one per line)</label>
                <textarea name="highlights_text" rows="4" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono text-sm">{{ old('highlights_text', $package->exists ? $package->highlights->pluck('text')->implode("\n") : '') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Itinerary (Title | Description per line)</label>
                <textarea name="itinerary_text" rows="5" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono text-sm">{{ old('itinerary_text', $package->exists ? $package->itineraries->map(fn($i) => $i->title.' | '.$i->description)->implode("\n") : '') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Inclusions (one per line)</label>
                <textarea name="inclusions_text" rows="4" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono text-sm">{{ old('inclusions_text', $package->exists ? $package->inclusions->pluck('text')->implode("\n") : '') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Exclusions (one per line)</label>
                <textarea name="exclusions_text" rows="4" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono text-sm">{{ old('exclusions_text', $package->exists ? $package->exclusions->pluck('text')->implode("\n") : '') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Location Tags (one per line)</label>
                <textarea name="location_tags_text" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono text-sm">{{ old('location_tags_text', $package->exists ? $package->locationTags->pluck('tag')->implode("\n") : '') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">FAQs (Question | Answer per line)</label>
                <textarea name="faqs_text" rows="5" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono text-sm">{{ old('faqs_text', $package->exists ? $package->faqs->map(fn($f) => $f->question.' | '.$f->answer)->implode("\n") : '') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 bg-brand text-white font-bold rounded-xl">Save Package</button>
            <a href="{{ route('admin.master.packages.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl font-semibold">Cancel</a>
        </div>
    </form>
@endsection
