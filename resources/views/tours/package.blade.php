@extends('layouts.app')

@section('body_class', 'bg-white pkg-detail-page')

@section('title', $package['title'] . ' — ' . config('site.name'))
@section('meta_description', \Illuminate\Support\Str::limit($package['description'], 160))

@section('content')
@php
    use App\Helpers\MediaHelper;

    $gallery = $package['gallery'] ?? [];
    $galleryItems = ! empty($gallery)
        ? $gallery
        : [['src' => $package['image'], 'alt' => $package['title']]];
    $lightboxImages = collect($galleryItems)->map(fn ($item) => [
        'url' => MediaHelper::url($item['src'] ?? $package['image']),
        'alt' => $item['alt'] ?? $package['title'],
    ])->values()->all();
    $mainImage = $galleryItems[0];
    $displayThumbs = array_slice($galleryItems, 1, 4);
    while (count($displayThumbs) < 4) {
        $displayThumbs[] = $galleryItems[0];
    }
    $extraCount = max(0, count($galleryItems) - 5);
    $categoryRoute = \App\Services\TourCatalog::routeForSlug($categorySlug);
    $tagColors = ['bg-sky-50 text-sky-700', 'bg-emerald-50 text-emerald-700', 'bg-teal-50 text-teal-700', 'bg-violet-50 text-violet-700'];
@endphp

