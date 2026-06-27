<section class="py-12 bg-white border-t border-slate-100" aria-labelledby="related-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 id="related-heading" class="text-xl sm:text-2xl font-extrabold text-ink mb-6">Explore other destinations</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach ($related as $cat)
                @php
                    $catRoute = match ($cat['slug']) {
                        'taj-mahal' => 'tours.taj-mahal',
                        'golden-triangle' => 'tours.golden-triangle',
                        default => 'tours.' . $cat['slug'],
                    };
                @endphp
                <a href="{{ route($catRoute) }}"
                   class="group relative rounded-2xl overflow-hidden aspect-[4/3] shadow-soft ring-1 ring-slate-100">
                    <x-site-image :src="$cat['card']" :alt="$cat['city']" width="400" height="300"
                                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div class="absolute inset-0 bg-gradient-to-t from-ink/85 via-ink/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-brand-100">{{ $cat['tagline'] }}</p>
                        <h3 class="text-base font-extrabold text-white">{{ $cat['city'] }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
