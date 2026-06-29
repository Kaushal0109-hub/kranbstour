@extends('layouts.app')

@section('title', 'Verify Email — ' . config('site.name'))

@section('content')
    <section class="min-h-[80vh] flex items-center py-12 sm:py-16 bg-gradient-to-b from-surface to-white">
        <div class="max-w-md w-full mx-auto px-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6 sm:p-8">
                <div class="text-center mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-xl" aria-hidden="true"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-ink">Enter Verification Code</h1>
                    <p class="text-sm text-ink-muted mt-2">
                        We sent a 6-digit code to<br>
                        <span class="font-semibold text-ink">{{ $email }}</span>
                    </p>
                </div>

                @if (session('error'))
                    <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="mb-5 p-3 rounded-xl bg-brand-50 border border-brand-100 text-brand-700 text-sm">{{ session('success') }}</div>
                @endif
                @if (session('dev_otp') && config('app.debug'))
                    <div class="mb-5 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm font-mono">
                        Dev mode OTP: <strong>{{ session('dev_otp') }}</strong>
                    </div>
                @endif

                <form action="{{ route('auth.verify-otp.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="otp" class="block text-xs font-bold text-ink mb-1.5 text-center">6-digit code</label>
                        <input type="text" id="otp" name="otp" required autofocus
                               inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                               placeholder="000000"
                               class="w-full px-4 py-4 rounded-xl border border-slate-200 bg-surface text-center text-2xl font-extrabold tracking-[0.4em] focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                    </div>
                    <button type="submit" class="btn-brand w-full text-white font-bold py-3.5 rounded-xl">
                        {{ $flow === 'register' ? 'Verify & Create Account' : 'Verify & Sign In' }}
                    </button>
                </form>

                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
                    <form action="{{ route('auth.otp.resend') }}" method="POST">
                        @csrf
                        <button type="submit" class="font-semibold text-brand hover:text-brand-700">Resend code</button>
                    </form>
                    <a href="{{ $flow === 'register' ? route('register') : route('login') }}" class="text-ink-muted hover:text-ink">
                        ← Change email
                    </a>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.getElementById('otp')?.addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 6);
        });
    </script>
    @endpush
@endsection
