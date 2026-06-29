@extends('layouts.app')

@php
    $slug = $slug ?? '';
    $isLegal = in_array($slug, ['terms', 'privacy'], true);
@endphp

@section('title', $title . ' — ' . config('site.name'))
@section('meta_description', str(strip_tags($content ?? ''))->limit(160))

@section('content')
    <section class="relative pt-28 pb-12 sm:pb-14 overflow-hidden min-h-[260px] sm:min-h-[300px] flex items-end">
        <div class="absolute inset-0">
            <x-site-image src="cities/hero-main.jpg" :alt="$heading" width="1920" height="800" :eager="true"
                          class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-br from-ink/88 via-brand-900/75 to-ink/80"></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-2">
            <nav class="text-xs text-brand-100/80 mb-4 flex flex-wrap items-center gap-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span aria-hidden="true">/</span>
                <span class="text-white">{{ $heading }}</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $heading }}</h1>
        </div>
    </section>

    <section class="py-10 sm:py-14 bg-surface">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-6 sm:p-8 lg:p-10">
                @if (!empty($content))
                    <div @class(['cms-content text-ink-muted leading-relaxed', 'cms-content-legal' => $isLegal])>
                        {!! $content !!}
                    </div>
                @else
                    <p class="text-ink-muted">Content coming soon.</p>
                @endif
            </div>

            @if ($slug === 'about')
                <div class="mt-8 grid sm:grid-cols-3 gap-4">
                    @foreach ([
                        ['icon' => 'fa-user-shield', 'title' => 'Licensed guides', 'text' => 'Expert local guides for every tour.'],
                        ['icon' => 'fa-car-side', 'title' => 'Private transport', 'text' => 'AC vehicles with hotel pickup.'],
                        ['icon' => 'fa-headset', 'title' => '24/7 support', 'text' => 'We reply within 2 hours.'],
                    ] as $item)
                        <div class="bg-white rounded-xl border border-slate-100 p-5 text-center">
                            <span class="w-11 h-11 rounded-xl bg-brand-50 text-brand inline-flex items-center justify-center mb-3">
                                <i class="fas {{ $item['icon'] }}" aria-hidden="true"></i>
                            </span>
                            <h3 class="font-bold text-ink text-sm mb-1">{{ $item['title'] }}</h3>
                            <p class="text-xs text-ink-muted">{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-3">
                @if ($slug !== 'about')
                    <a href="{{ route('about') }}" class="text-sm font-bold text-brand hover:underline">About us</a>
                @endif
                <a href="{{ route('contact') }}" class="btn-brand text-white font-bold text-sm px-6 py-2.5 rounded-xl {{ $slug === 'about' ? '' : 'ml-auto' }}">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .cms-content h2 { font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 1.5rem 0 0.65rem; }
    .cms-content h3 { font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 1.15rem 0 0.45rem; }
    .cms-content p { margin-bottom: 0.85rem; }
    .cms-content ul, .cms-content ol { margin: 0 0 0.85rem 1.2rem; }
    .cms-content ul { list-style: disc; }
    .cms-content ol { list-style: decimal; }
    .cms-content li { margin-bottom: 0.3rem; }
    .cms-content a { color: #1a8578; font-weight: 600; }
    .cms-content-legal h2 { font-size: 1.05rem; margin-top: 1.25rem; }
</style>
@endpush
