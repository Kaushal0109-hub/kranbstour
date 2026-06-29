@extends('layouts.admin')

@section('title', 'Maps API — Pickup Location')
@section('heading', 'Maps API — Pickup Location')

@section('content')
    <div class="max-w-2xl">
        <p class="text-sm text-ink-muted mb-6">
            Add your Google Maps API key to enable address search on the booking page pickup location field.
        </p>

        <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-ink-muted uppercase tracking-wide">Status</p>
                <p @class([
                    'font-extrabold text-lg mt-1',
                    'text-emerald-600' => $isConfigured,
                    'text-amber-600' => ! $isConfigured,
                ])>{{ $isConfigured ? 'Active — pickup search enabled' : 'Not configured' }}</p>
            </div>
            <span @class([
                'w-12 h-12 rounded-xl flex items-center justify-center text-xl',
                'bg-emerald-50 text-emerald-600' => $isConfigured,
                'bg-slate-100 text-slate-400' => ! $isConfigured,
            ])>
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
            </span>
        </div>

        <form action="{{ route('admin.master.maps-settings.update') }}" method="POST" class="bg-white rounded-2xl border border-slate-100 p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Google Maps API Key</label>
                <input type="text" name="google_maps_api_key"
                       value="{{ old('google_maps_api_key', $apiKey) }}"
                       class="w-full px-3 py-2.5 rounded-xl border border-slate-200 font-mono text-sm"
                       placeholder="AIzaSy...">
                <p class="text-[11px] text-ink-muted mt-2 leading-relaxed">
                    1. Go to <a href="https://console.cloud.google.com/google/maps-apis/credentials" target="_blank" rel="noopener" class="text-brand font-semibold hover:underline">Google Cloud Console</a><br>
                    2. Create an API key<br>
                    3. Enable <strong>Places API</strong> and <strong>Maps JavaScript API</strong><br>
                    4. Paste the key here and save
                </p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brand text-white font-bold rounded-xl text-sm">Save API Key</button>
                @if ($isConfigured)
                    <button type="submit" name="clear_key" value="1"
                            onclick="return confirm('Remove API key? Pickup search will be disabled.')"
                            class="px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl">
                        Remove key
                    </button>
                @endif
            </div>
        </form>
    </div>
@endsection
