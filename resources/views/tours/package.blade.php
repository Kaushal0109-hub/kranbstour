@extends('layouts.app')

@section('body_class', 'bg-white')

@section('title', $package['title'] . ' — ' . config('site.name'))
@section('meta_description', \Illuminate\Support\Str::limit($package['description'], 160))

@section('content')
@php
    $gallery = $package['gallery'] ?? [];
    $mainImage = $gallery[0] ?? ['src' => $package['image'], 'alt' => $package['title']];
    $thumbImages = array_slice($gallery, 1, 4);
    while (count($thumbImages) < 4) {
        $thumbImages[] = $mainImage;
    }
    $extraCount = max(0, count($gallery) - 5);
    $categoryRoute = \App\Services\TourCatalog::routeForSlug($categorySlug);
    $tagColors = ['bg-sky-50 text-sky-700', 'bg-emerald-50 text-emerald-700', 'bg-teal-50 text-teal-700', 'bg-violet-50 text-violet-700'];
@endphp

<div class="bg-white pt-2 pb-16 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="text-sm text-slate-500 mb-5 flex flex-wrap items-center gap-x-1.5 gap-y-1" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-brand transition-colors">Home</a>
            <span class="text-slate-400" aria-hidden="true">&gt;</span>
            <a href="{{ route('tours.packages') }}" class="hover:text-brand transition-colors">Tours</a>
            <span class="text-slate-400" aria-hidden="true">&gt;</span>
            <span class="text-slate-800 font-medium">{{ $package['title'] }}</span>
        </nav>

        {{-- Title block (reference layout) --}}
        <div class="relative mb-5">
            <div class="flex flex-wrap gap-2 mb-3 pr-16 sm:pr-20">
                @foreach ($package['location_tags'] as $i => $tag)
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $tagColors[$i % count($tagColors)] }}">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>

            <button type="button"
                    onclick="if(navigator.share){navigator.share({title:document.title,url:location.href})}"
                    class="absolute top-0 right-0 w-10 h-10 rounded-full border border-slate-200 text-slate-500 hover:text-brand hover:border-brand flex items-center justify-center transition-colors"
                    aria-label="Share">
                <i class="fas fa-share-alt text-sm" aria-hidden="true"></i>
            </button>

            <h1 class="text-2xl sm:text-3xl lg:text-[2.1rem] font-bold text-slate-900 leading-snug tracking-tight max-w-4xl pr-12">
                {{ $package['title'] }}
            </h1>

            <div class="flex flex-wrap items-center gap-2 mt-3 text-sm">
                <span class="inline-flex items-center gap-0.5" aria-label="Rating {{ $package['rating'] }} out of 5">
                    @for ($s = 1; $s <= 5; $s++)
                        <i class="fas fa-star text-amber-400 text-[13px]" aria-hidden="true"></i>
                    @endfor
                </span>
                <span class="font-bold text-slate-900">{{ $package['rating'] }}</span>
                <span class="text-slate-500">({{ $package['review_count'] }} reviews)</span>
            </div>
        </div>

        {{-- Gallery: main left + 2x2 thumbs right (reference) --}}
        <div class="pkg-gallery-ref mb-8">
            <div class="pkg-gallery-ref-main rounded-xl overflow-hidden bg-slate-100">
                <x-site-image :src="$mainImage['src']" :alt="$mainImage['alt']" width="960" height="640" :eager="true"
                              class="w-full h-full object-cover" />
            </div>
            <div class="pkg-gallery-ref-grid">
                @foreach ($thumbImages as $i => $thumb)
                    <div class="rounded-xl overflow-hidden bg-slate-100 relative min-h-0">
                        <x-site-image :src="$thumb['src']" :alt="$thumb['alt']" width="400" height="300"
                                      class="w-full h-full object-cover" />
                        @if ($i === 3 && $extraCount > 0)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white font-bold text-lg">
                                +{{ $extraCount }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Summary banner --}}
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 sm:p-5 mb-10 text-sm text-ink-muted leading-relaxed">
            {{ $package['summary'] }}
        </div>

        <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            {{-- Main content --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-10">

                {{-- About this tour --}}
                <section aria-labelledby="about-heading">
                    <h2 id="about-heading" class="text-xl font-extrabold text-ink mb-5">About this tour</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach ($package['features'] as $feature)
                            <div class="flex gap-3 p-4 rounded-xl border border-slate-100 bg-surface/50">
                                <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $feature['color'] }}">
                                    <i class="fas {{ $feature['icon'] }} text-sm" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-ink">{{ $feature['title'] }}</p>
                                    @if ($feature['desc'])
                                        <p class="text-xs text-ink-muted mt-0.5 leading-relaxed">{{ $feature['desc'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Highlighted reviews --}}
                <section aria-labelledby="reviews-heading">
                    <h2 id="reviews-heading" class="text-xl font-extrabold text-ink mb-5">Highlighted reviews from other travelers</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach ($highlightedReviews as $review)
                            <article class="bg-white border border-slate-100 rounded-2xl p-5 shadow-soft">
                                <div class="flex gap-1 mb-2">
                                    @for ($s = 1; $s <= $review['rating']; $s++)
                                        <i class="fas fa-star text-amber-400 text-xs" aria-hidden="true"></i>
                                    @endfor
                                </div>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="w-8 h-8 rounded-full bg-brand text-white text-xs font-bold flex items-center justify-center">{{ strtoupper(substr($review['name'], 0, 1)) }}</span>
                                    <div>
                                        <p class="text-sm font-bold text-ink">{{ $review['name'] }}</p>
                                        <p class="text-[10px] text-ink-muted">{{ $review['place'] }} · {{ $review['date'] }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-bold text-ink mb-1">{{ $review['title'] }}</p>
                                <p class="text-sm text-ink-muted leading-relaxed">{{ $review['text'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                {{-- Itinerary --}}
                <section aria-labelledby="itinerary-heading">
                    <h2 id="itinerary-heading" class="text-xl font-extrabold text-ink mb-6">Itinerary</h2>
                    <div class="space-y-0">
                        @foreach ($package['itinerary'] as $step)
                            <div class="pkg-itinerary-step flex gap-4 pb-8 last:pb-0">
                                <div class="pkg-itinerary-dot w-10 h-10 rounded-full bg-accent text-white flex items-center justify-center shrink-0 z-10">
                                    <i class="fas fa-map-marker-alt text-sm" aria-hidden="true"></i>
                                </div>
                                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-soft flex-1 -mt-1">
                                    <h3 class="font-bold text-ink text-sm mb-2">{{ $step['title'] }}</h3>
                                    <p class="text-sm text-ink-muted leading-relaxed">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Map --}}
                <section aria-labelledby="map-heading">
                    <h2 id="map-heading" class="text-xl font-extrabold text-ink mb-4">Where you'll be</h2>
                    <div class="relative rounded-2xl overflow-hidden border border-slate-100 aspect-[16/7] bg-slate-100">
                        <iframe title="Tour location map" class="w-full h-full border-0"
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                src="https://maps.google.com/maps?q={{ urlencode($package['map_query']) }}&z=12&output=embed"></iframe>
                    </div>
                    <p class="text-xs text-ink-muted mt-2">Exact pickup location provided after booking.</p>
                </section>

                {{-- Highlights --}}
                <section aria-labelledby="highlights-heading">
                    <h2 id="highlights-heading" class="text-xl font-extrabold text-ink mb-4">Highlights</h2>
                    <ul class="space-y-2.5">
                        @foreach ($package['highlights_list'] as $item)
                            <li class="flex gap-2.5 text-sm text-ink-muted">
                                <span class="text-brand font-bold mt-0.5">•</span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </section>

                {{-- Full description --}}
                <section aria-labelledby="desc-heading">
                    <h2 id="desc-heading" class="text-xl font-extrabold text-ink mb-4">Full description</h2>
                    <details class="group">
                        <summary class="list-none cursor-pointer marker:content-none">
                            <p class="text-sm text-ink-muted leading-relaxed">{{ \Illuminate\Support\Str::limit($package['full_description'], 280) }}</p>
                            <span class="inline-block text-accent font-bold text-sm mt-2 group-open:hidden">See more</span>
                        </summary>
                        <p class="text-sm text-ink-muted leading-relaxed mt-3">{{ $package['full_description'] }}</p>
                    </details>
                </section>

                {{-- Included / Excluded --}}
                <section aria-labelledby="included-heading">
                    <h2 id="included-heading" class="text-xl font-extrabold text-ink mb-5">What's included</h2>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-bold text-ink mb-3">What's included</h3>
                            <ul class="space-y-2">
                                @foreach ($package['inclusions'] as $item)
                                    <li class="flex gap-2 text-sm text-ink-muted">
                                        <i class="fas fa-check text-emerald-500 mt-0.5 shrink-0" aria-hidden="true"></i>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-ink mb-3">What's excluded</h3>
                            <ul class="space-y-2">
                                @foreach ($package['exclusions'] as $item)
                                    <li class="flex gap-2 text-sm text-ink-muted">
                                        <i class="fas fa-times text-red-500 mt-0.5 shrink-0" aria-hidden="true"></i>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- Important info --}}
                <section aria-labelledby="info-heading">
                    <h2 id="info-heading" class="text-xl font-extrabold text-ink mb-5">Important information</h2>
                    <div class="space-y-5">
                        @foreach ($package['important_info'] as $heading => $items)
                            <div>
                                <h3 class="text-sm font-bold text-ink mb-2">{{ $heading }}</h3>
                                <ul class="space-y-1.5">
                                    @foreach ($items as $item)
                                        <li class="text-sm text-ink-muted flex gap-2"><span>•</span>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- FAQ --}}
                <section aria-labelledby="faq-heading">
                    <h2 id="faq-heading" class="text-xl font-extrabold text-ink mb-5">Frequently asked questions</h2>
                    <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden bg-white">
                        @foreach ($package['faqs'] as $faq)
                            <details class="group pkg-faq-item">
                                <summary class="flex items-center justify-between gap-4 px-5 py-4 cursor-pointer list-none font-semibold text-sm text-ink hover:bg-surface/50">
                                    {{ $faq['q'] }}
                                    <span class="w-7 h-7 rounded-full bg-brand text-white flex items-center justify-center shrink-0 text-xs group-open:rotate-45 transition-transform">+</span>
                                </summary>
                                <div class="px-5 pb-4 text-sm text-ink-muted leading-relaxed">{{ $faq['a'] }}</div>
                            </details>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-5 xl:col-span-4">
                @include('tours.partials.package-sidebar')
            </aside>
        </div>

        {{-- Similar tours --}}
        @if ($relatedPackages)
            <section class="mt-16 pt-12 border-t border-slate-100" aria-labelledby="similar-heading">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
                    <div>
                        <h2 id="similar-heading" class="text-2xl font-extrabold text-ink">Similar tours</h2>
                        <p class="text-sm text-ink-muted mt-1">Explore more {{ $category['city'] }} adventures you might like</p>
                    </div>
                    <a href="{{ route($categoryRoute) }}" class="text-sm font-bold text-brand hover:text-brand-700">View all →</a>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($relatedPackages as $related)
                        <a href="{{ $related['url'] }}" class="card-hover group bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-soft flex flex-col">
                            <div class="relative aspect-[16/10] overflow-hidden bg-slate-200">
                                <x-site-image :src="$related['image']" :alt="$related['title']" width="500" height="312" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                <span class="absolute bottom-3 right-3 bg-white rounded-lg px-2 py-1 text-[10px] font-bold shadow flex items-center gap-1">
                                    <i class="fas fa-star text-amber-400" aria-hidden="true"></i>{{ $related['rating'] }}
                                    <span class="text-ink-muted font-normal">({{ \App\Services\TourCatalog::reviewCount($related['rating']) }})</span>
                                </span>
                            </div>
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-bold text-ink text-sm leading-snug mb-2 group-hover:text-brand transition-colors flex-1">{{ $related['title'] }}</h3>
                                <p class="text-xs text-ink-muted mb-3">{{ $related['duration'] }} · {{ $category['city'] }}</p>
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    <span class="text-[9px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded">Free Cancellation</span>
                                    <span class="text-[9px] font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded">Pickup Available</span>
                                </div>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-auto">
                                    <p class="text-lg font-extrabold text-ink">₹{{ $related['price'] }} <span class="text-xs font-normal text-ink-muted">/ person</span></p>
                                    <span class="text-xs font-bold text-brand">Book →</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .pkg-gallery-ref {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    @media (min-width: 768px) {
        .pkg-gallery-ref {
            flex-direction: row;
            height: 22rem;
            gap: 0.5rem;
        }
        .pkg-gallery-ref-main {
            flex: 0 0 58%;
            height: 100%;
        }
        .pkg-gallery-ref-grid {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 0.5rem;
            min-width: 0;
            height: 100%;
        }
    }
    @media (min-width: 1024px) {
        .pkg-gallery-ref { height: 26rem; }
    }
    @media (max-width: 767px) {
        .pkg-gallery-ref-main { aspect-ratio: 16 / 10; }
        .pkg-gallery-ref-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }
        .pkg-gallery-ref-grid > div { aspect-ratio: 4 / 3; }
    }
    .pkg-itinerary-step { position: relative; }
    .pkg-itinerary-step:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 1.25rem;
        top: 2.5rem;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    details.pkg-faq-item summary::-webkit-details-marker { display: none; }
</style>
@endpush
