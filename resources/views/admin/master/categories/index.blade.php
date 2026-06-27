@extends('layouts.admin')

@section('title', 'Categories — Master')
@section('heading', 'Tour Categories')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <p class="text-sm text-ink-muted">Taj Mahal, Delhi, Jaipur, Golden Triangle, Varanasi pages</p>
        <a href="{{ route('admin.master.categories.create') }}" class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-xl hover:bg-brand-700">+ Add Category</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Category</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Slug</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">City</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-5 py-4 font-bold text-ink">{{ $category->title }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $category->slug }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $category->city_name }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.master.categories.edit', $category) }}" class="text-brand font-semibold hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-ink-muted">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
