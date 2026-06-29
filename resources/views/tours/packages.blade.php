@extends('layouts.app')

@php
    use App\Services\TourCatalog;
@endphp

@section('title', 'Tour Packages — ' . config('site.name'))
@section('meta_description', 'Browse all tour packages for Agra, Delhi, Jaipur, Varanasi & Golden Triangle with ' . config('site.name') . '.')

@section('content')
    {{-- Hero --}}
    <section class="relative pt-28 pb-14 sm:pb-16 bg-gradient-to-br from-brand-600 via-brand-700 to-ink overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: url(htt)"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block bg-white/10 text-brand-100 text-xs font-bold px-4 py-1.5 rounded-full border border-white/20 mb-4">
                All Destinations
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4">
                Tour Packages across North India
            </h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                Agra, Delhi, Jaipur, Varanasi & Golden Triangle — private tours with expert local guides.
            </p>
        </div>
    </section>

    {{-- City tabs / quick nav --}}
    <section class="sticky top-20 z-40 bg-white/95 backdrop-blur border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 overflow-x-auto md:hidden">
            <div class="flex gap-2 min-w-max">
                @foreach ($categories as $cat)
                    @php
                        $catRoute = match ($cat['slug']) {
                            'taj-mahal' => 'tours.taj-mahal',
                            'golden-triangle' => 'tours.golden-triangle',
                            default => 'tours.' . $cat['slug'],
                        };
                    @endphp
                    <a href="{{ route($catRoute) }}"
                       class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2 rounded-full border border-slate-200 text-ink-muted hover:border-brand hover:text-brand hover:bg-brand-50 transition-colors whitespace-nowrap">
                        <i class="fas {{ $cat['icon'] }} text-[10px]" aria-hidden="true"></i>
                        {{ $cat['city'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- All packages by city --}}
    @foreach ($categories as $cat)
        @php
            $catRoute = match ($cat['slug']) {
                'taj-mahal' => 'tours.taj-mahal',
                'golden-triangle' => 'tours.golden-triangle',
                default => 'tours.' . $cat['slug'],
            };
        @endphp
        <section class="py-12 sm:py-14 {{ $loop->even ? 'bg-surface' : 'bg-white' }}" aria-labelledby="cat-{{ $cat['slug'] }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end gap-4 mb-8">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden shrink-0 shadow-md ring-1 ring-slate-100 hidden sm:block">
                            <x-site-image :src="$cat['card']" :alt="$cat['city']" width="200" height="200" class="w-full h-full object-cover" />
                        </div>
                        <div>
                            <span class="text-brand text-xs font-bold uppercase tracking-widest">{{ $cat['tagline'] }}</span>
                            <h2 id="cat-{{ $cat['slug'] }}" class="text-2xl sm:text-3xl font-extrabold text-ink mt-1">{{ $cat['heading'] }}</h2>
                            <p class="text-sm text-ink-muted mt-2 max-w-xl">{{ $cat['description'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route($catRoute) }}" class="text-sm font-bold text-brand hover:text-brand-700 shrink-0">
                        All {{ $cat['city'] }} tours →
                    </a>
                </div>

                {{-- Monuments strip --}}
                <div class="flex gap-3 overflow-x-auto pb-2 mb-8 -mx-1 px-1">
                    @foreach ($cat['monuments'] as $m)
                        @php $monumentUrl = $m['url'] ?? TourCatalog::monumentUrl($cat['slug'], $m); @endphp
                        <a href="{{ $monumentUrl }}" class="shrink-0 w-36 sm:w-44 rounded-xl overflow-hidden border border-slate-100 bg-white shadow-sm hover:border-brand/30 hover:shadow-md transition-all block group">
                            <div class="aspect-[4/3] overflow-hidden">
                                <x-site-image :src="$m['image']" :alt="$m['name']" width="200" height="150" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            </div>
                            <p class="text-[11px] font-bold text-ink p-2 leading-tight group-hover:text-brand">{{ $m['name'] }}</p>
                        </a>
                    @endforeach
                </div>

                {{-- Tours grid --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach (array_slice($cat['tours'], 0, 3) as $package)
                        @php $packageUrl = $package['url'] ?? TourCatalog::packageUrl($cat['slug'], $package); @endphp
                        <article class="card-hover bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-soft flex flex-col">
                            <a href="{{ $packageUrl }}" class="relative aspect-[16/10] overflow-hidden bg-slate-200 block group">
                                <x-site-image :src="$package['image']" :alt="$package['title']" width="500" height="312"
                                              class="w-full h-full object-cover" />
                                @if ($package['tag'])
                                    <span class="absolute top-3 left-3 bg-accent text-white text-[10px] font-bold uppercase px-2 py-0.5 rounded">{{ $package['tag'] }}</span>
                                @endif
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <div class="flex items-center justify-between text-xs text-ink-muted mb-2">
                                    <span>{{ $package['duration'] }}</span>
                                    <span class="text-accent font-bold"><i class="fas fa-star text-[9px]"></i> {{ $package['rating'] }}</span>
                                </div>
                                <h3 class="font-bold text-ink text-sm leading-snug mb-3 flex-1">
                                    <a href="{{ $packageUrl }}" class="hover:text-brand">{{ $package['title'] }}</a>
                                </h3>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                    <x-tour-price :display="$package['price']" price-class="text-lg text-brand" :show-label="true" suffix="" />
                                    <a href="{{ $packageUrl }}" class="text-xs font-bold text-brand hover:text-brand-700">View Details →</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    @include('tours.partials.cta', ['tour' => ['city' => 'North India']])
@endsection
