@extends('layouts.admin')

@section('title', 'Messages — Admin')
@section('heading', 'Contact Messages')

@section('content')
    <div class="space-y-4">
        @forelse ($messages as $msg)
            <article @class([
                'bg-white rounded-2xl border shadow-soft p-5 sm:p-6',
                'border-slate-100' => $msg->is_read,
                'border-brand/30 ring-1 ring-brand/10' => ! $msg->is_read,
            ])>
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                    <div>
                        <p class="font-extrabold text-ink">{{ $msg->name }}</p>
                        <p class="text-xs text-ink-muted">{{ $msg->email }} @if($msg->phone)· {{ $msg->phone }}@endif</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-ink-muted">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                        @if (! $msg->is_read)
                            <form action="{{ route('admin.messages.read', $msg) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs font-bold text-brand hover:text-brand-700">Mark read</button>
                            </form>
                        @endif
                    </div>
                </div>
                @if ($msg->city)
                    <p class="text-xs font-semibold text-brand mb-2">Interest: {{ $msg->city }}</p>
                @endif
                <p class="text-sm text-ink-muted leading-relaxed">{{ $msg->message }}</p>
            </article>
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center text-ink-muted">No messages yet.</div>
        @endforelse
    </div>
    @if ($messages->hasPages())
        <div class="mt-6">{{ $messages->links() }}</div>
    @endif
@endsection
