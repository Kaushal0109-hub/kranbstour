<section id="how-it-works" class="py-14 sm:py-20 bg-white relative overflow-hidden" aria-labelledby="process-heading">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-brand-50/50 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
            <span class="text-accent text-xs font-bold uppercase tracking-widest">Easy Booking</span>
            <h2 id="process-heading" class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight mt-2">Book your tour in 3 simple steps</h2>
            <p class="text-sm text-ink-muted mt-2">From choosing a city to exploring with your guide — we make it effortless</p>
        </div>

        <div class="md:hidden relative pl-10 space-y-5 mb-8">
            <div class="absolute left-[15px] top-3 bottom-3 w-0.5 bg-gradient-to-b from-brand via-brand-200 to-brand-100" aria-hidden="true"></div>
            @foreach ($processSteps as $i => $step)
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

        <div class="hidden md:grid md:grid-cols-3 gap-6 lg:gap-8">
            @foreach ($processSteps as $i => $step)
                <div class="card-hover bg-surface rounded-2xl p-6 border border-slate-100 shadow-soft text-center relative">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Step {{ $i + 1 }}</span>
                    <div class="w-14 h-14 rounded-2xl border {{ $step['color'] }} flex items-center justify-center text-xl mx-auto my-4">
                        <i class="fas {{ $step['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <h3 class="font-extrabold text-ink text-lg mb-2">{{ $step['title'] }}</h3>
                    <p class="text-ink-muted text-sm leading-relaxed">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
