@extends('layouts.admin')

@section('title', ($post->exists ? 'Edit' : 'Add').' Blog Post — Master')
@section('heading', ($post->exists ? 'Edit' : 'Add').' Blog Post')

@section('content')
    <form action="{{ $post->exists ? route('admin.master.blog-posts.update', $post) : route('admin.master.blog-posts.store') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf
        @if ($post->exists) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-100 p-6 grid sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Title *</label>
                <input name="title" value="{{ old('title', $post->title) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Slug *</label>
                <input name="slug" value="{{ old('slug', $post->slug) }}" required placeholder="taj-mahal-sunrise-tips" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Author</label>
                <input name="author_name" value="{{ old('author_name', $post->author_name ?? config('site.name')) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Excerpt</label>
                <textarea name="excerpt" rows="2" maxlength="500" class="w-full px-3 py-2 rounded-xl border border-slate-200" placeholder="Short summary for blog listing">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-ink-muted mb-1">Content (HTML allowed)</label>
                <textarea name="content" rows="12" class="w-full px-3 py-2 rounded-xl border border-slate-200 font-mono text-sm">{{ old('content', $post->content) }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <x-admin-image-field name="featured_image" label="Featured Image" :value="old('featured_image', $post->featured_image)" placeholder="blog/taj-sunrise.jpg" />
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Published Date</label>
                <input type="date" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d')) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $post->sort_order ?? 0) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div class="sm:col-span-2">
                <label class="flex items-center gap-2 text-sm font-semibold">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $post->is_active ?? true))> Active (visible on website)
                </label>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="px-5 py-2.5 bg-brand text-white font-bold rounded-xl">Save Post</button>
            <a href="{{ route('admin.master.blog-posts.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl font-semibold">Cancel</a>
        </div>
    </form>

    @if ($post->exists)
        <div class="flex justify-end mt-4">
            <x-admin-delete-form :action="route('admin.master.blog-posts.destroy', $post)" label="Delete Post" />
        </div>
    @endif
@endsection
