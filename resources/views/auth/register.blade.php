@extends('layouts.app')

@section('title', 'Sign Up — ' . config('site.name'))

@section('content')
    <section class="min-h-[80vh] flex items-center py-12 sm:py-16 bg-gradient-to-b from-surface to-white">
        <div class="max-w-md w-full mx-auto px-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6 sm:p-8">
                <div class="text-center mb-8">
                    @include('partials.logo')
                    <h1 class="text-2xl font-extrabold text-ink mt-4">Create Account</h1>
                    <p class="text-sm text-ink-muted mt-1">Verify your email with a code — no password required</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">{{ session('error') }}</div>
                @endif

                <form action="{{ route('register.otp.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-bold text-ink mb-1.5">Full name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Your name"
                               class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold text-ink mb-1.5">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               placeholder="you@example.com"
                               class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-bold text-ink mb-1.5">Phone <span class="font-normal text-ink-muted">(optional)</span></label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                               placeholder="+91 98765 43210"
                               class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <button type="submit" class="btn-accent w-full text-white font-bold py-3.5 rounded-xl">
                        <i class="fas fa-paper-plane mr-2" aria-hidden="true"></i>Send Verification Code
                    </button>
                </form>

                @include('auth.partials.google-login', ['intent' => 'register'])

                <p class="text-center text-sm text-ink-muted mt-6">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-bold text-brand hover:text-brand-700">Sign in</a>
                </p>
            </div>
        </div>
    </section>
@endsection
