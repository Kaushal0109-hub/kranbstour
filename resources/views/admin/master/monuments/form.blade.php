@extends('layouts.admin')

@section('title', ($monument->exists ? 'Edit' : 'Add').' Monument')
@section('heading', ($monument->exists ? 'Edit' : 'Add').' Monument')

@section('content')
    <form action="{{ $monument->exists ? route('admin.master.monuments.update', $monument) : route('admin.master.monuments.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6">
        @csrf
        @if ($monument->exists) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-100 p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Category *</label>
                <select name="category_id" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $monument->category_id) == $cat->id)>{{ $cat->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Name *</label>
                <input name="name" value="{{ old('name', $monument->name) }}" required class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200">{{ old('description', $monument->description) }}</textarea>
            </div>
            <div>
                <x-admin-image-field name="image" label="Monument Image" :value="old('image', $monument->image)" size="lg" />
            </div>
            <div>
                <label class="block text-xs font-bold text-ink-muted mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $monument->sort_order ?? 0) }}" class="w-full px-3 py-2 rounded-xl border border-slate-200">
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="px-5 py-2.5 bg-brand text-white font-bold rounded-xl">Save</button>
            <a href="{{ route('admin.master.monuments.index') }}" class="px-5 py-2.5 border border-slate-200 rounded-xl font-semibold">Cancel</a>
        </div>
    </form>

    @if ($monument->exists)
        <div class="flex justify-end mt-4">
            <x-admin-delete-form :action="route('admin.master.monuments.destroy', $monument)" label="Delete Monument" />
        </div>
    @endif
@endsection
