@extends('layouts.app')

@section('title', $title . ' — ' . config('site.name'))

@section('content')
    <section class="pt-28 pb-20 bg-gradient-to-b from-brand-50 to-surface">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <span class="inline-block bg-brand-50 text-brand text-xs font-bold px-4 py-1.5 rounded-full mb-4">{{ config('site.name') }}</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-ink mb-4">{{ $heading }}</h1>
            <p class="text-ink-muted mb-8">This page is being prepared. Explore our tours on the home page.</p>
            <a href="{{ route('home') }}" class="btn-accent inline-block text-white font-bold text-sm px-8 py-3.5 rounded-full">
                Back to Home
            </a>
        </div>
    </section>
@endsection
