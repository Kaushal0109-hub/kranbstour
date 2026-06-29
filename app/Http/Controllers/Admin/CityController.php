<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    use HandlesImageUploads;
    public function index(): View
    {
        return view('admin.master.cities.index', [
            'cities' => City::query()->orderBy('sort_order')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.master.cities.form', ['city' => new City()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        City::create($data);

        return redirect()->route('admin.master.cities.index')->with('success', 'City created successfully.');
    }

    public function edit(City $city): View
    {
        return view('admin.master.cities.form', compact('city'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $city->update($this->validated($request, $city));

        return redirect()->route('admin.master.cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();

        return back()->with('success', 'City deleted.');
    }

    private function validated(Request $request, ?City $city = null): array
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:50', 'unique:cities,key,'.($city?->id ?? 'NULL')],
            'slug' => ['required', 'string', 'max:80', 'unique:cities,slug,'.($city?->id ?? 'NULL')],
            'name' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:150'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'home_highlights' => ['nullable', 'string'],
            'tour_count_label' => ['nullable', 'string', 'max:50'],
            'card_image' => ['nullable', 'string', 'max:255'],
            'banner_image' => ['nullable', 'string', 'max:255'],
            'route_name' => ['nullable', 'string', 'max:80'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_spotlight' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['home_highlights'] = array_values(array_filter(array_map('trim', explode("\n", $data['home_highlights'] ?? ''))));
        $data['is_spotlight'] = $request->boolean('is_spotlight');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $this->mergeUploadedImages($request, $data, ['card_image', 'banner_image'], 'cities', $city);
    }
}
