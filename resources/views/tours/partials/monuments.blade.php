@php
    use App\Services\TourCatalog;
@endphp

<section class="py-12 sm:py-16 bg-white" aria-labelledby="monuments-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
            <span class="text-brand text-xs font-bold uppercase tracking-widest">Top Attractions</span>
            <h2 id="monuments-heading" class="text-2xl sm:text-3xl font-extrabold text-ink mt-2 tracking-tight">
                Iconic monuments in {{ $tour['city'] }}
            </h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @foreach ($tour['monuments'] as $monument)
                @php
                    $monumentUrl = $monument['url'] ?? TourCatalog::monumentUrl($tour['slug'], $monument);
                @endphp
                <a href="{{ $monumentUrl }}"
                   class="group rounded-2xl overflow-hidden border border-slate-100 shadow-soft bg-slate-100 block hover:border-brand/30 hover:shadow-md transition-all">
                    <div class="aspect-[4/3] overflow-hidden">
                        <x-site-image :src="$monument['image']" :alt="$monument['name']" width="400" height="300"
                                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    </div>
                    <div class="p-3 sm:p-4 bg-white">
                        <h3 class="font-bold text-ink text-sm sm:text-base leading-tight group-hover:text-brand transition-colors">
                            {{ $monument['name'] }}
                        </h3>
                        <p class="text-[11px] sm:text-xs text-ink-muted mt-1 leading-relaxed line-clamp-2">{{ $monument['desc'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
