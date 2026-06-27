<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — {{ config('site.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }, colors: { brand: { DEFAULT: '#1a8578', 600: '#186961' }, ink: { DEFAULT: '#0f172a' } } } } };
    </script>
</head>
<body class="min-h-screen bg-ink flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            @include('partials.logo', ['variant' => 'dark'])
            <p class="text-slate-400 text-sm mt-4 font-semibold uppercase tracking-wider">Admin Panel</p>
        </div>

        <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-8">
            <h1 class="text-xl font-extrabold text-white mb-6 text-center">Sign in to Admin</h1>

            @if (session('error'))
                <div class="mb-5 p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-300 text-sm">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-300 mb-1.5">Admin Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-brand">
                </div>
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:outline-none focus:border-brand">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-400">
                    <input type="checkbox" name="remember" class="rounded border-white/20 bg-white/5 text-brand">
                    Remember me
                </label>
                <button type="submit" class="w-full bg-brand hover:bg-brand-600 text-white font-bold py-3.5 rounded-xl transition-colors">
                    <i class="fas fa-lock mr-2 text-xs" aria-hidden="true"></i>Admin Login
                </button>
            </form>

            <p class="text-center text-xs text-slate-500 mt-6">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">← Back to website</a>
            </p>
        </div>
    </div>
</body>
</html>
