@extends('layouts.admin')

@section('title', 'Monuments — Master')
@section('heading', 'Monuments')

@section('content')
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <form method="GET">
            <select name="category" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm">
                <option value="">All categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->title }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.master.monuments.create') }}" class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-xl">+ Add Monument</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left">
                <tr>
                    <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase w-16">Image</th>
                    <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Name</th>
                    <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Category</th>
                    <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($monuments as $monument)
                    <tr>
                        <td class="px-5 py-4">
                            <x-admin-image-thumb :src="$monument->image" :alt="$monument->name" />
                        </td>
                        <td class="px-5 py-4 font-bold">{{ $monument->name }}</td>
                        <td class="px-5 py-4 text-ink-muted">{{ $monument->category->title ?? '—' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ route('admin.master.monuments.edit', $monument) }}" class="text-brand font-semibold hover:underline">Edit</a>
                                <x-admin-delete-form :action="route('admin.master.monuments.destroy', $monument)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-ink-muted">No monuments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
