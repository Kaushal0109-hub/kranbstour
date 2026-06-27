@extends('layouts.app')

@section('title', 'Register — ' . config('site.name'))

@section('content')
    <section class="min-h-[80vh] flex items-center py-16 bg-surface">
        <div class="max-w-md w-full mx-auto px-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-8">
                <div class="text-center mb-8">
                    @include('partials.logo')
                    <h1 class="text-2xl font-extrabold text-ink mt-4">Create Account</h1>
                    <p class="text-sm text-ink-muted mt-1">Book tours & manage your trips</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-bold text-ink mb-1.5">Full name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold text-ink mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-bold text-ink mb-1.5">Phone (optional)</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold text-ink mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required minlength="8"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-ink mb-1.5">Confirm password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <button type="submit" class="btn-accent w-full text-white font-bold py-3.5 rounded-xl">Create Account</button>
                </form>

                <p class="text-center text-sm text-ink-muted mt-6">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-bold text-brand hover:text-brand-700">Login</a>
                </p>
            </div>
        </div>
    </section>
@endsection
