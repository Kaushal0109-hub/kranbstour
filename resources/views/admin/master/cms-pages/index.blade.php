@extends('layouts.admin')

@section('title', 'CMS Pages — Master')
@section('heading', 'CMS Pages')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <p class="text-sm text-ink-muted">About, Terms, Privacy and other static pages</p>
        <a href="{{ route('admin.master.cms-pages.create') }}" class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-xl hover:bg-brand-700">+ Add Page</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Page</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">URL</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Footer</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Status</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pages as $page)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-bold text-ink">{{ $page->title }}</p>
                                <p class="text-xs text-ink-muted">{{ $page->heading }}</p>
                            </td>
                            <td class="px-5 py-4 text-ink-muted">/{{ $page->slug }}</td>
                            <td class="px-5 py-4">
                                @if ($page->show_in_footer)
                                    <span class="text-xs font-bold text-brand">Yes</span>
                                @else
                                    <span class="text-xs text-ink-muted">No</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <x-admin-status-badge :active="$page->is_active" />
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ route($page->slug) }}" target="_blank" class="text-ink-muted font-semibold hover:text-brand">View</a>
                                    <a href="{{ route('admin.master.cms-pages.edit', $page) }}" class="text-brand font-semibold hover:underline">Edit</a>
                                    <x-admin-delete-form :action="route('admin.master.cms-pages.destroy', $page)" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-ink-muted">No pages yet. Run seeder or add manually.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pages->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $pages->links() }}</div>
        @endif
    </div>
@endsection
