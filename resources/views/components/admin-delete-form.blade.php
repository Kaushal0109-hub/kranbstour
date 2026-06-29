@props([
    'action',
    'label' => 'Delete',
    'confirm' => 'Delete this item? This cannot be undone.',
])

<form action="{{ $action }}" method="POST" {{ $attributes->merge(['class' => 'inline']) }} onsubmit="return confirm(@js($confirm))">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-500 text-xs font-bold hover:text-red-600 hover:underline">
        {{ $label }}
    </button>
</form>
