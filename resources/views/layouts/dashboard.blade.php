<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard — ' . config('site.name'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset(config('site.logo.icon')) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#ecfdf8', 100: '#ccfbf1', 500: '#2d9a82', 600: '#186961', 700: '#155e56', DEFAULT: '#1a8578' },
                        accent: { light: '#fb923c', DEFAULT: '#f97316', dark: '#ea580c' },
                        ink: { DEFAULT: '#0f172a', muted: '#64748b' },
                        surface: { DEFAULT: '#f8fafc', alt: '#f0fdfa' },
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
        <aside class="lg:w-64 bg-white border-b lg:border-b-0 lg:border-r border-slate-200 shrink-0">
            <div class="p-5 border-b border-slate-100">
                <a href="{{ route('home') }}">@include('partials.logo')</a>
                <p class="text-xs text-ink-muted mt-3">Customer Dashboard</p>
            </div>
            <nav class="p-4 space-y-1">
                @foreach ([
                    ['Dashboard', 'dashboard.index', 'fa-home'],
                    ['My Bookings', 'dashboard.bookings', 'fa-suitcase'],
                    ['Profile', 'dashboard.profile', 'fa-user'],
                ] as [$label, $route, $icon])
                    <a href="{{ route($route) }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors',
                           'bg-brand-50 text-brand' => request()->routeIs($route),
                           'text-ink-muted hover:bg-surface hover:text-brand' => !request()->routeIs($route),
                       ])>
                        <i class="fas {{ $icon }} w-4 text-center" aria-hidden="true"></i>
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('tours.packages') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-ink-muted hover:bg-surface hover:text-brand transition-colors">
                    <i class="fas fa-compass w-4 text-center" aria-hidden="true"></i>
                    Browse Tours
                </a>
            </nav>
            <div class="p-4 border-t border-slate-100 mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center" aria-hidden="true"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 px-5 sm:px-8 py-4 flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-lg font-extrabold text-ink">@yield('heading', 'Dashboard')</h1>
                    <p class="text-xs text-ink-muted">Welcome, {{ auth()->user()->name }}</p>
                </div>
                <a href="{{ route('home') }}" class="text-xs font-bold text-brand hover:text-brand-700">← Back to site</a>
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
