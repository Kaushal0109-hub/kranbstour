@props([
    'amount' => null,
    'display' => null,
    'suffix' => '/person',
    'layout' => 'inline',
    'showLabel' => true,
    'priceClass' => '',
    'suffixClass' => '',
])

@php
    use App\Helpers\CurrencyHelper;
    $formatted = CurrencyHelper::formatAmount($amount, $display);
    $label = CurrencyHelper::startingFromLabel();
@endphp

@if ($layout === 'stack')
    <div {{ $attributes }}>
        @if ($showLabel)
            <p class="text-xs text-ink-muted mb-1">{{ $label }}</p>
        @endif
        <p @class(['font-extrabold text-ink', $priceClass ?: 'text-3xl'])>{{ $formatted }}</p>
        @if ($suffix)
            <p @class(['text-ink-muted mt-0.5', $suffixClass ?: 'text-xs'])>{{ trim($suffix, ' /') }}</p>
        @endif
    </div>
@elseif ($layout === 'hero')
    <p {{ $attributes->merge(['class' => 'text-slate-300 text-xs mt-1']) }}>
        {{ $label }} {{ $formatted }}
    </p>
@else
    <div {{ $attributes->merge(['class' => '']) }}>
        @if ($showLabel)
            <p class="text-[11px] text-ink-muted font-medium mb-0.5 leading-tight">{{ $label }}</p>
        @endif
        <p class="leading-tight m-0">
            <span @class(['font-extrabold text-ink', $priceClass ?: 'text-xl'])>{{ $formatted }}</span>
            @if ($suffix)
                <span @class(['font-normal text-ink-muted', $suffixClass ?: 'text-xs'])> {{ $suffix }}</span>
            @endif
        </p>
    </div>
@endif
