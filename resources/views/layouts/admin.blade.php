<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — ' . config('site.name'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset(config('site.logo.icon')) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#ecfdf8', 600: '#186961', 700: '#155e56', DEFAULT: '#1a8578' },
                        accent: { DEFAULT: '#f97316' },
                        ink: { DEFAULT: '#0f172a', muted: '#64748b' },
                        surface: { DEFAULT: '#f8fafc' },
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-surface text-ink antialiased min-h-screen">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <aside class="lg:w-64 bg-ink text-white shrink-0">
            <div class="p-5 border-b border-white/10">
                @include('partials.logo', ['variant' => 'dark'])
                <p class="text-xs text-slate-400 mt-3 font-semibold uppercase tracking-wider">Admin Panel</p>
            </div>
            <nav class="p-4 space-y-1">
                @foreach ([
                    ['Dashboard', 'admin.dashboard', 'fa-chart-line'],
                    ['Bookings', 'admin.bookings', 'fa-suitcase'],
                    ['Customers', 'admin.customers', 'fa-users'],
                    ['Messages', 'admin.messages', 'fa-envelope'],
                ] as [$label, $route, $icon])
                    <a href="{{ route($route) }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors',
                           'bg-brand text-white' => request()->routeIs($route),
                           'text-slate-300 hover:bg-white/5 hover:text-white' => !request()->routeIs($route),
                       ])>
                        <i class="fas {{ $icon }} w-4 text-center" aria-hidden="true"></i>
                        {{ $label }}
                    </a>
                @endforeach

                <p class="px-4 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Master Data</p>
                @foreach ([
                    ['Cities', 'admin.master.cities.*', 'admin.master.cities.index', 'fa-city'],
                    ['Categories', 'admin.master.categories.*', 'admin.master.categories.index', 'fa-folder'],
                    ['Packages', 'admin.master.packages.*', 'admin.master.packages.index', 'fa-box-open'],
                    ['Monuments', 'admin.master.monuments.*', 'admin.master.monuments.index', 'fa-monument'],
                    ['Homepage', 'admin.master.homepage.*', 'admin.master.homepage.index', 'fa-home'],
                    ['Settings', 'admin.master.settings.*', 'admin.master.settings.edit', 'fa-cog'],
                ] as [$label, $routePattern, $route, $icon])
                    <a href="{{ route($route) }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors',
                           'bg-brand text-white' => request()->routeIs($routePattern),
                           'text-slate-300 hover:bg-white/5 hover:text-white' => !request()->routeIs($routePattern),
                       ])>
                        <i class="fas {{ $icon }} w-4 text-center" aria-hidden="true"></i>
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                    <i class="fas fa-external-link-alt w-4 text-center" aria-hidden="true"></i>
                    View Website
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-300 hover:bg-red-500/10 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center" aria-hidden="true"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 px-5 sm:px-8 py-4">
                <h1 class="text-lg font-extrabold text-ink">@yield('heading', 'Admin Dashboard')</h1>
                <p class="text-xs text-ink-muted">{{ auth()->user()->email }}</p>
            </header>

            <main class="flex-1 p-5 sm:p-8">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-brand-50 border border-brand-100 text-brand-700 text-sm font-semibold">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-semibold">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
