@php
    $destCards = collect($cities)
        ->map(fn ($city) => ['type' => 'city', 'data' => $city])
        ->push(['type' => 'golden-triangle'])
        ->push(['type' => 'all-tours'])
        ->values();
@endphp

<section class="py-10 sm:py-16 bg-white sm:bg-surface" aria-labelledby="cities-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6 sm:mb-10">
            <div>
                <span class="hidden sm:inline-block text-brand text-xs font-bold uppercase tracking-widest">Explore India</span>
                <h2 id="cities-heading" class="text-[1.625rem] sm:text-3xl font-extrabold text-ink tracking-tight sm:mt-1.5 leading-[1.25] max-w-[16rem] sm:max-w-none">
                    Things to do wherever you're going
                </h2>
            </div>
            <a href="{{ route('tours.packages') }}"
               class="hidden sm:inline-flex items-center gap-1.5 text-sm font-bold text-brand hover:text-brand-700 shrink-0 transition-colors">
                View all tours
                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
        </div>

        {{-- Mobile: 2 cards visible, equal gap, scroll 2 at a time --}}
        <div class="cities-mobile-scroll sm:hidden -mx-4 px-4" role="region" aria-label="Destination tours carousel">
            @foreach ($destCards as $card)
                @include('home.sections.partials.dest-card-mobile', array_merge($card, ['images' => $images]))
            @endforeach
        </div>

        {{-- Tablet & desktop: rich overlay cards --}}
        <div class="hidden sm:grid sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 lg:gap-5">
            @foreach ($destCards as $card)
                @include('home.sections.partials.dest-card', array_merge($card, ['images' => $images]))
            @endforeach
        </div>
    </div>
</section>
