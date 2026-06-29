@extends('layouts.admin')

@section('title', 'Homepage CMS')
@section('heading', 'Homepage Content')

@section('content')
    <div class="space-y-10">
        {{-- Hero preview --}}
        @if ($hero)
            <section class="bg-white rounded-2xl border border-slate-100 p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <h2 class="text-lg font-bold">Hero Section</h2>
                    <a href="{{ route('admin.master.settings.edit') }}" class="text-sm text-brand font-semibold hover:underline">Edit in Settings →</a>
                </div>
                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-bold text-ink-muted mb-2">Background Image Preview</p>
                        <x-admin-image-thumb :src="$hero->background_image" alt="Hero background" size="lg" class="!w-full !max-w-xs !h-32 !rounded-xl" />
                        <p class="text-[10px] text-ink-muted mt-2">{{ $hero->background_image }}</p>
                    </div>
                    <div class="text-sm space-y-2">
                        <p><span class="font-bold text-ink-muted">Badge:</span> {{ $hero->badge_text }}</p>
                        <p><span class="font-bold text-ink-muted">Heading:</span> {{ $hero->heading_line1 }} {{ $hero->heading_line2 }}</p>
                        <p><span class="font-bold text-ink-muted">Subtitle:</span> {{ Str::limit($hero->subtitle, 120) }}</p>
                    </div>
                </div>
            </section>
        @endif

        {{-- Stats --}}
        <section>
            <h2 class="text-lg font-bold mb-4">Stats Bar</h2>
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden mb-4">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($stats as $stat)
                            <tr>
                                <td class="px-5 py-3 font-bold">{{ $stat->value }}</td>
                                <td class="px-5 py-3">{{ $stat->label }}</td>
                                <td class="px-5 py-3 text-right">
                                    <form action="{{ route('admin.master.homepage.stats.destroy', $stat) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 text-xs font-bold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <form action="{{ route('admin.master.homepage.stats.store') }}" method="POST" class="flex flex-wrap gap-2 items-end bg-surface p-4 rounded-xl">
                @csrf
                <input name="value" placeholder="Value" required class="px-3 py-2 rounded-lg border border-slate-200 text-sm">
                <input name="label" placeholder="Label" required class="px-3 py-2 rounded-lg border border-slate-200 text-sm flex-1 min-w-[140px]">
                <input type="number" name="sort_order" placeholder="Order" class="w-20 px-3 py-2 rounded-lg border border-slate-200 text-sm">
                <button class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-lg">Add Stat</button>
            </form>
        </section>

        {{-- Highlights --}}
        <section>
            <h2 class="text-lg font-bold mb-4">Why Choose Us</h2>
            @foreach ($highlights as $highlight)
                <form action="{{ route('admin.master.homepage.highlights.update', $highlight) }}" method="POST" class="bg-white rounded-xl border border-slate-100 p-4 mb-3 grid sm:grid-cols-4 gap-2">
                    @csrf @method('PUT')
                    <input name="icon" value="{{ $highlight->icon }}" class="px-2 py-1.5 rounded-lg border text-sm">
                    <input name="title" value="{{ $highlight->title }}" class="px-2 py-1.5 rounded-lg border text-sm">
                    <input name="text" value="{{ $highlight->text }}" class="px-2 py-1.5 rounded-lg border text-sm sm:col-span-2">
                    <button class="px-3 py-1.5 bg-brand text-white text-xs font-bold rounded-lg sm:col-span-4 w-fit">Update</button>
                </form>
            @endforeach
            <form action="{{ route('admin.master.homepage.highlights.store') }}" method="POST" class="flex flex-wrap gap-2 bg-surface p-4 rounded-xl">
                @csrf
                <input name="icon" placeholder="fa-star" required class="px-3 py-2 rounded-lg border text-sm w-28">
                <input name="title" placeholder="Title" required class="px-3 py-2 rounded-lg border text-sm">
                <input name="text" placeholder="Description" required class="px-3 py-2 rounded-lg border text-sm flex-1 min-w-[200px]">
                <button class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-lg">Add</button>
            </form>
        </section>

        {{-- Testimonials --}}
        <section>
            <h2 class="text-lg font-bold mb-4">Testimonials</h2>
            @foreach ($testimonials as $t)
                <form action="{{ route('admin.master.homepage.testimonials.update', $t) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-100 p-4 mb-3 space-y-3">
                    @csrf @method('PUT')
                    <div class="flex flex-wrap gap-4 items-start">
                        <x-admin-image-thumb :src="$t->avatar_image" :alt="$t->reviewer_name" size="lg" />
                        <div class="flex-1 min-w-[200px] space-y-2">
                            <textarea name="quote" rows="2" class="w-full px-3 py-2 rounded-lg border text-sm">{{ $t->quote }}</textarea>
                            <div class="grid sm:grid-cols-3 gap-2">
                                <input name="reviewer_name" value="{{ $t->reviewer_name }}" class="px-3 py-2 rounded-lg border text-sm">
                                <input name="place" value="{{ $t->place }}" class="px-3 py-2 rounded-lg border text-sm">
                                <input name="city" value="{{ $t->city }}" class="px-3 py-2 rounded-lg border text-sm">
                            </div>
                            <x-admin-image-field name="avatar_image" label="Avatar Image" :value="$t->avatar_image" size="sm" />
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <label><input type="checkbox" name="show_on_home" value="1" @checked($t->show_on_home)> Home</label>
                        <label><input type="checkbox" name="show_on_package" value="1" @checked($t->show_on_package)> Package page</label>
                        <label><input type="checkbox" name="is_active" value="1" @checked($t->is_active)> Active</label>
                    </div>
                    <button class="px-3 py-1.5 bg-brand text-white text-xs font-bold rounded-lg">Update</button>
                </form>
            @endforeach
            <form action="{{ route('admin.master.homepage.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="bg-surface p-4 rounded-xl space-y-3">
                @csrf
                <textarea name="quote" rows="2" placeholder="Review quote" required class="w-full px-3 py-2 rounded-lg border text-sm"></textarea>
                <div class="grid sm:grid-cols-2 gap-2">
                    <input name="reviewer_name" placeholder="Name" required class="px-3 py-2 rounded-lg border text-sm">
                    <input name="place" placeholder="Country" class="px-3 py-2 rounded-lg border text-sm">
                </div>
                <x-admin-image-field name="avatar_image" label="Avatar Image" value="" placeholder="cities/avatar-1.jpg" size="sm" />
                <label class="text-sm"><input type="checkbox" name="show_on_home" value="1"> Show on home</label>
                <button class="px-4 py-2 bg-brand text-white text-sm font-bold rounded-lg">Add Testimonial</button>
            </form>
        </section>
    </div>
@endsection
