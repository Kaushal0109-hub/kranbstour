@extends('layouts.app')

@section('body_class', 'bg-surface')

@section('title', 'Complete Your Booking — ' . config('site.name'))
@section('meta_description', 'Complete your tour booking for ' . $package['title'])

@section('content')
@php
    use App\Helpers\CurrencyHelper;
    use App\Helpers\MediaHelper;
    use App\Services\TourCatalog;

    $unitPrice = CurrencyHelper::parseNumeric($package['price']);
    $onlinePaymentAvailable = $onlinePaymentAvailable ?? false;
    $activeGateways = $activeGateways ?? collect();
    $defaultGateway = $activeGateways->first();
    $authUser = auth()->user();
    $nameParts = explode(' ', $authUser?->name ?? '', 2);
    $packageUrl = TourCatalog::packageUrl($categorySlug, $package);
    $duration = $package['duration'] ?? $package['features']['duration'] ?? null;
    $countries = ['India', 'United States', 'United Kingdom', 'Canada', 'Australia', 'Germany', 'France', 'Italy', 'Spain', 'Netherlands', 'Singapore', 'UAE', 'Japan', 'Other'];
    $mapsApiKey = $mapsApiKey ?? config('site.maps.google_api_key');
    $pickupCity = $pickupCity ?? 'India';
@endphp

