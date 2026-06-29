@php
    use App\Helpers\CurrencyHelper;
    use App\Services\TourCatalog;

    $bookUrl = TourCatalog::bookUrl($categorySlug, $package['slug']);
@endphp

<div class="pkg-mobile-bar lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur border-t border-slate-200 shadow-[0_-8px_30px_rgba(15,23,42,0.08)]">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-[10px] text-ink-muted leading-tight">{{ CurrencyHelper::startingFromLabel() }}</p>
            <p class="text-xl font-extrabold text-ink leading-tight">{{ CurrencyHelper::formatAmount(null, $package['price']) }}</p>
            <p class="text-[10px] text-ink-muted leading-tight">per person</p>
        </div>
        <a href="{{ $bookUrl }}"
           class="btn-accent shrink-0 inline-flex items-center justify-center text-white font-bold text-sm px-5 py-3.5 rounded-xl whitespace-nowrap">
            Check availability
        </a>
    </div>
</div>
