<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Monument;
use App\Models\TourCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Monument::query()->with('category')->orderBy('sort_order');

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        return view('admin.master.monuments.index', [
            'monuments' => $query->paginate(20)->withQueryString(),
            'categories' => TourCategory::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.master.monuments.form', [
            'monument' => new Monument(),
            'categories' => TourCategory::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Monument::create($this->validated($request));

        return redirect()->route('admin.master.monuments.index')->with('success', 'Monument created successfully.');
    }

    public function edit(Monument $monument): View
    {
        return view('admin.master.monuments.form', [
            'monument' => $monument,
            'categories' => TourCategory::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Monument $monument): RedirectResponse
    {
        $monument->update($this->validated($request));

        return redirect()->route('admin.master.monuments.index')->with('success', 'Monument updated successfully.');
    }

    public function destroy(Monument $monument): RedirectResponse
    {
        $monument->delete();

        return back()->with('success', 'Monument deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:tour_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
