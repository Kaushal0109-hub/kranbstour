@extends('layouts.admin')

@section('title', 'Bookings — Admin')
@section('heading', 'Manage Bookings')

@section('content')
    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Customer</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Tour</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Date</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Price</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-bold text-ink">{{ $booking->user->name }}</p>
                                <p class="text-xs text-ink-muted">{{ $booking->user->email }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-ink">{{ $booking->package_title }}</p>
                                <p class="text-xs text-ink-muted">{{ $booking->city }}</p>
                            </td>
                            <td class="px-5 py-4 text-ink-muted">{{ $booking->travel_date?->format('d M Y') ?? '—' }}</td>
                            <td class="px-5 py-4 font-bold">₹{{ $booking->price }}</td>
                            <td class="px-5 py-4">
                                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="text-xs font-bold px-2 py-1.5 rounded-lg border border-slate-200 bg-surface">
                                        @foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                                            <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-ink-muted">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bookings->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $bookings->links() }}</div>
        @endif
    </div>
@endsection
