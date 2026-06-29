@extends('layouts.app')

@section('title', 'Blog — ' . config('site.name'))
@section('meta_description', 'Travel stories, tips and guides for Agra, Delhi, Jaipur, Varanasi and Golden Triangle tours.')

@section('content')
    <section class="relative pt-28 pb-14 sm:pb-16 bg-gradient-to-br from-brand-600 via-brand-700 to-ink overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 80% 20%, #3db976 0%, transparent 45%), radial-gradient(circle at 10% 80%, #f97316 0%, transparent 40%);"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-brand-100/80 mb-5 flex flex-wrap items-center gap-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span aria-hidden="true">/</span>
                <span class="text-white">Blog</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-3">Travel Blog</h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl">Stories, tips and local insights from our guides across North India.</p>
        </div>
    </section>

    <section class="py-12 sm:py-16 bg-surface">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($posts->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-100 shadow-soft p-10 text-center">
                    <p class="text-ink-muted">No blog posts yet. Check back soon for travel tips and stories.</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach ($posts as $post)
                        <article class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                            <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/10] overflow-hidden bg-slate-100">
                                @if ($post->featured_image)
                                    <x-site-image :src="$post->featured_image" :alt="$post->title" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-50 to-brand-100 text-brand">
                                        <i class="fas fa-newspaper text-4xl opacity-40" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="p-5 sm:p-6 flex flex-col flex-1">
                                @if ($post->published_at)
                                    <time datetime="{{ $post->published_at->toDateString() }}" class="text-[11px] font-bold uppercase tracking-wider text-brand mb-2">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                @endif
                                <h2 class="text-lg font-extrabold text-ink mb-2 leading-snug">
                                    <a href="{{ route('blog.show', $post) }}" class="hover:text-brand transition-colors">{{ $post->title }}</a>
                                </h2>
                                <p class="text-sm text-ink-muted leading-relaxed flex-1 mb-4">
                                    {{ $post->excerpt ?: str($post->content)->stripTags()->limit(140) }}
                                </p>
                                <a href="{{ route('blog.show', $post) }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand hover:underline">
                                    Read more <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($posts->hasPages())
                    <div class="mt-10">{{ $posts->links() }}</div>
                @endif
            @endif
        </div>
    </section>
@endsection
