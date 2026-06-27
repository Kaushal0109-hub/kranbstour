@if ($type === 'city')
    @php
        $city = $data;
        $label = $city['key'] === 'delhi' ? 'New Delhi' : $city['name'];
    @endphp
    <a href="{{ route($city['route']) }}" class="dest-pill group block w-full min-w-0">
        <div class="dest-pill-image relative aspect-[3/4] sm:aspect-[4/5] rounded-2xl overflow-hidden bg-slate-200 shadow-md ring-1 ring-black/5">
            <x-site-image :src="$city['image']['url']" :alt="$city['image']['alt']" width="480" height="600"
                          class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
            <div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/25 to-transparent opacity-90 group-hover:opacity-100 transition-opacity"></div>

            <span class="absolute top-3 left-3 text-[10px] font-bold uppercase tracking-wide text-brand bg-white/95 px-2 py-1 rounded-md shadow-sm">
                {{ $city['tour_count'] }}
            </span>

            <div class="absolute bottom-0 left-0 right-0 p-3.5 sm:p-4">
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-brand-100 mb-0.5">{{ $city['tagline'] }}</p>
                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight group-hover:text-brand-100 transition-colors">{{ $label }}</h3>
            </div>
        </div>
    </a>
@elseif ($type === 'golden-triangle')
    <a href="{{ route('tours.golden-triangle') }}" class="dest-pill group block w-full min-w-0">
        <div class="dest-pill-image relative aspect-[3/4] sm:aspect-[4/5] rounded-2xl overflow-hidden shadow-md ring-1 ring-black/5 grid grid-cols-2 grid-rows-2 gap-0.5 bg-slate-200">
            <x-site-image :src="$images['cities']['agra']['card']" alt="Agra" width="240" height="300"
                          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <x-site-image :src="$images['cities']['delhi']['card']" alt="Delhi" width="240" height="300"
                          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <x-site-image :src="$images['cities']['jaipur']['card']" alt="Jaipur" width="240" height="300"
                          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="bg-gradient-to-br from-brand-500 to-brand-700 flex flex-col items-center justify-center text-white">
                <i class="fas fa-route text-lg mb-1" aria-hidden="true"></i>
                <span class="text-[10px] font-bold uppercase tracking-wide">3 Cities</span>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-transparent to-transparent pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 right-0 p-3.5 sm:p-4 pointer-events-none">
                <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-brand-100 mb-0.5">Combo Package</p>
                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight">Golden Triangle</h3>
            </div>
        </div>
    </a>
@elseif ($type === 'all-tours')
    <a href="{{ route('tours.packages') }}" class="dest-pill group block w-full min-w-0">
        <div class="dest-pill-image relative aspect-[3/4] sm:aspect-[4/5] rounded-2xl overflow-hidden shadow-md ring-1 ring-brand-200 bg-gradient-to-br from-brand-500 via-brand-600 to-brand-700 flex flex-col items-center justify-center text-white group-hover:from-brand-600 group-hover:to-brand-800 transition-all">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 30% 30%, white 0%, transparent 50%);"></div>
            <span class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-white/15 backdrop-blur-sm flex items-center justify-center mb-3 ring-2 ring-white/20 group-hover:scale-110 transition-transform">
                <i class="fas fa-compass text-2xl" aria-hidden="true"></i>
            </span>
            <span class="relative text-sm font-extrabold">80+ Tours</span>
            <span class="relative text-[10px] text-white/75 mt-1">All destinations</span>
            <div class="absolute bottom-0 left-0 right-0 p-3.5 sm:p-4 text-center">
                <h3 class="text-base sm:text-lg font-extrabold text-white leading-tight">All Tours</h3>
            </div>
        </div>
    </a>
@endif
