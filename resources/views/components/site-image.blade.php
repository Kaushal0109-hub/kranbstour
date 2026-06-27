@props([
    'src',
    'alt' => '',
    'class' => 'w-full h-full object-cover',
    'loading' => 'lazy',
    'width' => null,
    'height' => null,
    'eager' => false,
    'fallback' => null,
])

@php
    use App\Helpers\MediaHelper;

    $resolve = fn (?string $path) => match (true) {
        blank($path) => null,
        str_starts_with($path, 'http://'), str_starts_with($path, 'https://') => $path,
        default => MediaHelper::url($path),
    };

    $resolvedSrc = $resolve($src);
    $fallbackSrc = $resolve($fallback ?? config('site.images.fallback'));
@endphp

<img src="{{ $resolvedSrc }}"
     alt="{{ $alt }}"
     @if($width) width="{{ $width }}" @endif
     @if($height) height="{{ $height }}" @endif
     class="{{ $class }}"
     loading="{{ $eager ? 'eager' : $loading }}"
     decoding="async"
     onerror="if(this.dataset.fallbackApplied !== '1' && '{{ $fallbackSrc }}') { this.dataset.fallbackApplied='1'; this.src='{{ $fallbackSrc }}'; }"
     {{ $attributes }}>
