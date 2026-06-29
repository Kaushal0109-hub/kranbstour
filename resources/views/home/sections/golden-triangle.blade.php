@if ($goldenTriangle)
<section class="py-14 bg-gradient-to-r from-ink via-slate-800 to-brand-700" aria-label="Golden Triangle combo tour">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7 text-white">
                @if (!empty($goldenTriangle['badge']))
                    <span class="inline-block bg-accent/20 text-accent-light text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-4">
                        {{ $goldenTriangle['badge'] }}
                    </span>
                @endif
                <h2 class="text-2xl sm:text-3xl font-extrabold mb-3 tracking-tight">
                    {{ $goldenTriangle['title'] }}
                </h2>
                @if (!empty($goldenTriangle['description']))
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl mb-6">
                        {{ $goldenTriangle['description'] }}
                    </p>
                @endif
                @if (!empty($goldenTriangle['tags']))
                    <div class="flex flex-wrap gap-3 mb-6">
                        @foreach ($goldenTriangle['tags'] as $tag)
                            <span class="text-xs font-bold bg-white/10 px-3 py-1.5 rounded-lg">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
                @if (!empty($goldenTriangle['cta_route']))
                    <a href="{{ route($goldenTriangle['cta_route']) }}"
                       class="btn-accent inline-flex items-center gap-2 text-white font-bold text-sm px-8 py-3.5 rounded-full">
                        {{ $goldenTriangle['cta_label'] ?? 'View Tours' }}
                        <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                @endif
            </div>
            <div class="lg:col-span-5 grid grid-cols-3 gap-2">
                @foreach ($goldenTriangle['city_keys'] ?? ['agra', 'delhi', 'jaipur'] as $key)
                    @php $city = collect($cities)->firstWhere('key', $key); @endphp
                    @if ($city)
                        <div class="rounded-xl overflow-hidden aspect-[3/4] bg-slate-700 shadow-lg">
                            <x-site-image :src="$city['image']['url']" :alt="$city['name']" width="300" height="400"
                                          class="w-full h-full object-cover opacity-90" />
                            <span class="sr-only">{{ $city['name'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
