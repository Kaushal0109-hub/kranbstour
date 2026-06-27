@php
    $gallery = $package['gallery'] ?? [];
    $mainImage = $gallery[0] ?? ['src' => $package['image'], 'alt' => $package['title']];
    $thumbs = array_slice($gallery, 1, 4);
    $extraCount = max(0, count($gallery) - 5);
    $categoryRoute = \App\Services\TourCatalog::routeForSlug($categorySlug);
@endphp

<div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-5 sm:p-6 lg:sticky lg:top-24">
    <div class="mb-5">
        <p class="text-xs text-ink-muted mb-1">From</p>
        <p class="text-3xl font-extrabold text-ink">₹{{ $package['price'] }}</p>
        <p class="text-xs text-ink-muted">per person · <span class="text-brand font-semibold">Lowest price guarantee</span></p>
    </div>

    <div class="space-y-3 mb-6">
        <a href="#book-tour" class="btn-accent w-full inline-flex justify-center items-center gap-2 text-white font-bold py-3.5 rounded-xl text-sm">
            Check availability
        </a>
        <a href="https://wa.me/{{ ltrim(config('site.phone'), '+') }}?text={{ urlencode('Hi, I want to book: '.$package['title']) }}"
           target="_blank" rel="noopener noreferrer"
           class="w-full inline-flex justify-center items-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold py-3.5 rounded-xl text-sm transition-colors">
            <i class="fab fa-whatsapp text-lg" aria-hidden="true"></i>
            Book on WhatsApp
        </a>
    </div>

    <div id="book-tour" class="border-t border-slate-100 pt-6 mb-6">
        @auth
            @if (auth()->user()->isCustomer())
                <h3 class="font-extrabold text-ink mb-4 text-sm">Request booking</h3>
                <form action="{{ route('dashboard.bookings.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="category_slug" value="{{ $categorySlug }}">
                    <input type="hidden" name="package_slug" value="{{ $package['slug'] }}">
                    <input type="date" name="travel_date" min="{{ date('Y-m-d') }}" placeholder="Travel date"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand">
                    <select name="travelers" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand">
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'traveler' : 'travelers' }}</option>
                        @endfor
                    </select>
                    <textarea name="notes" rows="2" placeholder="Pickup location, special requests..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-surface text-sm resize-y focus:outline-none focus:border-brand"></textarea>
                    <button type="submit" class="btn-brand w-full text-white font-bold py-3 rounded-xl text-sm">Submit Booking</button>
                </form>
            @endif
        @else
            <p class="text-xs text-ink-muted mb-3">Login to book online instantly.</p>
            <a href="{{ route('login') }}" class="btn-brand w-full inline-flex justify-center text-white font-bold py-3 rounded-xl text-sm mb-2">Login to Book</a>
            <a href="{{ route('register') }}" class="w-full inline-flex justify-center border border-brand text-brand font-bold py-3 rounded-xl text-sm hover:bg-brand-50">Create Account</a>
        @endauth
    </div>

    <div class="border-t border-slate-100 pt-5 space-y-4">
        <h4 class="font-extrabold text-ink text-sm">Book with confidence</h4>
        @foreach (['No-hassle best price guarantee', 'Customer care available 24/7', 'Hand-picked tours & activities', 'Free cancellation up to 24 hours'] as $item)
            <p class="flex items-start gap-2 text-xs text-ink-muted">
                <i class="fas fa-check-circle text-brand mt-0.5 shrink-0" aria-hidden="true"></i>
                {{ $item }}
            </p>
        @endforeach
    </div>

    <div class="border-t border-slate-100 pt-5 mt-5 space-y-3">
        <h4 class="font-extrabold text-ink text-sm">Need help?</h4>
        <a href="tel:{{ config('site.phone') }}" class="flex items-center gap-2 text-sm font-semibold text-ink hover:text-brand">
            <i class="fas fa-phone-alt text-brand" aria-hidden="true"></i>{{ config('site.phone_display') }}
        </a>
        <a href="mailto:{{ config('site.email') }}" class="flex items-center gap-2 text-sm font-semibold text-ink hover:text-brand">
            <i class="fas fa-envelope text-brand" aria-hidden="true"></i>{{ config('site.email') }}
        </a>
    </div>

    <p class="text-[10px] text-ink-muted text-center mt-5 pt-4 border-t border-slate-100">Secure booking · Instant confirmation</p>
</div>
