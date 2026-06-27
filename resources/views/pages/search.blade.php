@extends('layouts.app')

@section('title', 'Search Tours — ' . config('site.name'))

@section('content')
    <section class="pt-28 pb-20 bg-surface">
        <div class="max-w-2xl mx-auto px-4">
            <h1 class="text-3xl font-extrabold text-ink mb-6">Search Results</h1>
            <form action="{{ route('search') }}" method="GET" role="search" class="bg-white p-3 rounded-2xl shadow-soft border border-slate-100 mb-8">
                <div class="flex gap-2">
                    <input type="search" name="q" value="{{ $query }}" placeholder="Destination, tour, attraction..."
                           class="flex-1 px-4 py-3 bg-surface rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                    <button type="submit" class="btn-brand text-white font-bold text-sm px-6 py-3 rounded-xl">Search</button>
                </div>
            </form>
            @if ($query === '')
                <p class="text-ink-muted">Enter a destination or tour to search.</p>
            @else
                <p class="text-ink-muted">Results for <strong class="text-ink">"{{ $query }}"</strong> — full search coming soon.</p>
                <a href="{{ route('tours.packages') }}" class="inline-block mt-4 text-brand font-bold hover:underline">Browse all packages →</a>
            @endif
        </div>
    </section>
@endsection
