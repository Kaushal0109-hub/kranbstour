@php
    $navTourLinks = $navTourLinks ?? [];
    $navLinks = array_merge(
        [['label' => 'Home', 'route' => 'home']],
        $navTourLinks,
        [
            ['label' => 'Tour Packages', 'route' => 'tours.packages'],
            ['label' => 'Contact Us', 'route' => 'contact'],
        ]
    );
    $currentRoute = Route::currentRouteName();
@endphp

<header id="site-header" class="site-header sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
        @include('partials.logo')

        <nav class="hidden lg:flex items-center gap-5 text-sm font-semibold text-ink-muted" aria-label="Main navigation">
            @foreach ($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   @class([
                       'transition-colors hover:text-brand',
                       'text-brand' => $currentRoute === $link['route'],
                   ])>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2 sm:gap-3">
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="hidden sm:inline-flex text-xs sm:text-sm font-bold text-ink-muted hover:text-brand px-3 py-2.5">
                        Admin
                    </a>
                @else
                    <a href="{{ route('dashboard.index') }}"
                       class="hidden sm:inline-flex text-xs sm:text-sm font-bold text-ink-muted hover:text-brand px-3 py-2.5">
                        My Account
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline">
                    @csrf
                    <button type="submit" class="text-xs sm:text-sm font-bold text-ink-muted hover:text-red-600 px-3 py-2.5">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="hidden lg:inline-flex btn-brand text-white text-xs sm:text-sm font-bold px-4 sm:px-5 py-2.5 rounded-lg">
                    Login
                </a>
            @endauth
            <button type="button" id="mobile-menu-toggle"
                    class="lg:hidden w-10 h-10 rounded-lg bg-brand-50 text-ink-muted hover:text-brand flex items-center justify-center transition-colors"
                    aria-controls="mobile-menu" aria-expanded="false" aria-label="Menu">
                <i id="menu-icon-open" class="fas fa-bars text-lg" aria-hidden="true"></i>
                <i id="menu-icon-close" class="fas fa-times text-lg hidden" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <nav id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100 shadow-lg" aria-label="Mobile navigation">
        <ul class="max-w-7xl mx-auto px-4 py-3 space-y-0.5">
            @foreach ($navLinks as $link)
                <li>
                    <a href="{{ route($link['route']) }}"
                       @class([
                           'block px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors',
                           'text-brand bg-brand-50' => $currentRoute === $link['route'],
                           'text-ink-muted hover:text-brand hover:bg-brand-50' => $currentRoute !== $link['route'],
                       ])>
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
            @guest
                <li class="border-t border-slate-100 pt-3 mt-2">
                    <a href="{{ route('login') }}"
                       class="block w-full text-center btn-brand text-white text-sm font-bold px-4 py-3 rounded-xl">
                        Login
                    </a>
                </li>
            @endguest
            @auth
                <li class="border-t border-slate-100 pt-2 mt-2">
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-ink-muted hover:text-brand hover:bg-brand-50">Admin Panel</a>
                    @else
                        <a href="{{ route('dashboard.index') }}" class="block px-3 py-2.5 rounded-lg text-sm font-semibold text-ink-muted hover:text-brand hover:bg-brand-50">My Account</a>
                    @endif
                </li>
            @endauth
        </ul>
    </nav>
</header>
