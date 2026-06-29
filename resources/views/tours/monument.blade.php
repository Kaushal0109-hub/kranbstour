@extends('layouts.app')

@section('title', $monument['name'] . ' — ' . $category['city'] . ' | ' . config('site.name'))
@section('meta_description', \Illuminate\Support\Str::limit($monument['desc'] ?? $monument['description'] ?? '', 160))

@section('content')
@php
    use App\Services\TourCatalog;

    $categoryRoute = TourCatalog::routeForSlug($categorySlug);
@endphp

<div class="bg-white min-h-screen pb-16">
    {{-- Hero --}}
    <section class="relative pt-24 pb-12 sm:pb-16 bg-ink overflow-hidden">
        <div class="absolute inset-0 opacity-40">
            <x-site-image :src="$monument['image']" :alt="$monument['name']" class="w-full h-full object-cover" />
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/70 to-ink/30"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm text-slate-300 mb-6 flex flex-wrap items-center gap-x-1.5 gap-y-1" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span aria-hidden="true">›</span>
                <a href="{{ route('tours.packages') }}" class="hover:text-white transition-colors">Tours</a>
                <span aria-hidden="true">›</span>
                <a href="{{ route($categoryRoute) }}" class="hover:text-white transition-colors">{{ $category['city'] }}</a>
                <span aria-hidden="true">›</span>
                <span class="text-white font-medium">{{ $monument['name'] }}</span>
            </nav>
            <span class="inline-block text-brand-100 text-xs font-bold uppercase tracking-widest mb-3">Monument</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight max-w-3xl">{{ $monument['name'] }}</h1>
            <p class="text-slate-300 text-sm sm:text-base mt-4 max-w-2xl leading-relaxed">
                {{ $monument['desc'] ?? $monument['description'] ?? '' }}
            </p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="grid lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 space-y-8">
                <div class="rounded-2xl overflow-hidden border border-slate-100 shadow-soft">
                    <x-site-image :src="$monument['image']" :alt="$monument['name']" class="w-full aspect-[16/10] object-cover" />
                </div>

                @if (! empty($monument['description']) || ! empty($monument['desc']))
                    <div class="prose prose-slate max-w-none">
                        <h2 class="text-xl font-extrabold text-ink mb-3">About this attraction</h2>
                        <p class="text-ink-muted leading-relaxed">{{ $monument['description'] ?? $monument['desc'] }}</p>
                    </div>
                @endif
            </div>

            <aside class="lg:col-span-1 space-y-6">
                <div class="bg-surface rounded-2xl border border-slate-100 p-5">
                    <h2 class="font-extrabold text-ink text-sm mb-4">Explore {{ $category['city'] }}</h2>
                    <a href="{{ route($categoryRoute) }}"
                       class="block w-full text-center btn-brand text-white font-bold py-3 rounded-xl text-sm mb-3">
                        View all {{ $category['city'] }} tours
                    </a>
                    <a href="{{ route('tours.packages') }}"
                       class="block w-full text-center border border-brand text-brand font-bold py-3 rounded-xl text-sm hover:bg-brand-50">
                        Browse all packages
                    </a>
                </div>

                @if (! empty($relatedTours))
                    <div class="bg-white rounded-2xl border border-slate-100 p-5">
                        <h2 class="font-extrabold text-ink text-sm mb-4">Popular tours nearby</h2>
                        <ul class="space-y-3">
                            @foreach ($relatedTours as $tour)
                                <li>
                                    <a href="{{ $tour['url'] }}" class="block group">
                                        <p class="font-bold text-sm text-ink group-hover:text-brand leading-snug">{{ $tour['title'] }}</p>
                                        <p class="text-xs text-ink-muted mt-0.5">{{ $tour['duration'] }} · <x-tour-price :display="$tour['price']" price-class="inline text-brand font-semibold" :show-label="false" suffix="" /></p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>
@endsection
