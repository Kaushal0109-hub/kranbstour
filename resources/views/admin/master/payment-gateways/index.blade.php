@extends('layouts.admin')

@section('title', 'Payment Gateways')
@section('heading', 'Payment Gateways')

@section('content')
    <p class="text-sm text-ink-muted mb-6 max-w-2xl">
        Enable a gateway, enter API credentials, and save. Active gateways appear on the booking checkout page.
    </p>

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($gateways as $gateway)
            @php $meta = $gateway->meta(); @endphp
            <div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-5 flex flex-col">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-11 h-11 rounded-xl flex items-center justify-center text-white shrink-0"
                              style="background-color: {{ $meta['brand_color'] ?? '#1a8578' }}">
                            <i class="{{ $meta['icon'] ?? 'fa-credit-card' }}" aria-hidden="true"></i>
                        </span>
                        <div class="min-w-0">
                            <h3 class="font-extrabold text-ink">{{ $gateway->name }}</h3>
                            <p class="text-[11px] text-ink-muted">{{ $meta['regions'] ?? 'Online payments' }}</p>
                        </div>
                    </div>
                    <span @class([
                        'text-[10px] font-bold uppercase px-2 py-1 rounded-full shrink-0',
                        'bg-emerald-50 text-emerald-700' => $gateway->isReady(),
                        'bg-slate-100 text-slate-500' => ! $gateway->isReady(),
                    ])>{{ $gateway->isReady() ? 'Active' : 'Inactive' }}</span>
                </div>

                <p class="text-xs text-ink-muted leading-relaxed flex-1 mb-4">
                    {{ $gateway->description }}
                </p>

                <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100">
                    <span class="text-[11px] text-ink-muted">
                        @if ($gateway->isConfigured())
                            {{ $gateway->modeLabel() }}
                        @else
                            Not configured
                        @endif
                    </span>
                    <a href="{{ route('admin.master.payment-gateways.edit', $gateway) }}"
                       class="text-sm font-bold text-brand hover:underline">
                        Configure →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
