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
<body class="bg-surface text-ink antialiased min-h-screen" data-asset-base="{{ rtrim(asset(''), '/') }}">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <aside class="lg:w-64 bg-ink text-white shrink-0 lg:sticky lg:top-0 lg:h-screen lg:flex lg:flex-col lg:overflow-hidden">
            <div class="p-5 border-b border-white/10 shrink-0">
                @include('partials.logo', ['variant' => 'dark'])
                <p class="text-xs text-slate-400 mt-3 font-semibold uppercase tracking-wider">Admin Panel</p>
            </div>
            <nav class="admin-sidebar-scroll p-4 space-y-1 lg:flex-1 lg:overflow-y-auto lg:min-h-0">
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
                    ['Blog Posts', 'admin.master.blog-posts.*', 'admin.master.blog-posts.index', 'fa-newspaper'],
                    ['CMS Pages', 'admin.master.cms-pages.*', 'admin.master.cms-pages.index', 'fa-file-alt'],
                    ['Homepage', 'admin.master.homepage.*', 'admin.master.homepage.index', 'fa-home'],
                    ['Payment Gateways', 'admin.master.payment-gateways.*', 'admin.master.payment-gateways.index', 'fa-credit-card'],
                    ['Maps API', 'admin.master.maps-settings.*', 'admin.master.maps-settings.edit', 'fa-map-marker-alt'],
                    ['Google Login', 'admin.master.auth-settings.*', 'admin.master.auth-settings.edit', 'fa-google'],
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
            <div class="p-4 border-t border-white/10 shrink-0 mt-auto">
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
    <script>
        (function () {
            function resolveImageUrl(path) {
                if (!path) return '';
                if (/^https?:\/\//i.test(path)) return path;
                const base = document.body.dataset.assetBase || '';
                if (path.startsWith('/')) return base + path;
                const normalized = path.startsWith('images/') ? path : 'images/' + path.replace(/^\//, '');
                return base + '/' + normalized;
            }

            function updatePreview(name, path) {
                const img = document.querySelector('[data-preview-img="' + name + '"]');
                const placeholder = document.querySelector('[data-preview-placeholder="' + name + '"]');
                if (!img) return;

                const val = (path || '').trim();
                if (!val) {
                    img.classList.add('hidden');
                    img.removeAttribute('src');
                    placeholder?.classList.remove('hidden');
                    return;
                }

                img.onload = function () {
                    img.classList.remove('hidden');
                    placeholder?.classList.add('hidden');
                };
                img.onerror = function () {
                    img.classList.add('hidden');
                    placeholder?.classList.remove('hidden');
                };
                img.src = resolveImageUrl(val);
            }

            document.addEventListener('change', function (e) {
                if (!e.target.classList.contains('admin-image-file')) return;
                const name = e.target.dataset.previewFor;
                const file = e.target.files?.[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (ev) {
                    const img = document.querySelector('[data-preview-img="' + name + '"]');
                    const placeholder = document.querySelector('[data-preview-placeholder="' + name + '"]');
                    if (img) {
                        img.src = ev.target.result;
                        img.classList.remove('hidden');
                        placeholder?.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            });

            document.querySelectorAll('.admin-image-path').forEach(function (input) {
                updatePreview(input.dataset.previewFor, input.value);
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
