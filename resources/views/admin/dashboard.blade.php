@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('heading', 'Dashboard Overview')

@section('content')
    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        @foreach ([
            ['Customers', $stats['customers'], 'fa-users', 'bg-blue-50 text-blue-600'],
            ['Total Bookings', $stats['bookings'], 'fa-suitcase', 'bg-brand-50 text-brand'],
            ['Pending', $stats['pending_bookings'], 'fa-clock', 'bg-amber-50 text-amber-600'],
            ['Unread Messages', $stats['messages'], 'fa-envelope', 'bg-red-50 text-red-600'],
        ] as [$label, $count, $icon, $badgeClass])
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-ink-muted">{{ $label }}</p>
                        <p class="text-3xl font-extrabold text-ink mt-1">{{ $count }}</p>
                    </div>
                    <span class="w-11 h-11 rounded-xl flex items-center justify-center {{ $badgeClass }}">
                        <i class="fas {{ $icon }}" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-extrabold text-ink">Recent Bookings</h2>
                <a href="{{ route('admin.bookings') }}" class="text-xs font-bold text-brand">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentBookings as $booking)
                    <div class="px-6 py-4">
                        <p class="font-bold text-sm text-ink">{{ $booking->package_title }}</p>
                        <p class="text-xs text-ink-muted">{{ $booking->user?->name ?? $booking->customerDisplayName() }} · {{ \App\Helpers\CurrencyHelper::formatAmount($booking->total_amount ?: $booking->price) }}</p>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-ink-muted text-center">No bookings yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-extrabold text-ink">Recent Messages</h2>
                <a href="{{ route('admin.messages') }}" class="text-xs font-bold text-brand">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentMessages as $msg)
                    <div class="px-6 py-4">
                        <p class="font-bold text-sm text-ink">{{ $msg->name }}</p>
                        <p class="text-xs text-ink-muted truncate">{{ $msg->message }}</p>
                    </div>
                @empty
                    <p class="px-6 py-8 text-sm text-ink-muted text-center">No messages yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
