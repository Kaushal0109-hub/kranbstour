<section class="py-14 bg-surface-alt" aria-labelledby="more-dest-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h2 id="more-dest-heading" class="text-xl sm:text-2xl font-extrabold text-ink tracking-tight">
                    Also explore Jaipur & Varanasi
                </h2>
                <p class="text-ink-muted text-sm mt-1">Royal Rajasthan & spiritual Kashi — add to your North India trip</p>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
            @foreach ($secondaryCities as $city)
                <a href="{{ route($city['route']) }}"
                   class="card-hover group flex flex-col sm:flex-row bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-soft">
                    <div class="sm:w-2/5 h-44 sm:h-auto shrink-0 overflow-hidden bg-slate-200">
                        <x-site-image :src="$city['image']['url']" :alt="$city['name']" width="500" height="350"
                                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    </div>
                    <div class="flex-1 p-5 sm:p-6 flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-8 h-8 bg-brand-50 text-brand rounded-lg flex items-center justify-center text-sm">
                                <i class="fas {{ $city['icon'] }}" aria-hidden="true"></i>
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-ink-muted">{{ $city['tour_count'] }}</span>
                        </div>
                        <h3 class="text-lg font-extrabold text-ink group-hover:text-brand transition-colors">{{ $city['name'] }}</h3>
                        <p class="text-xs text-brand font-semibold mb-2">{{ $city['tagline'] }}</p>
                        <p class="text-sm text-ink-muted leading-relaxed line-clamp-2 mb-3">{{ $city['description'] }}</p>
                        <ul class="flex flex-wrap gap-1.5">
                            @foreach (array_slice($city['highlights'], 0, 2) as $h)
                                <li class="text-[10px] font-semibold text-brand bg-brand-50 px-2 py-0.5 rounded">{{ $h }}</li>
                            @endforeach
                        </ul>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
