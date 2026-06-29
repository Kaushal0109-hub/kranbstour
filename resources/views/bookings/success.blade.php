@extends('layouts.app')

@section('title', 'Booking Confirmed — ' . config('site.name'))

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-lg w-full bg-white rounded-2xl border border-slate-100 shadow-soft p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-5">
            <i class="fas fa-check text-2xl" aria-hidden="true"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-ink mb-2">Booking Confirmed!</h1>
        <p class="text-sm text-ink-muted mb-6">Thank you, {{ $booking->customerDisplayName() }}. Your reservation has been received.</p>

        <div class="bg-surface rounded-xl p-5 text-left text-sm space-y-3 mb-6">
            <div class="flex justify-between gap-4">
                <span class="text-ink-muted">Reference</span>
                <span class="font-bold text-ink">{{ $booking->booking_ref }}</span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-ink-muted">Tour</span>
                <span class="font-semibold text-ink text-right">{{ $booking->package_title }}</span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-ink-muted">Travel date</span>
                <span class="font-semibold text-ink">{{ $booking->travel_date?->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-ink-muted">Travelers</span>
                <span class="font-semibold text-ink">{{ $booking->travelers }}</span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-ink-muted">Total</span>
                <span class="font-bold text-ink">{{ \App\Helpers\CurrencyHelper::formatAmount($booking->total_amount) }}</span>
            </div>
            <div class="flex justify-between gap-4">
                <span class="text-ink-muted">Payment</span>
                <span class="font-semibold text-ink text-right">{{ $booking->paymentOptionLabel() }}<br><span class="text-xs text-ink-muted">{{ $booking->paymentStatusLabel() }}</span></span>
            </div>
            @if ($booking->amount_paid > 0)
                <div class="flex justify-between gap-4">
                    <span class="text-ink-muted">Paid online</span>
                    <span class="font-bold text-emerald-700">{{ \App\Helpers\CurrencyHelper::formatAmount($booking->amount_paid) }}</span>
                </div>
            @endif
            @if ($booking->amount_due > 0 && $booking->payment_option !== 'full')
                <div class="flex justify-between gap-4">
                    <span class="text-ink-muted">Balance due</span>
                    <span class="font-bold text-amber-700">{{ \App\Helpers\CurrencyHelper::formatAmount($booking->amount_due) }}</span>
                </div>
            @endif
        </div>

        <p class="text-xs text-ink-muted mb-6">A confirmation has been noted for <strong>{{ $booking->customerDisplayEmail() }}</strong>. Our team will contact you shortly.</p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="btn-brand inline-flex justify-center text-white font-bold px-6 py-3 rounded-xl text-sm">Back to Home</a>
            @auth
                @if (auth()->user()->isCustomer())
                    <a href="{{ route('dashboard.bookings') }}" class="inline-flex justify-center border border-brand text-brand font-bold px-6 py-3 rounded-xl text-sm hover:bg-brand-50">My Bookings</a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection
