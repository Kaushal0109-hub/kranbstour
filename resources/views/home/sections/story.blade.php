<section class="py-14 sm:py-20 bg-gradient-to-b from-white to-surface-alt" aria-labelledby="story-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-12">
            <span class="bg-brand-50 text-brand text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">Why {{ config('site.name') }}?</span>
            <h2 id="story-heading" class="text-2xl sm:text-3xl font-extrabold text-ink mt-4 tracking-tight">
                Your trusted North India tour partner
            </h2>
            <p class="text-ink-muted text-sm mt-3 leading-relaxed">
                Local experts, private tours & honest pricing — everything you need for a hassle-free trip.
            </p>
        </div>

        {{-- Mobile: horizontal scroll --}}
        <div class="mobile-scroll-x sm:hidden -mx-4 px-4 pb-2">
            @foreach ($highlights as $index => $highlight)
                <div @class([
                    'mobile-scroll-item w-[82vw] max-w-[320px] p-5 rounded-2xl border flex gap-4 items-start shadow-soft',
                    'bg-gradient-to-br from-brand-500 to-brand-600 text-white border-transparent' => $index === 0,
                    'bg-white border-slate-100' => $index !== 0,
                ])>
                    <div @class([
                        'w-11 h-11 rounded-xl flex items-center justify-center text-lg shrink-0',
                        'bg-white/20' => $index === 0,
                        'bg-brand-50 text-brand' => $index !== 0,
                    ])>
                        <i class="fas {{ $highlight['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 @class(['font-extrabold text-sm mb-1', 'text-ink' => $index !== 0])>{{ $highlight['title'] }}</h3>
                        <p @class(['text-xs leading-relaxed', 'text-white/85' => $index === 0, 'text-ink-muted' => $index !== 0])>{{ $highlight['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <p class="sm:hidden text-center text-[10px] text-ink-muted mt-3 font-medium">← Swipe to see more →</p>

        {{-- Desktop: grid --}}
        <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($highlights as $index => $highlight)
                <div @class([
                    'card-hover p-6 rounded-2xl border flex flex-col gap-4 min-h-[190px] relative overflow-hidden',
                    'bg-gradient-to-br from-brand-500 to-brand-600 text-white border-transparent shadow-lg' => $index === 0,
                    'bg-white border-slate-100 shadow-soft' => $index !== 0,
                ])>
                    @if ($index === 0)
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                    @endif
                    <div @class([
                        'w-12 h-12 rounded-xl flex items-center justify-center text-xl relative z-10',
                        'bg-white/20' => $index === 0,
                        'bg-brand-50 text-brand' => $index !== 0,
                    ])>
                        <i class="fas {{ $highlight['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <div class="relative z-10">
                        <h3 @class(['font-extrabold text-base mb-1.5', 'text-ink' => $index !== 0])>{{ $highlight['title'] }}</h3>
                        <p @class(['text-xs leading-relaxed', 'text-white/85' => $index === 0, 'text-ink-muted' => $index !== 0])>{{ $highlight['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
