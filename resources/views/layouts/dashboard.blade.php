<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Account — ' . config('site.name'))</title>
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
                    boxShadow: {
                        soft: '0 4px 24px -4px rgba(15, 23, 42, 0.08)',
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
</head>
@php
    $user = auth()->user();
    $initials = collect(explode(' ', $user->name))->take(2)->map(fn ($w) => strtoupper(substr($w, 0, 1)))->implode('');
    $navItems = [
        ['Dashboard', 'dashboard.index', 'fa-home'],
        ['My Bookings', 'dashboard.bookings', 'fa-suitcase'],
        ['Profile', 'dashboard.profile', 'fa-user'],
    ];
@endphp
<body class="bg-surface text-ink antialiased min-h-screen">
    <div class="min-h-screen flex flex-col lg:flex-row">
        {{-- Sidebar --}}
        <aside class="lg:w-64 bg-white border-b lg:border-b-0 lg:border-r border-slate-200 shrink-0 lg:min-h-screen lg:sticky lg:top-0 flex flex-col">
            <div class="p-5 border-b border-slate-100">
                <a href="{{ route('home') }}">@include('partials.logo')</a>
            </div>

            <nav class="p-3 flex lg:flex-col gap-1 overflow-x-auto lg:overflow-visible">
                @foreach ($navItems as [$label, $route, $icon])
                    <a href="{{ route($route) }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors whitespace-nowrap shrink-0',
                           'bg-brand text-white shadow-sm' => request()->routeIs($route),
                           'text-ink-muted hover:bg-surface hover:text-brand' => !request()->routeIs($route),
                       ])>
                        <i class="fas {{ $icon }} w-4 text-center" aria-hidden="true"></i>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto p-4 border-t border-slate-100 hidden lg:block">
                <div class="flex items-center gap-3 px-3 py-2 mb-2">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-brand-600 text-white flex items-center justify-center text-sm font-extrabold shrink-0">{{ $initials }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-ink truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-ink-muted truncate">{{ $user->email }}</p>
                    </div>
                </div>
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
            <header class="bg-white/80 backdrop-blur border-b border-slate-200 px-5 sm:px-8 py-4 flex items-center justify-between gap-4 sticky top-0 z-10">
                <div class="min-w-0">
                    <h1 class="text-lg sm:text-xl font-extrabold text-ink truncate">@yield('heading', 'Overview')</h1>
                    <p class="text-xs text-ink-muted mt-0.5 hidden sm:block">Hi {{ $user->name }}, manage your trips here</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <form action="{{ route('logout') }}" method="POST" class="lg:hidden">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-red-600 px-3 py-2 rounded-lg hover:bg-red-50">Logout</button>
                    </form>
                    <a href="{{ route('home') }}" class="text-xs font-bold text-brand hover:text-brand-700 px-3 py-2 rounded-lg hover:bg-brand-50 transition-colors">← Site</a>
                </div>
            </header>

            <main class="flex-1 p-5 sm:p-8">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-brand-50 border border-brand-100 text-brand-700 text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>{{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>{{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
