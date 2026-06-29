@php
    use App\Services\TourCatalog;
@endphp

<section id="tour-packages" class="py-12 sm:py-16 bg-surface" aria-labelledby="packages-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 sm:mb-10">
            <div>
                <span class="text-accent text-xs font-bold uppercase tracking-widest">Tour Packages</span>
                <h2 id="packages-heading" class="text-2xl sm:text-3xl font-extrabold text-ink mt-2 tracking-tight">
                    Popular {{ $tour['city'] }} tours
                </h2>
                <p class="text-sm text-ink-muted mt-1">Private tours with local guides — instant booking available</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-ink-muted">
                <span class="text-accent font-bold"><i class="fas fa-star text-xs" aria-hidden="true"></i> 4.9</span>
                <span>· Free cancellation on most tours</span>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($tour['tours'] as $package)
                @php
                    $packageUrl = $package['url'] ?? TourCatalog::packageUrl($tour['slug'], $package);
                @endphp
                <article class="tour-card card-hover bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-soft flex flex-col">
                    <a href="{{ $packageUrl }}" class="relative aspect-[16/10] overflow-hidden bg-slate-200 block group">
                        <x-site-image :src="$package['image']" :alt="$package['title']" width="600" height="375"
                                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        @if ($package['tag'])
                            <span class="absolute top-3 left-3 bg-accent text-white text-[10px] font-bold uppercase px-2.5 py-1 rounded-md shadow">
                                {{ $package['tag'] }}
                            </span>
                        @endif
                        <span class="absolute bottom-3 right-3 bg-ink/75 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded-md">
                            {{ $package['duration'] }}
                        </span>
                    </a>

                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-xs font-bold text-accent flex items-center gap-1">
                                <i class="fas fa-star text-[9px]" aria-hidden="true"></i>{{ $package['rating'] }}
                            </span>
                        </div>
                        <h3 class="font-bold text-ink text-base leading-snug mb-3 flex-1">
                            <a href="{{ $packageUrl }}" class="hover:text-brand transition-colors">{{ $package['title'] }}</a>
                        </h3>

                        @if (!empty($package['highlights']))
                            <ul class="flex flex-wrap gap-1.5 mb-4">
                                @foreach ($package['highlights'] as $h)
                                    <li class="text-[10px] font-semibold text-brand bg-brand-50 px-2 py-0.5 rounded">{{ $h }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                            <x-tour-price :display="$package['price']" />
                            <a href="{{ $packageUrl }}"
                               class="bg-brand hover:bg-brand-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