<div class="bg-white pt-2 pb-28 lg:pb-16 min-h-screen">
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

        {{-- Gallery: main left + 2x2 thumbs right — click opens lightbox slider --}}
        <div class="pkg-gallery-ref mb-8" data-pkg-gallery>
            <button type="button"
                    class="pkg-gallery-ref-main pkg-gallery-trigger rounded-xl overflow-hidden bg-slate-100 relative group cursor-zoom-in"
                    data-lightbox-index="0"
                    aria-label="View gallery image 1">
                <x-site-image :src="$mainImage['src']" :alt="$mainImage['alt']" width="960" height="640" :eager="true"
                              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.02]" />
                <span class="absolute bottom-3 right-3 bg-black/55 text-white text-xs font-semibold px-2.5 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-expand mr-1" aria-hidden="true"></i> View
                </span>
            </button>
            <div class="pkg-gallery-ref-grid">
                @foreach ($displayThumbs as $i => $thumb)
                    @php $thumbIndex = min($i + 1, count($galleryItems) - 1); @endphp
                    <button type="button"
                            class="pkg-gallery-trigger rounded-xl overflow-hidden bg-slate-100 relative min-h-0 group cursor-zoom-in"
                            data-lightbox-index="{{ $thumbIndex }}"
                            aria-label="View gallery image {{ $thumbIndex + 1 }}">
                        <x-site-image :src="$thumb['src']" :alt="$thumb['alt']" width="400" height="300"
                                      class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        @if ($i === 3 && $extraCount > 0)
                            <span class="absolute inset-0 bg-black/50 flex items-center justify-center text-white font-bold text-lg pointer-events-none">
                                +{{ $extraCount }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Gallery lightbox (full-screen slider) --}}
        <div id="pkg-lightbox"
             class="pkg-lightbox hidden fixed inset-0 z-[120]"
             role="dialog"
             aria-modal="true"
             aria-label="Tour photo gallery"
             data-tour-title="{{ $package['title'] }}"
             hidden>
            <div class="pkg-lightbox-backdrop absolute inset-0 bg-black/95"></div>

            <header class="pkg-lightbox-header absolute top-0 inset-x-0 z-30 flex items-center justify-between gap-4 px-4 sm:px-6 py-4 bg-gradient-to-b from-black/80 to-transparent">
                <p class="pkg-lightbox-title text-white text-sm sm:text-base font-semibold truncate pr-4">
                    {{ $package['title'] }}
                    <span class="pkg-lightbox-counter text-white/70 font-normal"> — Image 1 of {{ count($lightboxImages) }}</span>
                </p>
                <div class="flex items-center gap-1 shrink-0">
                    <button type="button" class="pkg-lightbox-zoom-in w-10 h-10 rounded-lg text-white/90 hover:bg-white/10 flex items-center justify-center" aria-label="Zoom in">
                        <i class="fas fa-search-plus" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="pkg-lightbox-zoom-out w-10 h-10 rounded-lg text-white/90 hover:bg-white/10 flex items-center justify-center" aria-label="Zoom out">
                        <i class="fas fa-search-minus" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="pkg-lightbox-close w-10 h-10 rounded-lg text-white/90 hover:bg-white/10 flex items-center justify-center" aria-label="Close gallery">
                        <i class="fas fa-times text-lg" aria-hidden="true"></i>
                    </button>
                </div>
            </header>

            <button type="button"
                    class="pkg-lightbox-prev absolute left-2 sm:left-5 top-1/2 -translate-y-1/2 z-30 w-12 h-12 sm:w-14 sm:h-14 text-white/90 hover:text-white flex items-center justify-center transition-transform hover:scale-110"
                    aria-label="Previous image">
                <i class="fas fa-chevron-left text-2xl sm:text-3xl" aria-hidden="true"></i>
            </button>

            <button type="button"
                    class="pkg-lightbox-next absolute right-2 sm:right-5 top-1/2 -translate-y-1/2 z-30 w-12 h-12 sm:w-14 sm:h-14 text-white/90 hover:text-white flex items-center justify-center transition-transform hover:scale-110"
                    aria-label="Next image">
                <i class="fas fa-chevron-right text-2xl sm:text-3xl" aria-hidden="true"></i>
            </button>

            <div class="pkg-lightbox-stage absolute inset-0 flex items-center justify-center px-12 sm:px-20 py-20 z-20">
                <img src=""
                     alt=""
                     class="pkg-lightbox-image max-w-full max-h-full object-contain select-none transition-opacity duration-200"
                     draggable="false">
            </div>
        </div>

        <script type="application/json" id="pkg-lightbox-data">@json($lightboxImages)</script>

        {{-- Summary banner --}}
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 sm:p-5 mb-10 text-sm text-ink-muted leading-relaxed">
            {{ $package['summary'] }}
        </div>

        <div class="pkg-detail-layout flex flex-col lg:flex-row lg:items-stretch gap-8 lg:gap-10">
            {{-- Main content --}}
            <div class="pkg-detail-main flex-1 min-w-0 space-y-10">

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

                {{-- Similar tours (inside main column so sidebar stays sticky alongside) --}}
                @if ($relatedPackages)
                    <section class="pt-4 border-t border-slate-100" aria-labelledby="similar-heading">
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
                            <div>
                                <h2 id="similar-heading" class="text-2xl font-extrabold text-ink">Similar tours</h2>
                                <p class="text-sm text-ink-muted mt-1">Explore more {{ $category['city'] }} adventures you might like</p>
                            </div>
                            <a href="{{ route($categoryRoute) }}" class="text-sm font-bold text-brand hover:text-brand-700">View all →</a>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-5">
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
                                            <x-tour-price :display="$related['price']" price-class="text-lg" suffix="/ person" />
                                            <span class="text-xs font-bold text-brand">Book →</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            {{-- Sidebar (desktop sticky) --}}
            <aside class="pkg-detail-aside w-full lg:w-80 xl:w-[22rem] shrink-0">
                @include('tours.partials.package-sidebar')
            </aside>
        </div>

        @include('tours.partials.package-mobile-bar')
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
        .pkg-gallery-ref-grid > button { aspect-ratio: 4 / 3; }
    }
    .pkg-gallery-trigger {
        display: block;
        width: 100%;
        border: 0;
        padding: 0;
        text-align: left;
    }
    .pkg-gallery-ref-main.pkg-gallery-trigger {
        height: 100%;
    }
    .pkg-lightbox:not(.hidden) {
        display: block;
    }
    .pkg-lightbox-image {
        transform-origin: center center;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }
    .pkg-lightbox-image.is-changing {
        opacity: 0;
    }
    body.pkg-lightbox-open {
        overflow: hidden;
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

    @media (min-width: 1024px) {
        .pkg-detail-layout {
            align-items: stretch;
        }

        .pkg-detail-aside {
            align-self: stretch;
        }

        .pkg-sidebar-card {
            position: sticky;
            top: 5rem;
            z-index: 30;
            width: 100%;
        }
    }

    @media (max-width: 1023px) {
        .pkg-detail-page a[aria-label="WhatsApp"] {
            bottom: 5.75rem;
        }
        .pkg-detail-page button[aria-label="Ask AI"] {
            bottom: 5.75rem;
        }
        .pkg-mobile-bar {
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const dataEl = document.getElementById('pkg-lightbox-data');
    const lightbox = document.getElementById('pkg-lightbox');
    if (!dataEl || !lightbox) return;

    const images = JSON.parse(dataEl.textContent || '[]');
    if (!images.length) return;

    const tourTitle = lightbox.dataset.tourTitle || '';
    const imgEl = lightbox.querySelector('.pkg-lightbox-image');
    const counterEl = lightbox.querySelector('.pkg-lightbox-counter');
    const btnPrev = lightbox.querySelector('.pkg-lightbox-prev');
    const btnNext = lightbox.querySelector('.pkg-lightbox-next');
    const btnClose = lightbox.querySelector('.pkg-lightbox-close');
    const btnZoomIn = lightbox.querySelector('.pkg-lightbox-zoom-in');
    const btnZoomOut = lightbox.querySelector('.pkg-lightbox-zoom-out');
    const backdrop = lightbox.querySelector('.pkg-lightbox-backdrop');
    let current = 0;
    let zoom = 1;
    let touchStartX = 0;

    function updateCounter() {
        counterEl.textContent = ' — Image ' + (current + 1) + ' of ' + images.length;
    }

    function setZoom(value) {
        zoom = Math.min(2.5, Math.max(1, value));
        imgEl.style.transform = 'scale(' + zoom + ')';
        btnZoomOut.disabled = zoom <= 1;
        btnZoomIn.disabled = zoom >= 2.5;
    }

    function render(index, animate) {
        const nextIndex = (index + images.length) % images.length;
        const item = images[nextIndex];

        function applyImage() {
            current = nextIndex;
            imgEl.src = item.url;
            imgEl.alt = item.alt || tourTitle;
            updateCounter();
            setZoom(1);
            imgEl.classList.remove('is-changing');
        }

        if (animate) {
            imgEl.classList.add('is-changing');
            setTimeout(applyImage, 180);
        } else {
            applyImage();
        }

        const showNav = images.length > 1;
        btnPrev.style.visibility = showNav ? 'visible' : 'hidden';
        btnNext.style.visibility = showNav ? 'visible' : 'hidden';
    }

    function open(index) {
        render(index, false);
        lightbox.hidden = false;
        lightbox.classList.remove('hidden');
        document.body.classList.add('pkg-lightbox-open');
    }

    function close() {
        lightbox.hidden = true;
        lightbox.classList.add('hidden');
        document.body.classList.remove('pkg-lightbox-open');
        imgEl.removeAttribute('src');
        setZoom(1);
    }

    function goNext() {
        if (images.length < 2) return;
        render(current + 1, true);
    }

    function goPrev() {
        if (images.length < 2) return;
        render(current - 1, true);
    }

    document.querySelectorAll('.pkg-gallery-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            open(parseInt(btn.dataset.lightboxIndex || '0', 10));
        });
    });

    btnClose?.addEventListener('click', close);
    btnPrev?.addEventListener('click', function (e) { e.stopPropagation(); goPrev(); });
    btnNext?.addEventListener('click', function (e) { e.stopPropagation(); goNext(); });
    btnZoomIn?.addEventListener('click', function () { setZoom(zoom + 0.25); });
    btnZoomOut?.addEventListener('click', function () { setZoom(zoom - 0.25); });
    backdrop?.addEventListener('click', close);

    document.addEventListener('keydown', function (e) {
        if (lightbox.hidden) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') goPrev();
        if (e.key === 'ArrowRight') goNext();
    });

    lightbox.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    lightbox.addEventListener('touchend', function (e) {
        const diff = e.changedTouches[0].screenX - touchStartX;
        if (Math.abs(diff) < 50) return;
        if (diff > 0) goPrev();
        else goNext();
    }, { passive: true });
})();
</script>
@endpush
