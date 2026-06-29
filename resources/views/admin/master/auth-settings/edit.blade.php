@extends('layouts.admin')

@section('title', 'Google Login')
@section('heading', 'Google Login')

@section('content')
    <div class="max-w-2xl">
        <p class="text-sm text-ink-muted mb-6">
            Enable “Continue with Google” on customer login and register pages.
        </p>

        <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-ink-muted uppercase tracking-wide">Status</p>
                <p @class([
                    'font-extrabold text-lg mt-1',
                    'text-emerald-600' => $isConfigured,
                    'text-amber-600' => ! $isConfigured,
                ])>{{ $isConfigured ? 'Active — Google login enabled' : 'Not configured' }}</p>
            </div>
            <span @class([
                'w-12 h-12 rounded-xl flex items-center justify-center text-xl',
                'bg-emerald-50 text-emerald-600' => $isConfigured,
                'bg-slate-100 text-slate-400' => ! $isConfigured,
            ])>
                <i class="fab fa-google" aria-hidden="true"></i>
            </span>
        </div>

        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5 mb-6">
            <p class="text-xs font-bold text-ink-muted uppercase tracking-wide mb-2">Authorized redirect URI</p>
            <p class="font-mono text-sm text-ink break-all">{{ $redirectUri }}</p>
            <p class="text-[11px] text-ink-muted mt-2">Add this exact URL in your Google OAuth client settings.</p>
        </div>

        <form action="{{ route('admin.master.auth-settings.update') }}" method="POST" class="bg-white rounded-2xl border border-slate-100 p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Google Client ID</label>
                <input type="text" name="google_client_id"
                       value="{{ old('google_client_id', $clientId) }}"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 font-mono text-sm"
                       placeholder="123456789.apps.googleusercontent.com">
            </div>

            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Google Client Secret</label>
                <input type="password" name="google_client_secret"
                       value="{{ old('google_client_secret', $clientSecret) }}"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 font-mono text-sm"
                       placeholder="GOCSPX-...">
            </div>

            <p class="text-[11px] text-ink-muted leading-relaxed">
                1. Go to <a href="https://console.cloud.google.com/apis/credentials" target="_blank" rel="noopener" class="text-brand font-semibold hover:underline">Google Cloud Console → Credentials</a><br>
                2. Create an <strong>OAuth 2.0 Client ID</strong> (Web application)<br>
                3. Add the redirect URI shown above<br>
                4. Under <strong>OAuth consent screen</strong>, add your email as a test user (while app is in Testing)<br>
                5. Paste Client ID and Secret here, then save
            </p>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brand text-white font-bold rounded-xl text-sm">Save Credentials</button>
                @if ($isConfigured)
                    <button type="submit" name="clear_credentials" value="1"
                            class="px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl"
                            onclick="return confirm('Remove Google login credentials?')">
                        Clear
                    </button>
                @endif
            </div>
        </form>
    </div>
@endsection
