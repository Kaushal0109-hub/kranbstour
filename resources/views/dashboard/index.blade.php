@extends('layouts.dashboard')

@section('heading', 'Dashboard')

@section('content')
    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        @foreach ([
            ['Total Bookings', $stats['total'], 'fa-suitcase', 'brand'],
            ['Pending', $stats['pending'], 'fa-clock', 'accent'],
            ['Confirmed', $stats['confirmed'], 'fa-check-circle', 'emerald'],
        ] as [$label, $count, $icon, $color])
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-soft">
                <div class="flex items-center gap-3">
                    <span class="w-11 h-11 rounded-xl bg-brand-50 text-brand flex items-center justify-center">
                        <i class="fas {{ $icon }}" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p class="text-xs text-ink-muted font-semibold">{{ $label }}</p>
                        <p class="text-2xl font-extrabold text-ink">{{ $count }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-extrabold text-ink">Recent Bookings</h2>
            <a href="{{ route('dashboard.bookings') }}" class="text-xs font-bold text-brand">View all →</a>
        </div>
        @if ($bookings->isEmpty())
            <div class="p-10 text-center">
                <p class="text-ink-muted text-sm mb-4">No bookings yet.</p>
                <a href="{{ route('tours.packages') }}" class="btn-brand inline-flex text-white text-sm font-bold px-6 py-3 rounded-xl">Browse Tour Packages</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($bookings as $booking)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <p class="font-bold text-ink text-sm">{{ $booking->package_title }}</p>
                            <p class="text-xs text-ink-muted">{{ $booking->city }} · ₹{{ $booking->price }} · {{ $booking->travelers }} travelers</p>
                        </div>
                        <span @class([
                            'text-[10px] font-bold uppercase px-2.5 py-1 rounded-full w-fit',
                            'bg-amber-50 text-amber-700' => $booking->status === 'pending',
                            'bg-emerald-50 text-emerald-700' => $booking->status === 'confirmed',
                            'bg-slate-100 text-slate-600' => $booking->status === 'cancelled',
                            'bg-brand-50 text-brand' => $booking->status === 'completed',
                        ])>{{ $booking->statusLabel() }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
