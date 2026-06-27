@php($variant = $variant ?? 'light')
@php($logoSrc = $variant === 'dark' ? config('site.logo.white') : config('site.logo.default'))

<a href="{{ route('home') }}" class="inline-flex items-center shrink-0 group" aria-label="{{ config('site.name') }} — Home">
    <img src="{{ asset($logoSrc) }}"
         alt="{{ config('site.name') }} — India Tour Packages"
         class="h-11 sm:h-12 w-auto max-w-[220px] sm:max-w-[248px] transition-transform duration-300 group-hover:scale-[1.02]"
         width="268"
         height="56">
</a>
