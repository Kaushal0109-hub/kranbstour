@props([
    'src' => null,
    'alt' => '',
    'size' => 'sm',
])

@php
    use App\Helpers\MediaHelper;
    $url = $src ? MediaHelper::url($src) : null;
    $sizeClass = match ($size) {
        'md' => 'w-16 h-16',
        'lg' => 'w-20 h-20',
        default => 'w-12 h-12',
    };
@endphp

@if ($url)
    <img src="{{ $url }}" alt="{{ $alt }}"
         {{ $attributes->merge(['class' => "$sizeClass rounded-lg object-cover border border-slate-100 bg-slate-50 shrink-0 admin-image-thumb"]) }}
         onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'{{ $sizeClass }} rounded-lg border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center text-slate-300 shrink-0',innerHTML:'<i class=\'fas fa-image text-xs\'></i>'}))">
@else
    <div {{ $attributes->merge(['class' => "$sizeClass rounded-lg border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center text-slate-300 shrink-0"]) }}>
        <i class="fas fa-image text-xs" aria-hidden="true"></i>
    </div>
@endif
