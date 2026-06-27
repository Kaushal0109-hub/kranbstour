@extends('layouts.admin')

@section('title', 'Site Settings')
@section('heading', 'Site Settings')

@section('content')
    <form action="{{ route('admin.master.settings.update') }}" method="POST" class="max-w-2xl space-y-6">
        @csrf @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
            @foreach ([
                'name' => 'Site Name',
                'tagline' => 'Tagline',
                'phone' => 'Phone',
                'phone_display' => 'Phone Display',
                'email' => 'Email',
                'whatsapp' => 'WhatsApp',
            ] as $key => $label)
                <div>
                    <label class="block text-xs font-bold text-ink-muted mb-1">{{ $label }}</label>
                    <input name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}" @if($key === 'name') required @endif class="w-full px-3 py-2 rounded-xl border border-slate-200">
                </div>
            @endforeach
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('description', $settings['description'] ?? '') }}</textarea>
            </div>
        </div>

        <button type="submit" class="px-5 py-2.5 bg-brand text-white font-bold rounded-xl">Save Settings</button>
    </form>
@endsection
