<section class="py-14 bg-gradient-to-br from-ink via-slate-800 to-brand-700" aria-label="Book tour CTA">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-3">Ready to book your {{ $tour['city'] }} tour?</h2>
        <p class="text-slate-300 text-sm sm:text-base mb-8">Get a custom quote within 2 hours — free cancellation on most packages.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('contact') }}" class="btn-accent text-white font-bold text-sm px-10 py-4 rounded-full">
                Get Free Quote
            </a>
            @if (\App\Helpers\SiteHelper::phoneDisplay())
            <a href="{{ \App\Helpers\SiteHelper::telHref() }}" class="border-2 border-white/40 text-white font-semibold text-sm px-10 py-4 rounded-full hover:bg-white/10 transition-colors">
                Call {{ \App\Helpers\SiteHelper::phoneDisplay() }}
            </a>
            @endif
        </div>
    </div>
</section>
