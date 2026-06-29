@extends('layouts.admin')

@section('title', 'Configure '.$gateway->name)
@section('heading', 'Configure '.$gateway->name)

@section('content')
    <div class="max-w-2xl">
        <a href="{{ route('admin.master.payment-gateways.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand hover:underline mb-6">
            <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i> Back to gateways
        </a>

        <form action="{{ route('admin.master.payment-gateways.update', $gateway) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-5">
                <div class="flex items-center gap-4 pb-4 border-b border-slate-100">
                    <span class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-lg"
                          style="background-color: {{ $meta['brand_color'] ?? '#1a8578' }}">
                        <i class="{{ $meta['icon'] ?? 'fa-credit-card' }}" aria-hidden="true"></i>
                    </span>
                    <div>
                        <h2 class="font-extrabold text-ink">{{ $gateway->name }}</h2>
                        <p class="text-xs text-ink-muted mt-0.5">{{ $gateway->description }}</p>
                    </div>
                </div>

                @if (! empty($meta['docs_url']))
                    <p class="text-xs text-ink-muted bg-surface rounded-xl px-4 py-3">
                        Get API keys from
                        <a href="{{ $meta['docs_url'] }}" target="_blank" rel="noopener" class="font-bold text-brand hover:underline">developer dashboard</a>.
                    </p>
                @endif

                <label class="flex items-center justify-between gap-4 cursor-pointer">
                    <span>
                        <span class="block text-sm font-bold text-ink">Enable gateway</span>
                        <span class="block text-xs text-ink-muted">Show on booking checkout when credentials are set</span>
                    </span>
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $gateway->is_active)) class="w-5 h-5 rounded border-slate-300 text-brand">
                </label>

                <label class="flex items-center justify-between gap-4 cursor-pointer">
                    <span>
                        <span class="block text-sm font-bold text-ink">Test / Sandbox mode</span>
                        <span class="block text-xs text-ink-muted">Use test credentials — no real charges</span>
                    </span>
                    <input type="checkbox" name="is_test_mode" value="1" @checked(old('is_test_mode', $gateway->is_test_mode)) class="w-5 h-5 rounded border-slate-300 text-brand">
                </label>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
                <h3 class="font-bold text-ink text-sm">API Credentials</h3>

                @foreach ($meta['fields'] ?? [] as $key => $field)
                    <div>
                        <label class="block text-xs font-bold text-ink-muted mb-1">{{ $field['label'] }}</label>
                        <input
                            type="{{ $field['type'] ?? 'text' }}"
                            name="credentials[{{ $key }}]"
                            value="{{ old("credentials.{$key}", $gateway->credential($key)) }}"
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-brand font-mono"
                            placeholder="{{ $field['label'] }}"
                            @if(($field['type'] ?? '') === 'password') autocomplete="new-password" @endif
                        >
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-brand text-white font-bold rounded-xl text-sm">Save Gateway</button>
                <a href="{{ route('admin.master.payment-gateways.index') }}" class="px-6 py-2.5 border border-slate-200 text-ink font-semibold rounded-xl text-sm hover:bg-surface">Cancel</a>
            </div>
        </form>
    </div>
@endsection
