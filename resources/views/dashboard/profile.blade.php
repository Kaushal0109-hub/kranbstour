@extends('layouts.dashboard')

@section('heading', 'My Profile')

@section('content')
    <div class="max-w-lg bg-white rounded-2xl border border-slate-100 shadow-soft p-6 sm:p-8">
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
                       class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-sm text-ink-muted">
            </div>
            <div>
                <label for="phone" class="block text-xs font-bold text-ink mb-1.5">Phone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
            </div>
            <hr class="border-slate-100">
            <p class="text-xs font-bold text-ink-muted uppercase">Change password (optional)</p>
            <div>
                <label for="password" class="block text-xs font-bold text-ink mb-1.5">New password</label>
                <input type="password" id="password" name="password" minlength="8"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-ink mb-1.5">Confirm password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-surface text-sm focus:outline-none focus:border-brand focus:ring-2 focus:ring-brand/20">
            </div>
            <button type="submit" class="btn-brand text-white font-bold px-8 py-3 rounded-xl">Save Changes</button>
        </form>
    </div>
@endsection
