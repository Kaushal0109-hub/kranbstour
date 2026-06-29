<section class="relative min-h-[340px] sm:min-h-[420px] flex items-end overflow-hidden">
    <div class="absolute inset-0">
        <x-site-image :src="$tour['banner']" :alt="$tour['heading']" width="1920" height="800" :eager="true"
                      class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/70 to-ink/30"></div>
    </div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 sm:pb-14 pt-28">
        <nav class="text-xs text-slate-400 mb-4 flex flex-wrap items-center gap-2" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('tours.packages') }}" class="hover:text-white transition-colors">Tours</a>
            <span aria-hidden="true">/</span>
            <span class="text-white">{{ $tour['city'] }}</span>
        </nav>

        <div class="flex flex-wrap items-center gap-3 mb-4">
            <!-- <span class="w-11 h-11 bg-brand rounded-xl flex items-center justify-center text-white text-lg shadow-lg">
                <i class="fas {{ $tour['icon'] }}" aria-hidden="true"></i>
            </span> -->
            <span class="bg-white/10 backdrop-blur text-white text-xs font-bold px-3 py-1.5 rounded-full border border-white/20">
                {{ $tour['tour_count'] }}
            </span>
            @if ($tour['key'] !== 'golden-triangle')
                <span class="text-brand-100 text-xs font-semibold uppercase tracking-wider">{{ $tour['tagline'] }}</span>
            @endif
        </div>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight max-w-3xl mb-4">
            {{ $tour['heading'] }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl mb-6">
            {{ $tour['description'] }}
        </p>

        <div class="flex flex-wrap gap-3">
            <a href="#tour-packages" class="btn-accent inline-flex items-center gap-2 text-white font-bold text-sm px-7 py-3.5 rounded-full">
                View Packages
                <i class="fas fa-arrow-down text-xs" aria-hidden="true"></i>
            </a>
            @if (\App\Helpers\SiteHelper::phoneDisplay())
            <a href="{{ \App\Helpers\SiteHelper::telHref() }}" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/25 text-white font-semibold text-sm px-7 py-3.5 rounded-full hover:bg-white/20 transition-colors">
                <i class="fas fa-phone-alt text-xs" aria-hidden="true"></i>
                {{ \App\Helpers\SiteHelper::phoneDisplay() }}
            </a>
            @endif
        </div>
    </div>
</section>
