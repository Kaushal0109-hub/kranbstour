@extends('layouts.admin')

@section('title', 'Customers — Admin')
@section('heading', 'Customers')

@section('content')
    <div class="bg-white rounded-2xl border border-slate-100 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left">
                    <tr>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Name</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Email</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Phone</th>
                        <th class="px-5 py-3 text-xs font-bold text-ink-muted uppercase">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $customer)
                        <tr>
                            <td class="px-5 py-4 font-bold text-ink">{{ $customer->name }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $customer->email }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $customer->phone ?? '—' }}</td>
                            <td class="px-5 py-4 text-ink-muted">{{ $customer->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-ink-muted">No customers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customers->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $customers->links() }}</div>
        @endif
    </div>
@endsection
