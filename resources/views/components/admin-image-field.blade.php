@props([
    'name',
    'label',
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'size' => 'md',
    'accept' => 'image/jpeg,image/png,image/webp,image/gif,image/svg+xml',
])

@php
    use App\Helpers\MediaHelper;
    $resolved = $value ? MediaHelper::url($value) : '';
    $previewSize = match ($size) {
        'sm' => 'w-16 h-16',
        'lg' => 'w-44 h-32',
        'wide' => 'w-full max-w-xs h-28',
        default => 'w-28 h-20',
    };
    $uploadName = $name.'_upload';
@endphp

<div {{ $attributes->merge(['class' => 'admin-image-field']) }}>
    <label class="block text-xs font-bold text-ink-muted mb-1">{{ $label }}</label>

    <input type="hidden"
           name="{{ $name }}"
           value="{{ $value }}"
           class="admin-image-path"
           data-preview-for="{{ $name }}">

    <div class="flex flex-wrap items-center gap-3">
        <label for="admin-file-{{ $name }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 text-sm font-semibold text-ink hover:border-brand hover:text-brand hover:bg-brand-50 cursor-pointer transition-colors">
            <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
            Choose Image
        </label>
        <input type="file"
               id="admin-file-{{ $name }}"
               name="{{ $uploadName }}"
               accept="{{ $accept }}"
               class="sr-only admin-image-file"
               data-preview-for="{{ $name }}">
        @if ($value)
            <span class="text-[10px] text-ink-muted truncate max-w-[200px]" title="{{ $value }}">Saved: {{ $value }}</span>
        @endif
    </div>

    @if ($hint)
        <p class="text-[10px] text-ink-muted mt-1.5">{{ $hint }}</p>
    @else
        <p class="text-[10px] text-ink-muted mt-1.5">JPG, PNG, WebP, GIF or SVG — max 5MB. No URL needed.</p>
    @endif

    <div class="mt-2.5">
        <div @class([
            'admin-image-preview-wrap rounded-xl border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center',
            $previewSize,
        ]) data-preview-wrap="{{ $name }}">
            <img src="{{ $resolved }}"
                 alt="Preview"
                 class="admin-image-preview w-full h-full object-cover {{ $resolved ? '' : 'hidden' }}"
                 data-preview-img="{{ $name }}">
            <span @class([
                'admin-image-placeholder text-[10px] text-slate-400 text-center px-2 leading-tight',
                'hidden' => (bool) $resolved,
            ]) data-preview-placeholder="{{ $name }}">
                <i class="fas fa-image text-lg block mb-1 opacity-40" aria-hidden="true"></i>
                No image selected
            </span>
        </div>
    </div>
</div>
