<section class="py-12 bg-white" aria-label="Trust indicators">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center bg-brand-50 rounded-2xl p-6 border border-brand-100">
            <div class="lg:col-span-4 flex items-center gap-4">
                <div class="flex -space-x-2">
                    @foreach ($images['avatars'] as $avatar)
                        <img class="w-10 h-10 rounded-full ring-2 ring-white object-cover bg-slate-200"
                             src="{{ $avatar }}" alt="Happy Kranbstour traveler" width="40" height="40" loading="lazy">
                    @endforeach
                </div>
                <div>
                    <p class="text-sm font-bold text-ink"><span class="text-brand">100+ people</span> booked today</p>
                    <p class="text-xs text-ink-muted">with {{ config('site.name') }}</p>
                </div>
            </div>

            <div class="lg:col-span-4">
                <p class="font-bold text-ink text-sm">Trusted travel partner</p>
                <p class="text-xs text-ink-muted mt-1">Viator · GetYourGuide · TripAdvisor · Expedia</p>
            </div>

            <div class="lg:col-span-4 grid grid-cols-4 gap-3 text-center">
                @foreach ($stats as $stat)
                    <div>
                        <p class="text-xl sm:text-2xl font-extrabold text-brand">{{ $stat['value'] }}</p>
                        <p class="text-[10px] text-ink-muted font-medium mt-0.5">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
