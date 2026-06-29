@extends('layouts.app')

@section('title', 'Login — ' . config('site.name'))

@section('content')
    <section class="min-h-[80vh] flex items-center py-12 sm:py-16 bg-gradient-to-b from-surface to-white">
        <div class="max-w-md w-full mx-auto px-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6 sm:p-8">
                <div class="text-center mb-8">
                    @include('partials.logo')
                    <h1 class="text-2xl font-extrabold text-ink mt-4">Sign In</h1>
                    <!-- <p class="text-sm text-ink-muted mt-1">We'll email you a one-time code — no password needed</p> -->
                </div>

                @if (session('error'))
                    <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="mb-5 p-3 rounded-xl bg-brand-50 border border-brand-100 text-brand-700 text-sm">{{ session('success') }}</div>
                @endif

                <form action="{{ route('login.otp.send') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-ink mb-1.5">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="you@example.com"
                               class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <button type="submit" class="btn-brand w-full text-white font-bold py-3.5 rounded-xl">
                        <i class="fas fa-envelope mr-2" aria-hidden="true"></i>Send Login Code
                    </button>
                </form>

                @include('auth.partials.google-login')

                <p class="text-center text-sm text-ink-muted mt-6">
                    New here?
                    <a href="{{ route('register') }}" class="font-bold text-brand hover:text-brand-700">Create account</a>
                </p>
            </div>
        </div>
    </section>
@endsection
