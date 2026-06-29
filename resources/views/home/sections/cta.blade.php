@if ($ctaSection)
<section class="py-20 bg-gradient-to-br from-ink via-slate-800 to-brand-700 relative overflow-hidden" aria-label="Call to action">
    <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(circle at 25% 50%, #3db976 0%, transparent 45%), radial-gradient(circle at 75% 50%, #f97316 0%, transparent 45%);"></div>

    <div class="relative z-10 max-w-3xl mx-auto px-4 text-center">
        @if (!empty($ctaSection['badge']))
            <p class="text-brand-100 text-xs font-bold uppercase tracking-widest mb-3">{{ $ctaSection['badge'] }}</p>
        @endif
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 tracking-tight">
            {{ $ctaSection['title'] }}
        </h2>
        @if (!empty($ctaSection['description']))
            <p class="text-slate-300 text-base mb-8">{{ $ctaSection['description'] }}</p>
        @endif
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @if (!empty($ctaSection['cta_route']))
                <a href="{{ route($ctaSection['cta_route']) }}" class="btn-accent text-white font-bold text-sm px-10 py-4 rounded-full">
                    {{ $ctaSection['cta_label'] ?? 'Contact Us' }}
                </a>
            @endif
            @if (!empty($ctaSection['secondary_cta_route']))
                <a href="{{ route($ctaSection['secondary_cta_route']) }}" class="border-2 border-white/40 text-white font-semibold text-sm px-10 py-4 rounded-full hover:bg-white/10 transition-colors">
                    {{ $ctaSection['secondary_cta_label'] ?? 'Browse Tours' }}
                </a>
            @endif
        </div>
    </div>
</section>
@endif