<div class="bg-gradient-to-b from-surface via-white to-surface min-h-screen pb-20 lg:pb-16">
    {{-- Top bar --}}
    <div class="bg-white border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="text-xs text-ink-muted flex flex-wrap items-center gap-x-1.5 gap-y-1 mb-3" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-brand transition-colors">Home</a>
                <span class="text-slate-300" aria-hidden="true">/</span>
                <a href="{{ route('tours.packages') }}" class="hover:text-brand transition-colors">Tours</a>
                <span class="text-slate-300" aria-hidden="true">/</span>
                <a href="{{ $packageUrl }}" class="hover:text-brand transition-colors truncate max-w-[140px] sm:max-w-none">{{ $category['city'] ?? 'Tour' }}</a>
                <span class="text-slate-300" aria-hidden="true">/</span>
                <span class="text-ink font-semibold">Book</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-brand mb-1">Secure checkout</p>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-ink leading-tight">Complete your booking</h1>
                    <p class="text-sm text-ink-muted mt-1 line-clamp-2">{{ $package['title'] }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full">
                        <i class="fas fa-lock text-[10px]" aria-hidden="true"></i> SSL secured
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-brand bg-brand-50 border border-brand-100 px-3 py-1.5 rounded-full">
                        <i class="fas fa-bolt text-[10px]" aria-hidden="true"></i> Instant confirm
                    </span>
                </div>
            </div>

            {{-- Steps --}}
            <ol class="flex items-center gap-2 sm:gap-4 mt-6 text-xs font-semibold">
                <li class="flex items-center gap-2 text-brand">
                    <span class="w-7 h-7 rounded-full bg-brand text-white flex items-center justify-center text-[11px] font-extrabold">1</span>
                    <span class="hidden sm:inline">Your details</span>
                </li>
                <li class="flex-1 h-px bg-slate-200 max-w-12 sm:max-w-20" aria-hidden="true"></li>
                <li class="flex items-center gap-2 text-ink-muted">
                    <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[11px] font-extrabold">2</span>
                    <span class="hidden sm:inline">Payment</span>
                </li>
                <li class="flex-1 h-px bg-slate-200 max-w-12 sm:max-w-20" aria-hidden="true"></li>
                <li class="flex items-center gap-2 text-ink-muted">
                    <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[11px] font-extrabold">3</span>
                    <span class="hidden sm:inline">Confirmation</span>
                </li>
            </ol>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8 items-start">

            <div class="lg:col-span-2 order-2 lg:order-1 space-y-6">

                <form id="booking-checkout-form" action="{{ route('bookings.checkout') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="category_slug" value="{{ $categorySlug }}">
                    <input type="hidden" name="package_slug" value="{{ $package['slug'] }}">

                    {{-- Your Information --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
                        <div class="px-5 sm:px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-brand-50/80 to-white flex items-start gap-4">
                            <span class="w-11 h-11 rounded-xl bg-brand text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h2 class="font-extrabold text-ink text-base">Your information</h2>
                                <p class="text-xs text-ink-muted mt-0.5">We'll send your voucher and updates to this email</p>
                            </div>
                        </div>
                        <div class="p-5 sm:p-6 space-y-5">
                            @if ($errors->any())
                                <div class="text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-ink-muted mb-1.5">First Name *</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                                        <input type="text" name="customer_first_name" required maxlength="60"
                                               value="{{ old('customer_first_name', $nameParts[0] ?? '') }}"
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 transition-shadow">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-ink-muted mb-1.5">Last Name *</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                                        <input type="text" name="customer_last_name" required maxlength="60"
                                               value="{{ old('customer_last_name', $nameParts[1] ?? '') }}"
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 transition-shadow">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-ink-muted mb-1.5">Email Address *</label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                                    <input type="email" name="customer_email" required maxlength="120"
                                           value="{{ old('customer_email', $authUser?->email) }}"
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 transition-shadow">
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-ink-muted mb-1.5">Phone Number *</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                                        <input type="tel" name="customer_phone" required maxlength="30"
                                               value="{{ old('customer_phone') }}"
                                               placeholder="+91 98765 43210"
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 transition-shadow">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-ink-muted mb-1.5">Country of Residence *</label>
                                    <div class="relative">
                                        <i class="fas fa-globe absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                                        <select name="country" required
                                                class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 appearance-none transition-shadow">
                                            <option value="">Select country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country }}" @selected(old('country') === $country)>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-ink-muted mb-1.5">Travel Date *</label>
                                    <div class="relative">
                                        <i class="fas fa-calendar-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                                        <input type="date" name="travel_date" required min="{{ date('Y-m-d') }}"
                                               value="{{ old('travel_date', request('date')) }}"
                                               data-summary-date
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 transition-shadow">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-ink-muted mb-1.5">Guests *</label>
                                    <div class="relative">
                                        <i class="fas fa-users absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                                        <select name="travelers" required data-booking-travelers data-summary-travelers
                                                class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 appearance-none transition-shadow">
                                            @for ($i = 1; $i <= 20; $i++)
                                                <option value="{{ $i }}" @selected((int) old('travelers', request('travelers', 1)) === $i)>
                                                    {{ $i }} {{ $i === 1 ? 'Adult' : 'Adults' }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl border border-slate-100 bg-surface/40 p-4">
                                <p class="text-xs font-bold text-ink mb-3 flex items-center gap-2">
                                    <i class="fas fa-map-pin text-brand" aria-hidden="true"></i> Pickup preference
                                </p>
                                <div class="grid sm:grid-cols-2 gap-3">
                                    <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 border-slate-200 bg-white cursor-pointer hover:border-brand/30 has-[:checked]:border-brand has-[:checked]:bg-brand-50/40 transition-colors">
                                        <input type="radio" name="pickup_preference" value="operator"
                                               @checked(old('pickup_preference', 'location') === 'operator')
                                               data-pickup-pref class="mt-0.5 text-brand shrink-0">
                                        <span class="text-sm text-ink leading-snug">Contact tour operator <span class="block text-xs text-ink-muted mt-0.5">Details in voucher</span></span>
                                    </label>
                                    <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 border-slate-200 bg-white cursor-pointer hover:border-brand/30 has-[:checked]:border-brand has-[:checked]:bg-brand-50/40 transition-colors">
                                        <input type="radio" name="pickup_preference" value="location"
                                               @checked(old('pickup_preference', 'location') === 'location')
                                               data-pickup-pref class="mt-0.5 text-brand shrink-0">
                                        <span class="text-sm text-ink leading-snug">Enter pick-up location <span class="block text-xs text-ink-muted mt-0.5">Hotel or address</span></span>
                                    </label>
                                </div>
                            </div>

                            <div id="pickup-location-wrap">
                                <label class="block text-xs font-bold text-ink-muted mb-1.5" for="pickup-location-input">Pickup Location</label>
                                <div class="relative">
                                    <i class="fas fa-map-marker-alt absolute left-3.5 top-3 text-slate-400 text-sm z-10 pointer-events-none" aria-hidden="true"></i>
                                    <input type="text" id="pickup-location-input" name="pickup_location" maxlength="255"
                                           value="{{ old('pickup_location') }}"
                                           placeholder="Search hotel, address, or landmark"
                                           autocomplete="off"
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 transition-shadow">
                                </div>
                                <input type="hidden" name="pickup_latitude" id="pickup-latitude" value="{{ old('pickup_latitude') }}">
                                <input type="hidden" name="pickup_longitude" id="pickup-longitude" value="{{ old('pickup_longitude') }}">
                                @if ($mapsApiKey)
                                    <p class="text-[11px] text-ink-muted mt-1.5 flex items-center gap-1.5">
                                        <i class="fas fa-search text-brand text-[10px]" aria-hidden="true"></i>
                                        Start typing to search places near {{ $pickupCity }}
                                    </p>
                                @else
                                    <p class="text-[11px] text-amber-700 mt-1.5">Enter your hotel name or full address manually.</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-ink-muted mb-1.5">Special Requests</label>
                                <textarea name="notes" rows="2" maxlength="1000" placeholder="Dietary needs, accessibility, guide language, etc."
                                          class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface/50 text-sm resize-y focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/15 transition-shadow">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Options --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
                        <div class="px-5 sm:px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-accent/10 to-white flex items-start gap-4">
                            <span class="w-11 h-11 rounded-xl bg-accent text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fas fa-credit-card" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h2 class="font-extrabold text-ink text-base">Payment options</h2>
                                <p class="text-xs text-ink-muted mt-0.5">Flexible ways to secure your booking</p>
                            </div>
                        </div>
                        <div class="p-5 sm:p-6 space-y-5">
                            <div class="space-y-3">
                                <label class="flex items-center justify-between gap-4 p-4 sm:p-5 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer hover:border-brand/40 hover:shadow-sm has-[:checked]:border-brand has-[:checked]:bg-brand-50/30 has-[:checked]:shadow-md transition-all">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <input type="radio" name="payment_option" value="arrival" checked class="mt-1 text-brand shrink-0">
                                        <div class="min-w-0">
                                            <span class="flex items-center gap-2 font-bold text-sm text-ink">
                                                <i class="fas fa-hand-holding-usd text-brand text-xs" aria-hidden="true"></i>
                                                Pay on arrival
                                            </span>
                                            <span class="block text-xs text-ink-muted mt-1">Pay the full amount when you arrive</span>
                                        </div>
                                    </div>
                                    <span class="text-base sm:text-lg font-extrabold text-brand shrink-0" data-pay-amount="arrival">—</span>
                                </label>

                                <label class="flex items-center justify-between gap-4 p-4 sm:p-5 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer hover:border-brand/40 hover:shadow-sm has-[:checked]:border-brand has-[:checked]:bg-brand-50/30 has-[:checked]:shadow-md transition-all @unless($onlinePaymentAvailable) opacity-50 pointer-events-none @endunless">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <input type="radio" name="payment_option" value="deposit" class="mt-1 text-brand shrink-0" @disabled(! $onlinePaymentAvailable)>
                                        <div class="min-w-0">
                                            <span class="flex items-center gap-2 font-bold text-sm text-ink">
                                                <i class="fas fa-percentage text-accent text-xs" aria-hidden="true"></i>
                                                Pay 30% & reserve
                                            </span>
                                            <span class="block text-xs text-ink-muted mt-1" data-pay-desc="deposit">Pay 30% now online</span>
                                        </div>
                                    </div>
                                    <span class="text-base sm:text-lg font-extrabold text-brand shrink-0" data-pay-amount="deposit">—</span>
                                </label>

                                <label class="flex items-center justify-between gap-4 p-4 sm:p-5 rounded-2xl border-2 border-slate-200 bg-white cursor-pointer hover:border-brand/40 hover:shadow-sm has-[:checked]:border-brand has-[:checked]:bg-brand-50/30 has-[:checked]:shadow-md transition-all @unless($onlinePaymentAvailable) opacity-50 pointer-events-none @endunless">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <input type="radio" name="payment_option" value="full" class="mt-1 text-brand shrink-0" @disabled(! $onlinePaymentAvailable)>
                                        <div class="min-w-0">
                                            <span class="flex items-center gap-2 font-bold text-sm text-ink">
                                                <i class="fas fa-check-circle text-emerald-600 text-xs" aria-hidden="true"></i>
                                                Pay 100% & book
                                            </span>
                                            <span class="block text-xs text-ink-muted mt-1">Pay the full amount now online</span>
                                        </div>
                                    </div>
                                    <span class="text-base sm:text-lg font-extrabold text-brand shrink-0" data-pay-amount="full">—</span>
                                </label>
                            </div>

                            @if ($onlinePaymentAvailable)
                                <div id="gateway-select-wrap" class="hidden pt-2 border-t border-slate-100">
                                    <p class="text-xs font-bold text-ink-muted mb-2">Payment method</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($activeGateways as $gw)
                                            @php $gMeta = $gw->meta(); @endphp
                                            <label class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 cursor-pointer has-[:checked]:border-brand has-[:checked]:bg-brand-50/40 text-sm font-semibold">
                                                <input type="radio" name="payment_gateway" value="{{ $gw->slug }}"
                                                       @checked($loop->first) class="text-brand">
                                                <i class="{{ $gMeta['icon'] ?? 'fa-credit-card' }}" aria-hidden="true"></i>
                                                {{ $gw->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                                    Online payment is not available. You can book with Pay on Arrival.
                                </p>
                            @endif

                            <div id="booking-form-error" class="hidden text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3"></div>

                            <div id="booking-submit-wrap">
                                <button type="submit" id="booking-submit-btn" class="btn-accent w-full text-white font-extrabold py-4 rounded-xl text-base shadow-lg shadow-accent/25 hover:shadow-xl hover:shadow-accent/30 transition-shadow flex items-center justify-center gap-2">
                                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                                    <span id="booking-submit-label">Confirm Booking</span>
                                </button>
                                <p class="text-[11px] text-center text-ink-muted mt-3 flex items-center justify-center gap-3 flex-wrap">
                                    <span><i class="fas fa-shield-alt text-brand mr-1" aria-hidden="true"></i>Secure booking</span>
                                    <span><i class="fas fa-undo text-brand mr-1" aria-hidden="true"></i>Free cancellation*</span>
                                </p>
                            </div>

                            <div id="booking-payment-wrap" class="hidden space-y-4">
                                <p class="text-xs text-ink-muted text-center" id="payment-wrap-label">Complete payment securely</p>
                                <div id="paypal-button-container"></div>
                                <div id="stripe-payment-element" class="hidden"></div>
                                <button type="button" id="stripe-pay-btn" class="hidden btn-brand w-full text-white font-bold py-3.5 rounded-xl text-sm">Pay with Card</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <aside class="lg:col-span-1 order-first lg:order-2">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden lg:sticky lg:top-20">
                    <div class="relative @if(empty($package['image'])) bg-gradient-to-br from-brand to-brand-600 @endif">
                        @if (! empty($package['image']))
                            <div class="h-40 sm:h-44 overflow-hidden relative">
                                <img src="{{ MediaHelper::url($package['image']) }}" alt="{{ $package['title'] }}"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/30 to-transparent"></div>
                            </div>
                        @endif
                        <div @class([
                            'p-5',
                            'absolute bottom-0 left-0 right-0' => ! empty($package['image']),
                        ])>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-white/80 mb-1">{{ $category['city'] ?? $category['name'] ?? 'Tour' }}</p>
                            <h3 class="font-extrabold text-white text-sm sm:text-base leading-snug line-clamp-2">{{ $package['title'] }}</h3>
                            @if (! empty($package['rating']))
                                <div class="flex items-center gap-1.5 mt-2">
                                    <span class="inline-flex text-amber-400 text-[10px]">
                                        @for ($s = 1; $s <= 5; $s++)<i class="fas fa-star" aria-hidden="true"></i>@endfor
                                    </span>
                                    <span class="text-xs font-bold text-white">{{ $package['rating'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-5 sm:p-6 space-y-4">
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between gap-3 items-center py-2 border-b border-slate-50">
                                <dt class="text-ink-muted flex items-center gap-2"><i class="fas fa-calendar text-brand/70 w-4 text-center text-xs" aria-hidden="true"></i> Date</dt>
                                <dd class="font-bold text-ink text-right" data-summary-date-display>—</dd>
                            </div>
                            @if ($duration)
                                <div class="flex justify-between gap-3 items-center py-2 border-b border-slate-50">
                                    <dt class="text-ink-muted flex items-center gap-2"><i class="fas fa-clock text-brand/70 w-4 text-center text-xs" aria-hidden="true"></i> Duration</dt>
                                    <dd class="font-bold text-ink text-right">{{ $duration }}</dd>
                                </div>
                            @endif
                            <div class="flex justify-between gap-3 items-center py-2 border-b border-slate-50">
                                <dt class="text-ink-muted flex items-center gap-2"><i class="fas fa-users text-brand/70 w-4 text-center text-xs" aria-hidden="true"></i> Guests</dt>
                                <dd class="font-bold text-ink text-right" data-summary-travelers-display>1 Adult</dd>
                            </div>
                            <div class="flex justify-between gap-3 items-center py-2">
                                <dt class="text-ink-muted flex items-center gap-2"><i class="fas fa-tag text-brand/70 w-4 text-center text-xs" aria-hidden="true"></i> Per person</dt>
                                <dd class="font-bold text-ink">{{ CurrencyHelper::formatAmount($unitPrice) }}</dd>
                            </div>
                        </dl>

                        @if (! empty($package['summary']) || ! empty($package['description']))
                            <p class="text-xs text-ink-muted leading-relaxed bg-surface rounded-xl p-3 border border-slate-100">
                                {{ \Illuminate\Support\Str::limit($package['summary'] ?? $package['description'], 140) }}
                            </p>
                        @endif

                        <div class="rounded-xl bg-gradient-to-br from-brand-50 to-white border border-brand-100 p-4">
                            <div class="flex justify-between items-center">
                                <span class="font-extrabold text-ink">Total</span>
                                <span class="text-2xl font-extrabold text-brand" data-summary-total>{{ CurrencyHelper::formatAmount($unitPrice) }}</span>
                            </div>
                            <p class="text-[10px] text-ink-muted mt-1">Includes taxes · Price updates with guests</p>
                        </div>

                        <ul class="space-y-2 text-[11px] text-ink-muted">
                            @foreach (['No hidden fees', 'Licensed local guides', '24/7 customer support'] as $perk)
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-brand text-[10px]" aria-hidden="true"></i>{{ $perk }}
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ $packageUrl }}" class="flex items-center justify-center gap-2 text-xs font-bold text-brand hover:text-brand-700 py-2.5 rounded-xl border border-brand/20 hover:bg-brand-50 transition-colors">
                            <i class="fas fa-arrow-left text-[10px]" aria-hidden="true"></i>
                            Back to tour details
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    {{-- Mobile sticky total --}}
    <div class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur border-t border-slate-200 px-4 py-3 shadow-[0_-8px_30px_rgba(15,23,42,0.08)]">
        <div class="flex items-center justify-between gap-4 max-w-6xl mx-auto">
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wide text-ink-muted">Total</p>
                <p class="text-lg font-extrabold text-brand truncate" data-summary-total-mobile>{{ CurrencyHelper::formatAmount($unitPrice) }}</p>
            </div>
            <button type="button" onclick="document.getElementById('booking-submit-btn')?.click()"
                    class="btn-accent shrink-0 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-lg shadow-accent/20">
                Book now
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pac-container {
        z-index: 10000 !important;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 40px rgba(15, 23, 42, 0.12);
        font-family: 'Plus Jakarta Sans', sans-serif;
        margin-top: 4px;
    }
    .pac-item {
        padding: 10px 12px;
        font-size: 13px;
        cursor: pointer;
    }
    .pac-item:hover {
        background: #f0fdfa;
    }
    @media (max-width: 1023px) {
        body.bg-surface { padding-bottom: 5rem; }
    }
</style>
@endpush

@push('scripts')
@if ($activeGateways->contains('slug', 'paypal'))
    @php $paypalGw = $activeGateways->firstWhere('slug', 'paypal'); @endphp
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalGw->credential('client_id') }}&currency={{ config('site.currency.code', 'USD') }}&disable-funding=card"></script>
@endif
@if ($activeGateways->contains('slug', 'stripe'))
    <script src="https://js.stripe.com/v3/"></script>
@endif
@if ($activeGateways->contains('slug', 'razorpay'))
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif
<script>
(function () {
    const form = document.getElementById('booking-checkout-form');
    if (!form) return;

    const unitPrice = {{ json_encode($unitPrice) }};
    const currencySymbol = @json(CurrencyHelper::symbol());
    const currencyCode = @json(config('site.currency.code', 'USD'));
    const checkoutUrl = @json(route('bookings.checkout'));
    const confirmUrlBase = @json(url('/bookings'));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const onlineAvailable = @json($onlinePaymentAvailable);

    let paymentData = null;
    let stripeInstance = null;
    let stripeElements = null;

    function formatMoney(amount) {
        return currencySymbol + Math.round(amount).toLocaleString();
    }

    function formatDateDisplay(value) {
        if (!value) return '—';
        const d = new Date(value + 'T00:00:00');
        return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function updateTotals() {
        const travelers = parseInt(form.travelers.value, 10) || 1;
        const total = unitPrice * travelers;
        const deposit = Math.round(total * 0.30 * 100) / 100;
        const balance = Math.round((total - deposit) * 100) / 100;

        document.querySelector('[data-summary-total]').textContent = formatMoney(total);
        const mobileTotal = document.querySelector('[data-summary-total-mobile]');
        if (mobileTotal) mobileTotal.textContent = formatMoney(total);
        document.querySelector('[data-summary-travelers-display]').textContent =
            travelers + (travelers === 1 ? ' Adult' : ' Adults');

        const dateInput = form.querySelector('[data-summary-date]');
        const dateDisplay = document.querySelector('[data-summary-date-display]');
        if (dateDisplay) dateDisplay.textContent = formatDateDisplay(dateInput?.value);

        document.querySelector('[data-pay-amount="arrival"]').textContent = formatMoney(total);
        document.querySelector('[data-pay-amount="deposit"]').textContent = formatMoney(deposit);
        document.querySelector('[data-pay-amount="full"]').textContent = formatMoney(total);

        const depositDesc = document.querySelector('[data-pay-desc="deposit"]');
        if (depositDesc) {
            depositDesc.textContent = 'Pay 30% now, and ' + formatMoney(balance) + ' on arrival';
        }
    }

    function selectedPayment() {
        return form.querySelector('input[name="payment_option"]:checked')?.value || 'arrival';
    }

    function toggleGatewaySelect() {
        const wrap = document.getElementById('gateway-select-wrap');
        if (!wrap) return;
        const show = onlineAvailable && selectedPayment() !== 'arrival';
        wrap.classList.toggle('hidden', !show);
    }

    function togglePickupField() {
        const pref = form.querySelector('input[name="pickup_preference"]:checked')?.value || 'location';
        const wrap = document.getElementById('pickup-location-wrap');
        const input = document.getElementById('pickup-location-input');
        if (pref === 'operator') {
            wrap.classList.add('hidden');
            input.removeAttribute('required');
        } else {
            wrap.classList.remove('hidden');
            input.setAttribute('required', 'required');
        }
    }

    function showError(message) {
        const el = document.getElementById('booking-form-error');
        el.textContent = message;
        el.classList.remove('hidden');
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearError() {
        const el = document.getElementById('booking-form-error');
        el.textContent = '';
        el.classList.add('hidden');
    }

    function setSubmitLabel(text) {
        const label = document.getElementById('booking-submit-label');
        if (label) label.textContent = text;
        else document.getElementById('booking-submit-btn').textContent = text;
    }

    function submitBtnLabel() {
        return selectedPayment() === 'arrival' ? 'Confirm Booking' : 'Continue to Payment';
    }

    function resetPaymentStep() {
        paymentData = null;
        document.getElementById('booking-submit-wrap').classList.remove('hidden');
        document.getElementById('booking-payment-wrap').classList.add('hidden');
        document.getElementById('paypal-button-container').innerHTML = '';
        document.getElementById('stripe-payment-element').innerHTML = '';
        document.getElementById('stripe-payment-element').classList.add('hidden');
        document.getElementById('stripe-pay-btn').classList.add('hidden');
        const btn = document.getElementById('booking-submit-btn');
        btn.disabled = false;
        setSubmitLabel(submitBtnLabel());
    }

    function confirmPayment(payload) {
        return fetch(confirmUrlBase + '/' + paymentData.booking_id + '/payment/confirm', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(res => res.json().then(body => ({ ok: res.ok, body })))
        .then(({ ok, body }) => {
            if (!ok) throw new Error(body.message || 'Payment failed.');
            window.location.href = body.redirect;
        });
    }

    function showPaymentUI(data) {
        paymentData = data;
        document.getElementById('booking-submit-wrap').classList.add('hidden');
        document.getElementById('booking-payment-wrap').classList.remove('hidden');

        if (data.gateway === 'paypal' && typeof paypal !== 'undefined') {
            document.getElementById('payment-wrap-label').textContent = 'Complete payment with PayPal';
            paypal.Buttons({
                createOrder: () => data.order_id,
                onApprove: (res) => confirmPayment({ order_id: res.orderID }),
                onError: () => showError('PayPal error. Please try again.'),
            }).render('#paypal-button-container');
        }

        if (data.gateway === 'stripe' && typeof Stripe !== 'undefined') {
            document.getElementById('payment-wrap-label').textContent = 'Enter card details';
            stripeInstance = Stripe(data.publishable_key);
            stripeElements = stripeInstance.elements({ clientSecret: data.client_secret });
            const paymentElement = stripeElements.create('payment');
            const el = document.getElementById('stripe-payment-element');
            el.classList.remove('hidden');
            paymentElement.mount('#stripe-payment-element');
            const payBtn = document.getElementById('stripe-pay-btn');
            payBtn.classList.remove('hidden');
            payBtn.onclick = function () {
                payBtn.disabled = true;
                payBtn.textContent = 'Processing...';
                stripeInstance.confirmPayment({
                    elements: stripeElements,
                    redirect: 'if_required',
                }).then(result => {
                    if (result.error) {
                        showError(result.error.message);
                        payBtn.disabled = false;
                        payBtn.textContent = 'Pay with Card';
                        return;
                    }
                    confirmPayment({ payment_intent_id: result.paymentIntent.id })
                        .catch(err => {
                            showError(err.message);
                            payBtn.disabled = false;
                            payBtn.textContent = 'Pay with Card';
                        });
                });
            };
        }

        if (data.gateway === 'razorpay' && typeof Razorpay !== 'undefined') {
            document.getElementById('payment-wrap-label').textContent = 'Click below to open Razorpay checkout';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-brand w-full text-white font-bold py-3.5 rounded-xl text-sm';
            btn.textContent = 'Pay with Razorpay';
            btn.onclick = function () {
                const rzp = new Razorpay({
                    key: data.key_id,
                    amount: Math.round(data.amount * 100),
                    currency: data.currency,
                    name: @json(config('site.name')),
                    description: @json($package['title']),
                    order_id: data.order_id,
                    prefill: {
                        name: data.customer_name,
                        email: data.customer_email,
                        contact: data.customer_phone,
                    },
                    handler: function (response) {
                        confirmPayment({
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_signature: response.razorpay_signature,
                        }).catch(err => showError(err.message));
                    },
                });
                rzp.on('payment.failed', function () {
                    showError('Payment failed. Please try again.');
                });
                rzp.open();
            };
            document.getElementById('paypal-button-container').appendChild(btn);
        }
    }

    form.travelers.addEventListener('change', updateTotals);
    form.querySelector('[data-summary-date]')?.addEventListener('change', updateTotals);
    form.querySelectorAll('input[name="payment_option"]').forEach(radio => {
        radio.addEventListener('change', () => {
            toggleGatewaySelect();
            setSubmitLabel(submitBtnLabel());
        });
    });
    form.querySelectorAll('[data-pickup-pref]').forEach(radio => {
        radio.addEventListener('change', togglePickupField);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearError();

        const btn = document.getElementById('booking-submit-btn');
        btn.disabled = true;
        setSubmitLabel('Please wait...');

        fetch(checkoutUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: new FormData(form),
        })
        .then(res => res.json().then(body => ({ ok: res.ok, body })))
        .then(({ ok, body }) => {
            if (!ok) throw new Error(body.message || Object.values(body.errors || {})[0]?.[0] || 'Booking failed.');
            if (body.redirect) {
                window.location.href = body.redirect;
                return;
            }
            showPaymentUI(body);
        })
        .catch(err => {
            showError(err.message);
            btn.disabled = false;
            setSubmitLabel(submitBtnLabel());
        });
    });

    togglePickupField();
    toggleGatewaySelect();
    updateTotals();
})();
</script>
@if ($mapsApiKey)
<script>
window.initPickupAutocomplete = function () {
    const input = document.getElementById('pickup-location-input');
    if (!input || !window.google?.maps?.places) return;

    const autocomplete = new google.maps.places.Autocomplete(input, {
        fields: ['formatted_address', 'name', 'geometry', 'place_id'],
        types: ['establishment', 'geocode', 'point_of_interest'],
        componentRestrictions: { country: 'in' },
    });

    autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();
        if (place.formatted_address) {
            input.value = place.formatted_address;
        } else if (place.name) {
            input.value = place.name;
        }
        const latEl = document.getElementById('pickup-latitude');
        const lngEl = document.getElementById('pickup-longitude');
        if (place.geometry?.location) {
            latEl.value = place.geometry.location.lat();
            lngEl.value = place.geometry.location.lng();
        } else {
            latEl.value = '';
            lngEl.value = '';
        }
    });

    input.addEventListener('input', function () {
        if (!input.value.trim()) {
            document.getElementById('pickup-latitude').value = '';
            document.getElementById('pickup-longitude').value = '';
        }
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') e.preventDefault();
    });
};
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $mapsApiKey }}&libraries=places&callback=initPickupAutocomplete" async defer></script>
@endif
@endpush
