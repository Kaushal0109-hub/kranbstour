@extends('layouts.admin')

@section('title', ($package->exists ? 'Edit' : 'Add').' Package')
@section('heading', ($package->exists ? 'Edit' : 'Add').' Tour Package')

@section('content')
    <form action="{{ $package->exists ? route('admin.master.packages.update', $package) : route('admin.master.packages.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl space-y-6">
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
                <label class="block text-xs font-bold text-ink-muted mb-1">Price ($) *</label>
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
                <x-admin-image-field name="image" label="Main Image" :value="old('image', $package->image)" size="lg" />
                @if ($package->exists && $package->galleryImages->isNotEmpty())
                    <x-admin-image-gallery :images="$package->galleryImages" title="Gallery Images" />
                @endif
                <div class="mt-4">
                    <label class="block text-xs font-bold text-ink-muted mb-1">Add Gallery Images</label>
                    <input type="file" name="gallery_upload[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple
                           class="block w-full text-sm text-ink-muted file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand file:font-semibold hover:file:bg-brand-100">
                    <p class="text-[10px] text-ink-muted mt-1">Select multiple images to add to package gallery.</p>
                </div>
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
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active ?? true))> Active (visible on website)</label>
            </div>
            <input type="hidden" name="featured_section" value="popular">
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-6">
            <h3 class="font-bold text-ink">Package Details</h3>

            <x-admin-repeatable-rows
                name="highlights"
                label="Highlights"
                placeholder="e.g. Sunrise entry, Private guide"
                :values="$package->exists ? $package->highlights->pluck('text')->all() : []"
            />

            <x-admin-repeatable-rows
                name="itinerary"
                type="pair"
                label="Itinerary"
                first-key="title"
                second-key="description"
                first-placeholder="Step title"
                second-placeholder="Step description"
                :values="$package->exists ? $package->itineraries->map(fn ($i) => ['title' => $i->title, 'description' => $i->description])->all() : []"
            />

            <x-admin-repeatable-rows
                name="inclusions"
                label="Inclusions"
                placeholder="e.g. Hotel pickup and drop-off"
                :values="$package->exists ? $package->inclusions->pluck('text')->all() : []"
            />

            <x-admin-repeatable-rows
                name="exclusions"
                label="Exclusions"
                placeholder="e.g. Monument entry tickets"
                :values="$package->exists ? $package->exclusions->pluck('text')->all() : []"
            />

            <x-admin-repeatable-rows
                name="location_tags"
                label="Location Tags"
                placeholder="e.g. Delhi, Agra"
                :values="$package->exists ? $package->locationTags->pluck('tag')->all() : []"
            />

            <x-admin-repeatable-rows
                name="faqs"
                type="pair"
                label="FAQs"
                first-key="question"
                second-key="answer"
                first-placeholder="Question"
                second-placeholder="Answer"
                :values="$package->exists ? $package->faqs->map(fn ($f) => ['question' => $f->question, 'answer' => $f->answer])->all() : []"
            />
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="px-5 py-2.5 bg-brand text-white font-bold rounded-xl">Save Package</button>
            <a href="{{ route('admin.master.packages.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl font-semibold">Cancel</a>
        </div>
    </form>

    @if ($package->exists)
        <div class="flex justify-end mt-4">
            <x-admin-delete-form
                :action="route('admin.master.packages.destroy', $package)"
                label="Delete Package"
            />
        </div>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('click', function (e) {
    const addBtn = e.target.closest('[data-repeatable-add]');
    if (addBtn) {
        const name = addBtn.dataset.repeatableAdd;
        const list = document.querySelector('[data-repeatable-list="' + name + '"]');
        const template = document.querySelector('[data-repeatable-template="' + name + '"]');
        if (!list || !template) return;
        list.appendChild(template.content.cloneNode(true));
        return;
    }

    const removeBtn = e.target.closest('[data-repeatable-remove]');
    if (removeBtn) {
        const row = removeBtn.closest('[data-repeatable-row]');
        const list = row?.parentElement;
        if (!row || !list) return;
        const rows = list.querySelectorAll('[data-repeatable-row]');
        if (rows.length <= 1) {
            row.querySelectorAll('input').forEach(function (input) { input.value = ''; });
            return;
        }
        row.remove();
    }
});
</script>
@endpush
