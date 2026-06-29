@extends('layouts.admin')

@section('title', ($page->exists ? 'Edit' : 'Add').' CMS Page — Master')
@section('heading', ($page->exists ? 'Edit' : 'Add').' CMS Page')

@section('content')
    <form action="{{ $page->exists ? route('admin.master.cms-pages.update', $page) : route('admin.master.cms-pages.store') }}" method="POST" class="max-w-3xl space-y-6">
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-100 p-6 grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Slug *</label>
                <input name="slug" value="{{ old('slug', $page->slug) }}" required placeholder="about" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                <p class="text-[11px] text-ink-muted mt-1">URL: /your-slug — reserved: blog, contact, admin</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Page Title (meta) *</label>
                <input name="title" value="{{ old('title', $page->title) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Heading (on page) *</label>
                <input name="heading" value="{{ old('heading', $page->heading) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Content (HTML allowed)</label>
                <textarea name="content" rows="16" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono text-sm">{{ old('content', $page->content) }}</textarea>
                <p class="text-[11px] text-ink-muted mt-1">Use &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;&lt;li&gt; for sections. Plain text also works.</p>
            </div>
            <div class="sm:col-span-2 flex flex-wrap gap-6">
                <label class="flex items-center gap-2 text-sm font-semibold">
                    <input type="checkbox" name="show_in_footer" value="1" @checked(old('show_in_footer', $page->show_in_footer))> Show in footer (Company links)
                </label>
                <label class="flex items-center gap-2 text-sm font-semibold">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active ?? true))> Active (visible on website)
                </label>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="px-5 py-2.5 bg-brand text-white font-bold rounded-xl">Save Page</button>
            <a href="{{ route('admin.master.cms-pages.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl font-semibold">Cancel</a>
        </div>
    </form>

    @if ($page->exists)
        <div class="flex justify-end mt-4">
            <x-admin-delete-form :action="route('admin.master.cms-pages.destroy', $page)" label="Delete Page" />
        </div>
    @endif
@endsection
