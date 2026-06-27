<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', config('site.description'))">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('site.name') . ' — ' . config('site.tagline'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset(config('site.logo.icon')) }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf8',
                            100: '#ccfbf1',
                            500: '#2d9a82',
                            600: '#186961',
                            700: '#155e56',
                            DEFAULT: '#1a8578',
                        },
                        accent: {
                            light: '#fb923c',
                            DEFAULT: '#f97316',
                            dark: '#ea580c',
                        },
                        ink: {
                            DEFAULT: '#0f172a',
                            muted: '#64748b',
                        },
                        surface: {
                            DEFAULT: '#f8fafc',
                            alt: '#f0fdfa',
                        },
                    },
                    boxShadow: {
                        soft: '0 4px 24px -4px rgba(15, 23, 42, 0.07)',
                        card: '0 12px 40px -12px rgba(15, 23, 42, 0.12)',
                        search: '0 20px 50px -12px rgba(13, 148, 136, 0.15)',
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="@yield('body_class', 'bg-surface') text-ink antialiased">

    @include('partials.floating-buttons')
    @include('partials.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    @if (session('success'))
        <div id="toast-success" class="fixed top-24 right-4 z-50 bg-brand text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-card" role="status">
            {{ session('success') }}
        </div>
        <script>setTimeout(function(){ document.getElementById('toast-success')?.remove(); }, 4000);</script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');

            if (toggle && menu) {
                toggle.addEventListener('click', function () {
                    const isOpen = !menu.classList.contains('hidden');
                    menu.classList.toggle('hidden');
                    document.body.classList.toggle('mobile-nav-open', !isOpen);
                    toggle.setAttribute('aria-expanded', String(!isOpen));
                    iconOpen.classList.toggle('hidden', !isOpen);
                    iconClose.classList.toggle('hidden', isOpen);
                });
                menu.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        menu.classList.add('hidden');
                        document.body.classList.remove('mobile-nav-open');
                        toggle.setAttribute('aria-expanded', 'false');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
