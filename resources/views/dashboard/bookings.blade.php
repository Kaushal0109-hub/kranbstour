@extends('layouts.dashboard')

@section('heading', 'My Bookings')

@section('content')
    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        @if ($bookings->isEmpty())
            <div class="p-10 text-center">
                <p class="text-ink-muted text-sm mb-4">You haven't booked any tours yet.</p>
                <a href="{{ route('tours.packages') }}" class="btn-brand inline-flex text-white text-sm font-bold px-6 py-3 rounded-xl">Explore Packages</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface text-left">
                        <tr>
                            <th class="px-6 py-3 font-bold text-ink-muted text-xs uppercase">Tour</th>
                            <th class="px-6 py-3 font-bold text-ink-muted text-xs uppercase">Date</th>
                            <th class="px-6 py-3 font-bold text-ink-muted text-xs uppercase">Travelers</th>
                            <th class="px-6 py-3 font-bold text-ink-muted text-xs uppercase">Price</th>
                            <th class="px-6 py-3 font-bold text-ink-muted text-xs uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-ink">{{ $booking->package_title }}</p>
                                    <p class="text-xs text-ink-muted">{{ $booking->city }}</p>
                                </td>
                                <td class="px-6 py-4 text-ink-muted">{{ $booking->travel_date?->format('d M Y') ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $booking->travelers }}</td>
                                <td class="px-6 py-4 font-bold">₹{{ $booking->price }}</td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'text-[10px] font-bold uppercase px-2.5 py-1 rounded-full',
                                        'bg-amber-50 text-amber-700' => $booking->status === 'pending',
                                        'bg-emerald-50 text-emerald-700' => $booking->status === 'confirmed',
                                        'bg-slate-100 text-slate-600' => $booking->status === 'cancelled',
                                        'bg-brand-50 text-brand' => $booking->status === 'completed',
                                    ])>{{ $booking->statusLabel() }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($bookings->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $bookings->links() }}</div>
            @endif
        @endif
    </div>
@endsection
