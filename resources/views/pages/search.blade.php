@extends('layouts.app')

@section('title', ($query ? 'Search: '.$query.' — ' : 'Search Tours — ').config('site.name'))

@section('content')
    <section class="pt-24 sm:pt-28 pb-16 sm:pb-20 bg-surface min-h-[60vh]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-ink">Search Tours</h1>
                <p class="text-sm text-ink-muted mt-1">Find packages, destinations & attractions</p>
            </div>

            <form action="{{ route('search') }}" method="GET" role="search"
                  class="bg-white p-3 sm:p-4 rounded-2xl shadow-soft border border-slate-100 mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <div class="relative flex-1 min-w-0">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                        <input type="search" name="q" value="{{ $query }}"
                               placeholder="Destination, tour, attraction..."
                               autocomplete="off"
                               class="w-full pl-10 pr-4 py-3 sm:py-3.5 bg-surface rounded-xl text-sm sm:text-base text-ink placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand/30 border border-transparent focus:border-brand/20">
                    </div>
                    <button type="submit"
                            class="btn-brand w-full sm:w-auto shrink-0 text-white font-bold text-sm px-6 py-3 sm:py-3.5 rounded-xl">
                        Search
                    </button>
                </div>
            </form>

            @if ($query === '')
                <div class="text-center py-10 sm:py-14 px-4">
                    <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-xl" aria-hidden="true"></i>
                    </div>
                    <p class="text-ink-muted text-sm sm:text-base">Enter a destination or tour to search.</p>
                </div>
            @else
                <p class="text-sm sm:text-base text-ink-muted mb-5 sm:mb-6 break-words">
                    Results for <strong class="text-ink">"{{ $query }}"</strong>
                </p>

                @if (count($packages) || count($categories))
                    @if (count($packages))
                        <h2 class="text-base sm:text-lg font-bold text-ink mb-3 sm:mb-4">
                            Tour Packages <span class="text-ink-muted font-semibold">({{ count($packages) }})</span>
                        </h2>
                        <div class="grid gap-3 sm:gap-4 mb-8 sm:mb-10">
                            @foreach ($packages as $package)
                                <a href="{{ $package['url'] }}"
                                   class="group block sm:flex sm:items-center gap-0 sm:gap-4 p-0 sm:p-4 bg-white rounded-2xl border border-slate-100 overflow-hidden hover:border-brand/30 hover:shadow-md transition-all active:scale-[0.99]">
                                    @if (! empty($package['image']))
                                        <div class="aspect-[16/9] sm:aspect-auto sm:w-24 sm:h-24 md:w-28 md:h-28 shrink-0 overflow-hidden bg-slate-100">
                                            <img src="{{ $package['image'] }}" alt="{{ $package['title'] }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                 loading="lazy">
                                        </div>
                                    @endif
                                    <div class="min-w-0 p-4 sm:p-0 flex flex-col justify-center">
                                        <p class="font-bold text-ink text-sm sm:text-base leading-snug line-clamp-2 sm:line-clamp-none sm:truncate">
                                            {{ $package['title'] }}
                                        </p>
                                        <p class="text-xs text-ink-muted mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                            <span>{{ $package['duration'] }}</span>
                                            <span class="text-slate-300 hidden sm:inline" aria-hidden="true">·</span>
                                            <span class="text-accent font-semibold">★ {{ $package['rating'] }}</span>
                                        </p>
                                        <x-tour-price :display="$package['price']" price-class="text-sm sm:text-base text-brand font-bold" class="mt-2" suffix="" />
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if (count($categories))
                        <h2 class="text-base sm:text-lg font-bold text-ink mb-3 sm:mb-4">
                            Destinations <span class="text-ink-muted font-semibold">({{ count($categories) }})</span>
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($categories as $category)
                                <a href="{{ $category['url'] }}"
                                   class="p-4 bg-white rounded-xl border border-slate-100 hover:border-brand/30 hover:shadow-sm font-semibold text-ink hover:text-brand transition-colors active:scale-[0.99]">
                                    <span class="block text-sm sm:text-base leading-snug">{{ $category['title'] }}</span>
                                    <span class="block text-xs text-ink-muted font-normal mt-1">{{ $category['city'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="text-center py-10 sm:py-14 px-4 bg-white rounded-2xl border border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map-marked-alt text-xl" aria-hidden="true"></i>
                        </div>
                        <p class="text-ink-muted text-sm sm:text-base mb-4">No tours found for "{{ $query }}". Try a different keyword.</p>
                        <a href="{{ route('tours.packages') }}"
                           class="inline-flex items-center justify-center btn-brand text-white font-bold text-sm px-6 py-3 rounded-xl">
                            Browse all packages
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
