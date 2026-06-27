<section class="py-14 sm:py-20 bg-surface-alt" aria-labelledby="reviews-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-8 sm:mb-12">
            <div class="text-center sm:text-left">
                <span class="text-brand text-xs font-bold uppercase tracking-widest">Testimonials</span>
                <h2 id="reviews-heading" class="text-2xl sm:text-3xl font-extrabold text-ink mt-2 mb-1">Travelers love our tours</h2>
                <p class="text-sm text-ink-muted">Real reviews from Agra, Delhi, Jaipur & Varanasi</p>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-soft shrink-0 mx-auto sm:mx-0">
                <div class="text-center sm:text-left">
                    <span class="text-3xl sm:text-4xl font-extrabold text-ink leading-none">4.9</span>
                    <div class="text-accent text-xs mt-1" aria-label="5 stars">
                        @for ($i = 0; $i < 5; $i++)<i class="fas fa-star" aria-hidden="true"></i>@endfor
                    </div>
                </div>
                <div class="h-10 w-px bg-slate-200" aria-hidden="true"></div>
                <div>
                    <p class="text-xs font-bold text-ink">Excellent</p>
                    <p class="text-[11px] text-ink-muted">2,260+ TripAdvisor reviews</p>
                </div>
            </div>
        </div>

        {{-- Mobile: horizontal scroll --}}
        <div class="mobile-scroll-x sm:hidden -mx-4 px-4 pb-2">
            @foreach ($reviews as $review)
                <article class="mobile-scroll-item w-[85vw] max-w-[340px] bg-white rounded-2xl p-5 border border-slate-100 shadow-soft flex flex-col relative">
                    <i class="fas fa-quote-left text-brand-100 text-3xl absolute top-4 right-4" aria-hidden="true"></i>
                    <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand bg-brand-50 px-2.5 py-1 rounded-full">
                            {{ $review['city'] }}
                        </span>
                        <div class="text-accent text-[10px]" aria-hidden="true">
                            @for ($i = 0; $i < ($review['rating'] ?? 5); $i++)<i class="fas fa-star"></i>@endfor
                        </div>
                    </div>
                    <p class="text-sm text-ink leading-relaxed flex-1 mb-5 relative z-10">"{{ $review['quote'] }}"</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100 relative z-10">
                        <img src="{{ $review['avatar'] }}" alt="{{ $review['name'] }}"
                             class="w-10 h-10 rounded-full object-cover ring-2 ring-brand-100" width="40" height="40" loading="lazy">
                        <div>
                            <p class="text-sm font-bold text-ink">{{ $review['name'] }}</p>
                            <p class="text-[11px] text-ink-muted">{{ $review['place'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <p class="sm:hidden text-center text-[10px] text-ink-muted mt-3 font-medium">← Swipe for more reviews →</p>

        {{-- Desktop: grid --}}
        <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($reviews as $review)
                <article class="card-hover bg-white rounded-2xl p-5 border border-slate-100 shadow-soft flex flex-col relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-brand to-brand-300 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300" aria-hidden="true"></div>
                    <i class="fas fa-quote-left text-brand-50 text-4xl absolute top-3 right-3 group-hover:text-brand-100 transition-colors" aria-hidden="true"></i>
                    <div class="flex items-center justify-between gap-2 mb-3 relative z-10">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand bg-brand-50 px-2 py-1 rounded">
                            {{ $review['city'] }} Tour
                        </span>
                        <div class="text-accent text-xs" aria-hidden="true">
                            @for ($i = 0; $i < ($review['rating'] ?? 5); $i++)<i class="fas fa-star"></i>@endfor
                        </div>
                    </div>
                    <p class="text-sm text-ink-muted leading-relaxed flex-1 mb-4 relative z-10">"{{ $review['quote'] }}"</p>
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100 relative z-10">
                        <img src="{{ $review['avatar'] }}" alt="{{ $review['name'] }}"
                             class="w-9 h-9 rounded-full object-cover ring-2 ring-brand-100" width="36" height="36" loading="lazy">
                        <div>
                            <p class="text-sm font-bold text-ink">{{ $review['name'] }}</p>
                            <p class="text-[11px] text-ink-muted">{{ $review['place'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
