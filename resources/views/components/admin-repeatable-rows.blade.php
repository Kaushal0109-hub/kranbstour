@props([
    'name',
    'label',
    'type' => 'single',
    'values' => [],
    'placeholder' => '',
    'firstKey' => 'title',
    'secondKey' => 'description',
    'firstPlaceholder' => 'Title',
    'secondPlaceholder' => 'Description',
    'addLabel' => 'Add more',
])

@php
    $rows = old($name, $values);
    if (! is_array($rows)) {
        $rows = [];
    }
    if ($type === 'pair' && isset($rows[$firstKey]) && is_array($rows[$firstKey])) {
        $first = $rows[$firstKey] ?? [];
        $second = $rows[$secondKey] ?? [];
        $rows = [];
        $count = max(count($first), count($second));
        for ($i = 0; $i < $count; $i++) {
            $rows[] = [
                $firstKey => $first[$i] ?? '',
                $secondKey => $second[$i] ?? '',
            ];
        }
    }
    if ($type === 'single' && empty($rows)) {
        $rows = [''];
    }
    if ($type === 'pair' && empty($rows)) {
        $rows = [[$firstKey => '', $secondKey => '']];
    }
@endphp

<div class="admin-repeatable" data-repeatable-type="{{ $type }}">
    <div class="flex items-center justify-between gap-3 mb-2">
        <label class="block text-xs font-bold text-ink-muted">{{ $label }}</label>
        <button type="button"
                class="admin-repeatable-add text-xs font-bold text-brand hover:text-brand-700"
                data-repeatable-add="{{ $name }}">
            + {{ $addLabel }}
        </button>
    </div>

    <div class="space-y-2" data-repeatable-list="{{ $name }}">
        @if ($type === 'single')
            @foreach ($rows as $value)
                <div class="admin-repeatable-row flex items-center gap-2" data-repeatable-row>
                    <input type="text"
                           name="{{ $name }}[]"
                           value="{{ $value }}"
                           placeholder="{{ $placeholder }}"
                           class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm">
                    <button type="button"
                            class="admin-repeatable-remove shrink-0 w-9 h-9 rounded-lg border border-red-100 text-red-500 hover:bg-red-50"
                            data-repeatable-remove
                            title="Remove"
                            aria-label="Remove row">
                        <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                    </button>
                </div>
            @endforeach
        @else
            @foreach ($rows as $row)
                <div class="admin-repeatable-row flex flex-col sm:flex-row items-stretch sm:items-center gap-2" data-repeatable-row>
                    <input type="text"
                           name="{{ $name }}[{{ $firstKey }}][]"
                           value="{{ $row[$firstKey] ?? '' }}"
                           placeholder="{{ $firstPlaceholder }}"
                           class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm">
                    <input type="text"
                           name="{{ $name }}[{{ $secondKey }}][]"
                           value="{{ $row[$secondKey] ?? '' }}"
                           placeholder="{{ $secondPlaceholder }}"
                           class="flex-[1.5] px-3 py-2 rounded-xl border border-slate-200 text-sm">
                    <button type="button"
                            class="admin-repeatable-remove shrink-0 w-9 h-9 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 self-end sm:self-auto"
                            data-repeatable-remove
                            title="Remove"
                            aria-label="Remove row">
                        <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                    </button>
                </div>
            @endforeach
        @endif
    </div>

    <template data-repeatable-template="{{ $name }}">
        @if ($type === 'single')
            <div class="admin-repeatable-row flex items-center gap-2" data-repeatable-row>
                <input type="text"
                       name="{{ $name }}[]"
                       value=""
                       placeholder="{{ $placeholder }}"
                       class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm">
                <button type="button"
                        class="admin-repeatable-remove shrink-0 w-9 h-9 rounded-lg border border-red-100 text-red-500 hover:bg-red-50"
                        data-repeatable-remove
                        title="Remove"
                        aria-label="Remove row">
                    <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                </button>
            </div>
        @else
            <div class="admin-repeatable-row flex flex-col sm:flex-row items-stretch sm:items-center gap-2" data-repeatable-row>
                <input type="text"
                       name="{{ $name }}[{{ $firstKey }}][]"
                       value=""
                       placeholder="{{ $firstPlaceholder }}"
                       class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm">
                <input type="text"
                       name="{{ $name }}[{{ $secondKey }}][]"
                       value=""
                       placeholder="{{ $secondPlaceholder }}"
                       class="flex-[1.5] px-3 py-2 rounded-xl border border-slate-200 text-sm">
                <button type="button"
                        class="admin-repeatable-remove shrink-0 w-9 h-9 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 self-end sm:self-auto"
                        data-repeatable-remove
                        title="Remove"
                        aria-label="Remove row">
                    <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                </button>
            </div>
        @endif
    </template>
</div>
