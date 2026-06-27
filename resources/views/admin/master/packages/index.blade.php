@extends('layouts.admin')

@section('title', 'Packages — Master')
@section('heading', 'Tour Packages')

@section('content')
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <form method="GET" class="flex items-center gap-2">
            <select name="category" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm">
                <option value="">All categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->title }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.master.packages.create') }}" class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-xl">+ Add Package</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Package</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Category</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Price</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Featured</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($packages as $package)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-bold text-ink">{{ $package->title }}</p>
                                <p class="text-xs text-ink-muted">{{ $package->duration }} · ★ {{ $package->rating }}</p>
                            </td>
                            <td class="px-5 py-4 text-ink-muted">{{ $package->category->title ?? '—' }}</td>
                            <td class="px-5 py-4 font-bold">₹{{ $package->price_formatted }}</td>
                            <td class="px-5 py-4">
                                @if ($package->is_featured)
                                    <span class="text-xs font-bold px-2 py-1 rounded-lg bg-amber-50 text-amber-700">Featured</span>
                                @else
                                    <span class="text-xs text-ink-muted">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.master.packages.edit', $package) }}" class="text-brand font-semibold hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-ink-muted">No packages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($packages->hasPages())
            <div class="px-5 py-4 border-t">{{ $packages->links() }}</div>
        @endif
    </div>
@endsection
