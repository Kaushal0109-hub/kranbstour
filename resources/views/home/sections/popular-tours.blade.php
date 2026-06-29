<section class="py-16 bg-surface" aria-labelledby="popular-tours-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div>
                <span class="text-accent text-xs font-bold uppercase tracking-widest">Traveler’s Favourites</span>
                <h2 id="popular-tours-heading" class="text-2xl sm:text-3xl font-extrabold text-ink mt-2 tracking-tight">
                    Most popular tours right now
                </h2>
                <p class="text-ink-muted text-sm mt-2">Handpicked bestsellers — Delhi, Agra & beyond</p>
            </div>
            <a href="{{ route('tours.packages') }}" class="text-sm font-bold text-brand hover:text-brand-700 transition-colors shrink-0">
                View all packages →
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($popularTours as $index => $tour)
                <a href="{{ $tour['package_url'] }}"
                   @class([
                       'tour-card card-hover group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-soft flex flex-col',
                       'sm:col-span-2 lg:col-span-1 lg:row-span-1' => $index === 0,
                   ])>
                    <div @class([
                        'relative overflow-hidden bg-slate-200',
                        'aspect-[16/9]' => $index === 0,
                        'aspect-[16/10]' => $index !== 0,
                    ])>
                        <x-site-image :src="$tour['image']" :alt="$tour['title']" width="600" height="400"
                                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/50 via-transparent to-transparent"></div>

                        @if ($tour['tag'])
                            <span class="absolute top-3 left-3 bg-accent text-white text-[10px] font-bold uppercase px-2.5 py-1 rounded-md shadow">
                                {{ $tour['tag'] }}
                            </span>
                        @endif

                        <span class="absolute bottom-3 left-3 inline-flex items-center gap-1.5 bg-white/95 backdrop-blur text-ink text-[11px] font-bold px-2.5 py-1 rounded-md">
                            <i class="fas {{ $tour['city_icon'] }} text-brand text-[10px]" aria-hidden="true"></i>
                            {{ $tour['city'] }}
                        </span>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-xs text-ink-muted font-medium">{{ $tour['duration'] }}</span>
                            <span class="text-xs font-bold text-accent flex items-center gap-1">
                                <i class="fas fa-star text-[9px]" aria-hidden="true"></i>{{ $tour['rating'] }}
                            </span>
                        </div>
                        <h3 class="font-bold text-ink text-base leading-snug mb-4 group-hover:text-brand transition-colors flex-1">
                            {{ $tour['title'] }}
                        </h3>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                            <x-tour-price :display="$tour['price']" price-class="text-xl" />
                            <span class="bg-brand-50 group-hover:bg-brand text-brand group-hover:text-white text-xs font-bold px-4 py-2.5 rounded-lg transition-colors">
                                View Details
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
