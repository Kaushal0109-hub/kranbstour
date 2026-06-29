@props(['images' => [], 'title' => 'Gallery'])

@if (count($images))
    <div {{ $attributes->merge(['class' => 'mt-4']) }}>
        <p class="text-xs font-bold text-ink-muted mb-2">{{ $title }} ({{ count($images) }})</p>
        <div class="flex flex-wrap gap-2">
            @foreach ($images as $img)
                <div class="group relative">
                    <x-admin-image-thumb :src="$img->image ?? ($img['image'] ?? null)" :alt="$img->alt ?? ($img['alt'] ?? '')" size="lg" />
                    @if (!empty($img->alt) || !empty($img['alt']))
                        <span class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[9px] px-1 py-0.5 truncate opacity-0 group-hover:opacity-100 transition-opacity rounded-b-lg">
                            {{ $img->alt ?? $img['alt'] ?? '' }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
