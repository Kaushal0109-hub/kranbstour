@props(['active' => true])

<span @class([
    'text-xs font-bold px-2 py-1 rounded-lg',
    'bg-emerald-50 text-emerald-700' => $active,
    'bg-slate-100 text-slate-500' => ! $active,
])>
    {{ $active ? 'Active' : 'Inactive' }}
</span>
