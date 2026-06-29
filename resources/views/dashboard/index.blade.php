@extends('layouts.dashboard')

@section('heading', 'Overview')

@section('content')
    <div class="rounded-2xl bg-gradient-to-r from-brand to-brand-600 text-white p-6 sm:p-8 mb-8 shadow-soft relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4" aria-hidden="true"></div>
        <div class="relative">
            <p class="text-brand-100 text-xs font-bold uppercase tracking-wider mb-1">Your trips</p>
            <h2 class="text-xl sm:text-2xl font-extrabold mb-2">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
            <p class="text-white/80 text-sm max-w-md">Track bookings, update your profile, and plan your next India adventure.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        @foreach ([
            ['Total Bookings', $stats['total'], 'fa-suitcase', 'from-brand-50 to-white', 'text-brand', 'border-brand-100'],
            ['Pending', $stats['pending'], 'fa-clock', 'from-amber-50 to-white', 'text-amber-600', 'border-amber-100'],
            ['Confirmed', $stats['confirmed'], 'fa-check-circle', 'from-emerald-50 to-white', 'text-emerald-600', 'border-emerald-100'],
        ] as [$label, $count, $icon, $bg, $iconColor, $border])
            <div class="bg-gradient-to-br {{ $bg }} rounded-2xl border {{ $border }} p-5 shadow-soft">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs text-ink-muted font-semibold uppercase tracking-wide">{{ $label }}</p>
                        <p class="text-3xl font-extrabold text-ink mt-1">{{ $count }}</p>
                    </div>
                    <span class="w-12 h-12 rounded-xl bg-white shadow-sm {{ $iconColor }} flex items-center justify-center text-lg">
                        <i class="fas {{ $icon }}" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-slate-100 bg-surface/50">
            <h2 class="font-extrabold text-ink">Recent Bookings</h2>
            @if ($bookings->isNotEmpty())
                <a href="{{ route('dashboard.bookings') }}" class="text-xs font-bold text-brand hover:text-brand-700">View all →</a>
            @endif
        </div>
        @if ($bookings->isEmpty())
            <div class="p-10 sm:p-14 text-center">
                <div class="w-16 h-16 rounded-2xl bg-surface text-ink-muted flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-suitcase" aria-hidden="true"></i>
                </div>
                <p class="text-ink font-semibold mb-1">No bookings yet</p>
                <p class="text-ink-muted text-sm mb-6">When you book a tour, it will show up here.</p>
                <a href="{{ route('tours.packages') }}" class="btn-brand inline-flex text-white text-sm font-bold px-6 py-3 rounded-xl">Explore Packages</a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach ($bookings as $booking)
                    <div class="px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-surface/40 transition-colors">
                        <div class="min-w-0">
                            <p class="font-bold text-ink text-sm truncate">{{ $booking->package_title }}</p>
                            <p class="text-xs text-ink-muted mt-0.5">{{ $booking->city }} · {{ \App\Helpers\CurrencyHelper::formatAmount(null, $booking->price) }} · {{ $booking->travelers }} travelers</p>
                        </div>
                        <span @class([
                            'text-[10px] font-bold uppercase px-3 py-1.5 rounded-full w-fit shrink-0',
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
