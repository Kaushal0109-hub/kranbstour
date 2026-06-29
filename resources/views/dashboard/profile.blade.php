@extends('layouts.dashboard')

@section('heading', 'My Profile')

@section('content')
    <div class="max-w-lg">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-6 sm:p-8">
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
                @php
                    $initials = collect(explode(' ', $user->name))->take(2)->map(fn ($w) => strtoupper(substr($w, 0, 1)))->implode('');
                @endphp
                <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand to-brand-600 text-white flex items-center justify-center text-lg font-extrabold shrink-0">{{ $initials }}</span>
                <div>
                    <p class="font-extrabold text-ink">{{ $user->name }}</p>
                    <p class="text-sm text-ink-muted">{{ $user->email }}</p>
                    @if ($user->google_id)
                        <p class="text-[11px] text-ink-muted mt-1"><i class="fab fa-google mr-1" aria-hidden="true"></i>Linked with Google</p>
                    @else
                        <p class="text-[11px] text-ink-muted mt-1"><i class="fas fa-envelope mr-1" aria-hidden="true"></i>Email OTP sign-in</p>
                    @endif
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-xs font-bold text-ink mb-1.5">Full name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink mb-1.5">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-sm text-ink-muted cursor-not-allowed">
                    <p class="text-[11px] text-ink-muted mt-1">Email cannot be changed here.</p>
                </div>
                <div>
                    <label for="phone" class="block text-xs font-bold text-ink mb-1.5">Phone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+91 98765 43210"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
                </div>
                <button type="submit" class="btn-brand text-white font-bold px-8 py-3 rounded-xl">Save Changes</button>
            </form>
        </div>
    </div>
@endsection
