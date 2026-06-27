<section class="py-16 bg-white" aria-labelledby="spotlight-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-brand text-xs font-bold uppercase tracking-widest">Delhi & Agra</span>
            <h2 id="spotlight-heading" class="text-2xl sm:text-3xl font-extrabold text-ink mt-2 tracking-tight">
                Where most travelers start
            </h2>
            <p class="text-ink-muted text-sm mt-2">Capital heritage meets the Taj — our two most booked destinations</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 lg:gap-8">
            @foreach ($spotlightCities as $spot)
                <div class="rounded-3xl overflow-hidden border border-slate-100 shadow-soft bg-surface flex flex-col">
                    {{-- City header --}}
                    <div class="relative h-48 sm:h-56 overflow-hidden">
                        <x-site-image :src="$spot['banner']['url']" :alt="$spot['name']" width="800" height="400"
                                      class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-r from-ink/85 to-ink/30"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="w-11 h-11 bg-brand rounded-xl flex items-center justify-center text-white text-lg shadow-lg">
                                    <i class="fas {{ $spot['icon'] }}" aria-hidden="true"></i>
                                </span>
                                <div>
                                    <p class="text-brand-100 text-xs font-semibold uppercase tracking-wider">{{ $spot['tagline'] }}</p>
                                    <h3 class="text-2xl font-extrabold text-white">{{ $spot['name'] }} Tours</h3>
                                </div>
                            </div>
                            <p class="text-slate-300 text-sm leading-relaxed max-w-md">{{ $spot['description'] }}</p>
                        </div>
                    </div>

                    {{-- Quick tour picks --}}
                    <div class="p-5 sm:p-6 space-y-3 flex-1">
                        @foreach ($spot['tours'] as $tour)
                            <a href="{{ $tour['package_url'] }}"
                               class="flex items-center gap-4 p-3 rounded-xl bg-white border border-slate-100 hover:border-brand/30 hover:shadow-md transition-all group">
                                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 bg-slate-200">
                                    <x-site-image :src="$spot['image']['url']" :alt="$tour['title']" width="128" height="128"
                                                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        @if ($tour['tag'])
                                            <span class="text-[9px] font-bold uppercase text-accent">{{ $tour['tag'] }}</span>
                                        @endif
                                        <span class="text-[10px] text-ink-muted">{{ $tour['duration'] }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-ink truncate group-hover:text-brand transition-colors">{{ $tour['title'] }}</p>
                                    <p class="text-sm font-extrabold text-brand mt-1">₹{{ $tour['price'] }} <span class="text-[10px] font-normal text-ink-muted">/person</span></p>
                                </div>
                                <i class="fas fa-chevron-right text-slate-300 group-hover:text-brand text-xs shrink-0" aria-hidden="true"></i>
                            </a>
                        @endforeach

                        <a href="{{ route($spot['route']) }}"
                           class="inline-flex items-center gap-2 text-sm font-bold text-brand hover:text-brand-700 pt-2 transition-colors">
                            All {{ $spot['name'] }} tours
                            <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
