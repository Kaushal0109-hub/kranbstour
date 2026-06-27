@extends('layouts.app')

@section('title', 'Login — ' . config('site.name'))

@section('content')
    <section class="min-h-[80vh] flex items-center py-16 bg-surface">
        <div class="max-w-md w-full mx-auto px-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-8">
                <div class="text-center mb-8">
                    @include('partials.logo')
                    <h1 class="text-2xl font-extrabold text-ink mt-4">Customer Login</h1>
                    <p class="text-sm text-ink-muted mt-1">Access your bookings & tour dashboard</p>
                </div>

                @if (session('error'))
                    <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">{{ session('error') }}</div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-ink mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold text-ink mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-ink-muted">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand focus:ring-brand">
                        Remember me
                    </label>
                    <button type="submit" class="btn-brand w-full text-white font-bold py-3.5 rounded-xl">Sign In</button>
                </form>

                <p class="text-center text-sm text-ink-muted mt-6">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-bold text-brand hover:text-brand-700">Register</a>
                </p>
                <p class="text-center text-xs text-ink-muted mt-4">
                    Admin?
                    <a href="{{ route('admin.login') }}" class="font-semibold text-brand hover:text-brand-700">Admin login →</a>
                </p>
            </div>
        </div>
    </section>
@endsection
