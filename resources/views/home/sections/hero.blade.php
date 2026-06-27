<section class="relative min-h-[88vh] flex items-end lg:items-center overflow-hidden" aria-labelledby="hero-heading">
    {{-- Background --}}
    <div class="absolute inset-0 z-0">
        <x-site-image :src="$images['hero']['main']['url']" :alt="$images['hero']['main']['alt']"
                      width="1920" height="1080" :eager="true"
                      class="w-full h-full object-cover scale-105" />
        <div class="absolute inset-0 bg-gradient-to-r from-ink/95 via-ink/80 to-ink/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink/60 via-transparent to-transparent"></div>
    </div>

    {{-- Decorative orbs --}}
    <div class="absolute top-1/4 right-1/4 w-72 h-72 bg-brand/20 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute bottom-1/3 right-12 w-48 h-48 bg-accent/15 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-14 items-end lg:items-center">
            {{-- Copy --}}
            <div class="lg:col-span-7">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold px-4 py-2 rounded-full">
                        <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                        Agra · Delhi · Jaipur · Varanasi
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-amber-300 text-xs font-bold">
                        <i class="fas fa-star" aria-hidden="true"></i> 4.9 · 2,260+ reviews
                    </span>
                </div>

                <h1 id="hero-heading" class="text-4xl sm:text-5xl xl:text-[3.5rem] font-extrabold text-white tracking-tight leading-[1.08] mb-5">
                    Discover India’s heritage
                    <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-brand-100 via-emerald-300 to-teal-200">
                        with local experts
                    </span>
                </h1>

                <p class="text-slate-300 text-base sm:text-lg leading-relaxed mb-8 max-w-xl">
                    Private Taj Mahal sunrises, Old Delhi walks, Jaipur palaces & Varanasi Ganga aarti — curated by {{ config('site.name') }}.
                </p>

                {{-- Search --}}
                <form action="{{ route('search') }}" method="GET" role="search"
                      class="bg-white/95 backdrop-blur-sm p-2 sm:p-2.5 rounded-2xl shadow-2xl border border-white/50 mb-8 max-w-xl">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-1">
                            <i class="fas fa-search text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-sm" aria-hidden="true"></i>
                            <label for="hero-search" class="sr-only">Search tours</label>
                            <input type="search" id="hero-search" name="q" value="{{ request('q') }}"
                                   placeholder="Taj Mahal, Old Delhi, Jaipur..."
                                   class="w-full pl-11 pr-4 py-3.5 bg-surface rounded-xl text-sm text-ink border border-transparent focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                        </div>
                        <button type="submit" class="btn-accent text-white font-bold text-sm px-8 py-3.5 rounded-xl whitespace-nowrap">
                            Find Tours
                        </button>
                    </div>
                </form>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('tours.packages') }}" class="btn-accent inline-flex items-center gap-2 text-white font-bold px-8 py-4 rounded-full text-sm">
                        Browse Packages
                        <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                    <a href="tel:{{ config('site.phone') }}"
                       class="inline-flex items-center gap-3 text-white/90 hover:text-white font-semibold text-sm transition-colors">
                        <span class="w-11 h-11 bg-white/10 backdrop-blur border border-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone-alt text-xs" aria-hidden="true"></i>
                        </span>
                        {{ config('site.phone_display') }}
                    </a>
                </div>
            </div>

            {{-- Floating cards --}}
            <div class="lg:col-span-5 hidden lg:block relative h-[420px]">
                @php
                    $heroCards = [
                        ['key' => 'agra', 'label' => 'Agra', 'tag' => 'Most Booked', 'price' => '1,750', 'icon' => 'fa-monument'],
                        ['key' => 'delhi', 'label' => 'Delhi', 'tag' => 'Heritage Walk', 'price' => '1,450', 'icon' => 'fa-landmark'],
                        ['key' => 'jaipur', 'label' => 'Jaipur', 'tag' => 'Pink City', 'price' => '2,100', 'icon' => 'fa-fort-awesome'],
                    ];
                @endphp

                @foreach ($heroCards as $i => $card)
                    <a href="{{ route(collect($cities)->firstWhere('key', $card['key'])['route']) }}"
                       @class([
                           'hero-float-card absolute block w-52 rounded-2xl overflow-hidden shadow-2xl border-2 border-white/30 group',
                           'top-0 right-8 z-30' => $i === 0,
                           'top-28 right-44 z-20' => $i === 1,
                           'bottom-4 right-0 z-10' => $i === 2,
                       ])>
                        <div class="aspect-[4/5] bg-slate-800 relative">
                            <x-site-image :src="$images['hero'][$card['key']]['url']" :alt="$card['label']"
                                          width="400" height="500"
                                          class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            <div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/20 to-transparent"></div>
                            <div class="absolute top-3 left-3 w-8 h-8 bg-brand rounded-lg flex items-center justify-center text-white text-xs">
                                <i class="fas {{ $card['icon'] }}" aria-hidden="true"></i>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-accent">{{ $card['tag'] }}</span>
                                <p class="text-white font-extrabold text-lg leading-tight">{{ $card['label'] }}</p>
                                <p class="text-slate-300 text-xs mt-1">From ₹{{ $card['price'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach

                <div class="absolute bottom-16 left-0 bg-white rounded-2xl p-4 shadow-xl border border-slate-100 z-40 max-w-[200px]">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex -space-x-2">
                            @foreach (array_slice($images['avatars'], 0, 3) as $avatar)
                                <img src="{{ $avatar }}" alt="" class="w-7 h-7 rounded-full ring-2 ring-white object-cover" width="28" height="28" loading="lazy">
                            @endforeach
                        </div>
                        <span class="text-xs font-bold text-brand">10K+</span>
                    </div>
                    <p class="text-[11px] text-ink-muted leading-snug">Happy travelers across North India</p>
                </div>
            </div>
        </div>

        {{-- Mobile city quick links --}}
        <div class="flex flex-wrap gap-2 mt-10 lg:hidden">
            @foreach ($cities as $city)
                <a href="{{ route($city['route']) }}"
                   class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur border border-white/20 text-white text-xs font-bold px-3 py-2 rounded-full">
                    <i class="fas {{ $city['icon'] }} text-[10px] text-brand-100" aria-hidden="true"></i>
                    {{ $city['name'] }}
                </a>
            @endforeach
        </div>
    </div>
</section>
