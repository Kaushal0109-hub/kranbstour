@extends('layouts.admin')

@section('title', 'Cities — Master')
@section('heading', 'Manage Cities')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <p class="text-sm text-ink-muted">Homepage city cards & spotlight sections</p>
        <a href="{{ route('admin.master.cities.create') }}" class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-xl hover:bg-brand-700">+ Add City</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">City</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Key</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Tours</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Status</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($cities as $city)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-bold text-ink">{{ $city->name }}</p>
                                <p class="text-xs text-ink-muted">{{ $city->tagline }}</p>
                            </td>
                            <td class="px-5 py-4 text-ink-muted">{{ $city->key }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $city->tour_count_label ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <span @class(['text-xs font-bold px-2 py-1 rounded-lg', 'bg-emerald-50 text-emerald-700' => $city->is_active, 'bg-slate-100 text-slate-500' => !$city->is_active])>
                                    {{ $city->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.master.cities.edit', $city) }}" class="text-brand font-semibold hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-ink-muted">No cities yet. Run seeder or add manually.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($cities->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $cities->links() }}</div>
        @endif
    </div>
@endsection
