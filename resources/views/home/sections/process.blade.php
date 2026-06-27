<section id="how-it-works" class="py-14 sm:py-20 bg-white relative overflow-hidden" aria-labelledby="process-heading">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-brand-50/50 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
            <span class="text-accent text-xs font-bold uppercase tracking-widest">Easy Booking</span>
            <h2 id="process-heading" class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight mt-2">Book your tour in 3 simple steps</h2>
            <p class="text-sm text-ink-muted mt-2">From choosing a city to exploring with your guide — we make it effortless</p>
        </div>

        @php
            $steps = [
                ['icon' => 'fa-map-marked-alt', 'color' => 'bg-orange-50 border-orange-200 text-accent', 'num' => '01', 'title' => 'Pick your city', 'text' => 'Browse tours in Agra, Delhi, Jaipur or Varanasi.'],
                ['icon' => 'fa-calendar-check', 'color' => 'bg-brand-50 border-brand-200 text-brand', 'num' => '02', 'title' => 'Select date & book', 'text' => 'Choose date, group size & extras. Instant confirmation.'],
                ['icon' => 'fa-route', 'color' => 'bg-emerald-50 border-emerald-200 text-brand-700', 'num' => '03', 'title' => 'Explore with guide', 'text' => 'Your local guide handles transport, tickets & timing.'],
            ];
        @endphp

        {{-- Mobile: vertical timeline --}}
        <div class="md:hidden relative pl-10 space-y-5 mb-8">
            <div class="absolute left-[15px] top-3 bottom-3 w-0.5 bg-gradient-to-b from-brand via-brand-200 to-brand-100" aria-hidden="true"></div>
            @foreach ($steps as $i => $step)
                <div class="relative">
                    <span class="absolute -left-10 top-5 w-8 h-8 rounded-full bg-brand text-white text-xs font-extrabold flex items-center justify-center shadow-md ring-4 ring-white z-10">
                        {{ $i + 1 }}
                    </span>
                    <div class="card-hover bg-surface rounded-2xl p-4 border border-slate-100 shadow-soft flex gap-4 items-start">
                        <div class="w-12 h-12 rounded-xl border {{ $step['color'] }} flex items-center justify-center text-lg shrink-0">
                            <i class="fas {{ $step['icon'] }}" aria-hidden="true"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Step {{ $i + 1 }}</span>
                            <h3 class="font-extrabold text-ink text-base mt-0.5 mb-1">{{ $step['title'] }}</h3>
                            <p class="text-ink-muted text-xs leading-relaxed">{{ $step['text'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Desktop: connected steps --}}
        <div class="hidden md:grid md:grid-cols-3 gap-6 lg:gap-8 relative">
            <div class="absolute top-[3.25rem] left-[16%] right-[16%] h-0.5 bg-gradient-to-r from-orange-200 via-brand-200 to-emerald-200 hidden lg:block" aria-hidden="true"></div>

            @foreach ($steps as $i => $step)
                <div class="card-hover bg-surface rounded-2xl p-7 border border-slate-100 shadow-soft text-center relative">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-brand text-white text-[10px] font-extrabold px-3 py-1 rounded-full shadow-md">
                        STEP {{ $i + 1 }}
                    </span>
                    <div class="w-16 h-16 mx-auto rounded-2xl border-2 {{ $step['color'] }} flex items-center justify-center text-2xl mb-5 mt-2 bg-white relative z-10">
                        <i class="fas {{ $step['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <h3 class="font-extrabold text-ink text-lg mb-2">{{ $step['title'] }}</h3>
                    <p class="text-ink-muted text-sm leading-relaxed">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-8 sm:mt-12">
            <a href="{{ route('contact') }}" class="btn-brand inline-flex items-center gap-2 text-white font-bold text-sm px-10 py-4 rounded-full">
                Plan my trip
                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>
