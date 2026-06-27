@if ($type === 'city')
    @php
        $city = $data;
        $label = $city['key'] === 'delhi' ? 'New Delhi' : $city['name'];
    @endphp
    <a href="{{ route($city['route']) }}" class="dest-mobile-card group block min-w-0">
        <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 ring-1 ring-black/[0.04]">
            <x-site-image :src="$city['image']['url']" :alt="$city['image']['alt']" width="400" height="400"
                          class="w-full h-full object-cover group-active:scale-[0.98] transition-transform duration-300" />
        </div>
        <h3 class="mt-3 text-base font-extrabold text-ink leading-tight">{{ $label }}</h3>
    </a>
@elseif ($type === 'golden-triangle')
    <a href="{{ route('tours.golden-triangle') }}" class="dest-mobile-card group block min-w-0">
        <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 ring-1 ring-black/[0.04] grid grid-cols-2 grid-rows-2 gap-px">
            <x-site-image :src="$images['cities']['agra']['card']" alt="Agra" width="200" height="200"
                          class="w-full h-full object-cover" />
            <x-site-image :src="$images['cities']['delhi']['card']" alt="Delhi" width="200" height="200"
                          class="w-full h-full object-cover" />
            <x-site-image :src="$images['cities']['jaipur']['card']" alt="Jaipur" width="200" height="200"
                          class="w-full h-full object-cover" />
            <div class="bg-brand-600 flex items-center justify-center text-white">
                <i class="fas fa-route text-lg" aria-hidden="true"></i>
            </div>
        </div>
        <h3 class="mt-3 text-base font-extrabold text-ink leading-tight">Golden Triangle</h3>
    </a>
@elseif ($type === 'all-tours')
    <a href="{{ route('tours.packages') }}" class="dest-mobile-card group block min-w-0">
        <div class="aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-brand-500 to-brand-700 ring-1 ring-black/[0.04] flex items-center justify-center">
            <span class="w-14 h-14 rounded-full bg-white/15 flex items-center justify-center text-white">
                <i class="fas fa-compass text-2xl" aria-hidden="true"></i>
            </span>
        </div>
        <h3 class="mt-3 text-base font-extrabold text-ink leading-tight">All Tours</h3>
    </a>
@endif
