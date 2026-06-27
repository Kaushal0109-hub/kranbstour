<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\TourCategory;
use App\Services\TourCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TourCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.master.categories.index', [
            'categories' => TourCategory::query()->with('city')->orderBy('sort_order')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.master.categories.form', [
            'category' => new TourCategory(),
            'cities' => City::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        TourCategory::create($this->validated($request));

        return redirect()->route('admin.master.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(TourCategory $category): View
    {
        return view('admin.master.categories.form', [
            'category' => $category,
            'cities' => City::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, TourCategory $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));

        return redirect()->route('admin.master.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(TourCategory $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    private function validated(Request $request, ?TourCategory $category = null): array
    {
        $data = $request->validate([
            'city_id' => ['nullable', 'exists:cities,id'],
            'key' => ['required', 'string', 'max:50'],
            'slug' => ['required', 'string', 'max:80', 'unique:tour_categories,slug,'.($category?->id ?? 'NULL')],
            'city_name' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:150'],
            'heading' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:150'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'banner_image' => ['nullable', 'string', 'max:255'],
            'card_image' => ['nullable', 'string', 'max:255'],
            'tour_count_label' => ['nullable', 'string', 'max:50'],
            'route_name' => ['nullable', 'string', 'max:80'],
            'map_query' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['route_name'] = $data['route_name'] ?: TourCatalog::routeForSlug($data['slug']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
