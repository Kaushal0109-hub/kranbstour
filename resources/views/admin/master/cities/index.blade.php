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
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase w-16">Image</th>
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
                                <x-admin-image-thumb :src="$city->card_image" :alt="$city->name" />
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-ink">{{ $city->name }}</p>
                                <p class="text-xs text-ink-muted">{{ $city->tagline }}</p>
                            </td>
                            <td class="px-5 py-4 text-ink-muted">{{ $city->key }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $city->tour_count_label ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <x-admin-status-badge :active="$city->is_active" />
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ route('admin.master.cities.edit', $city) }}" class="text-brand font-semibold hover:underline">Edit</a>
                                    <x-admin-delete-form :action="route('admin.master.cities.destroy', $city)" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-ink-muted">No cities yet. Run seeder or add manually.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($cities->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $cities->links() }}</div>
        @endif
    </div>
@endsection
