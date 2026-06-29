@extends('layouts.app')

@section('title', $post->title . ' — Blog — ' . config('site.name'))
@section('meta_description', $post->excerpt ?: str($post->content)->stripTags()->limit(160))

@section('content')
    <section class="relative pt-28 pb-10 sm:pb-12 overflow-hidden">
        @if ($post->featured_image)
            <div class="absolute inset-0">
                <x-site-image :src="$post->featured_image" :alt="$post->title" class="w-full h-full object-cover" :eager="true" />
                <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/70 to-ink/40"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-700 to-ink"></div>
        @endif

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-brand-100/80 mb-5 flex flex-wrap items-center gap-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('blog') }}" class="hover:text-white transition-colors">Blog</a>
                <span aria-hidden="true">/</span>
                <span class="text-white line-clamp-1">{{ $post->title }}</span>
            </nav>
            @if ($post->published_at)
                <time datetime="{{ $post->published_at->toDateString() }}" class="text-xs font-bold uppercase tracking-wider text-brand-100 mb-3 block">
                    {{ $post->published_at->format('F d, Y') }}
                </time>
            @endif
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-3">{{ $post->title }}</h1>
            @if ($post->author_name)
                <p class="text-sm text-slate-300">By {{ $post->author_name }}</p>
            @endif
        </div>
    </section>

    <section class="py-10 sm:py-14 bg-surface">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-white rounded-2xl border border-slate-100 shadow-soft p-6 sm:p-8 lg:p-10">
                @if (!empty($post->content))
                    <div class="cms-content text-ink-muted leading-relaxed">
                        {!! $post->content !!}
                    </div>
                @endif
            </article>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand hover:underline">
                    <i class="fas fa-arrow-left text-xs" aria-hidden="true"></i> Back to blog
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-sm font-bold text-accent hover:underline ml-auto">
                    Plan your trip <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                </a>
            </div>

            @if ($related->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-xl font-extrabold text-ink mb-5">More articles</h2>
                    <div class="grid sm:grid-cols-3 gap-5">
                        @foreach ($related as $item)
                            <a href="{{ route('blog.show', $item) }}" class="bg-white rounded-xl border border-slate-100 p-4 hover:border-brand/30 hover:shadow-soft transition-all block">
                                <p class="text-sm font-bold text-ink line-clamp-2 hover:text-brand">{{ $item->title }}</p>
                                @if ($item->published_at)
                                    <p class="text-xs text-ink-muted mt-2">{{ $item->published_at->format('M d, Y') }}</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
    .cms-content h2 { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 1.5rem 0 0.75rem; }
    .cms-content h3 { font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 1.25rem 0 0.5rem; }
    .cms-content p { margin-bottom: 1rem; }
    .cms-content ul, .cms-content ol { margin: 0 0 1rem 1.25rem; }
    .cms-content ul { list-style: disc; }
    .cms-content ol { list-style: decimal; }
    .cms-content li { margin-bottom: 0.35rem; }
    .cms-content a { color: #1a8578; font-weight: 600; }
    .cms-content a:hover { text-decoration: underline; }
</style>
@endpush
