@php
    use App\Helpers\SiteHelper;
    use App\Services\TourCatalog;

    $bookUrl = TourCatalog::bookUrl($categorySlug, $package['slug']);
@endphp

<div class="pkg-sidebar-card bg-white rounded-2xl border border-slate-100 shadow-soft p-5 sm:p-6 lg:sticky lg:top-20 lg:z-30">
    <div class="mb-5 hidden lg:block">
        <p class="text-xs text-ink-muted mb-1">{{ \App\Helpers\CurrencyHelper::startingFromLabel() }}</p>
        <p class="text-3xl font-extrabold text-ink">{{ \App\Helpers\CurrencyHelper::formatAmount(null, $package['price']) }}</p>
        <p class="text-xs text-ink-muted">per person · <span class="text-brand font-semibold">Lowest price guarantee</span></p>
    </div>

    <div class="space-y-3 mb-6 hidden lg:block">
        <a href="{{ $bookUrl }}" class="btn-accent w-full inline-flex justify-center items-center gap-2 text-white font-bold py-3.5 rounded-xl text-sm">
            Check availability
        </a>
        <a href="{{ SiteHelper::whatsappHref('Hi, I want to book: '.$package['title']) }}"
           target="_blank" rel="noopener noreferrer"
           class="w-full inline-flex justify-center items-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold py-3.5 rounded-xl text-sm transition-colors">
            <i class="fab fa-whatsapp text-lg" aria-hidden="true"></i>
            Book on WhatsApp
        </a>
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
        @if (SiteHelper::phoneDisplay())
        <a href="{{ SiteHelper::telHref() }}" class="flex items-center gap-2 text-sm font-semibold text-ink hover:text-brand">
            <i class="fas fa-phone-alt text-brand" aria-hidden="true"></i>{{ SiteHelper::phoneDisplay() }}
        </a>
        @endif
        @if (SiteHelper::email())
        <a href="{{ SiteHelper::mailtoHref() }}" class="flex items-center gap-2 text-sm font-semibold text-ink hover:text-brand">
            <i class="fas fa-envelope text-brand" aria-hidden="true"></i>{{ SiteHelper::email() }}
        </a>
        @endif
    </div>

    <p class="text-[10px] text-ink-muted text-center mt-5 pt-4 border-t border-slate-100">Secure booking · Instant confirmation</p>
</div>
