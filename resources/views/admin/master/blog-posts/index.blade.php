@extends('layouts.admin')

@section('title', 'Blog Posts — Master')
@section('heading', 'Blog Posts')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <p class="text-sm text-ink-muted">Travel articles shown on the public blog page</p>
        <a href="{{ route('admin.master.blog-posts.create') }}" class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-xl hover:bg-brand-700">+ Add Post</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase w-16">Image</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Title</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Published</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Status</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr>
                            <td class="px-5 py-4">
                                <x-admin-image-thumb :src="$post->featured_image" :alt="$post->title" />
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-ink">{{ $post->title }}</p>
                                <p class="text-xs text-ink-muted">/blog/{{ $post->slug }}</p>
                            </td>
                            <td class="px-5 py-4 text-ink-muted">
                                {{ $post->published_at?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-4">
                                <x-admin-status-badge :active="$post->is_active" />
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ route('blog.show', $post) }}" target="_blank" class="text-ink-muted font-semibold hover:text-brand">View</a>
                                    <a href="{{ route('admin.master.blog-posts.edit', $post) }}" class="text-brand font-semibold hover:underline">Edit</a>
                                    <x-admin-delete-form :action="route('admin.master.blog-posts.destroy', $post)" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-ink-muted">No blog posts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($posts->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $posts->links() }}</div>
        @endif
    </div>
@endsection
