<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeHighlight;
use App\Models\HomeStat;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function index(): View
    {
        return view('admin.master.homepage.index', [
            'stats' => HomeStat::query()->orderBy('sort_order')->get(),
            'highlights' => HomeHighlight::query()->orderBy('sort_order')->get(),
            'testimonials' => Testimonial::query()->orderBy('sort_order')->get(),
        ]);
    }

    public function storeStat(Request $request): RedirectResponse
    {
        HomeStat::create($this->statData($request));

        return back()->with('success', 'Stat added.');
    }

    public function updateStat(Request $request, HomeStat $stat): RedirectResponse
    {
        $stat->update($this->statData($request));

        return back()->with('success', 'Stat updated.');
    }

    public function destroyStat(HomeStat $stat): RedirectResponse
    {
        $stat->delete();

        return back()->with('success', 'Stat deleted.');
    }

    public function storeHighlight(Request $request): RedirectResponse
    {
        HomeHighlight::create($this->highlightData($request));

        return back()->with('success', 'Highlight added.');
    }

    public function updateHighlight(Request $request, HomeHighlight $highlight): RedirectResponse
    {
        $highlight->update($this->highlightData($request));

        return back()->with('success', 'Highlight updated.');
    }

    public function destroyHighlight(HomeHighlight $highlight): RedirectResponse
    {
        $highlight->delete();

        return back()->with('success', 'Highlight deleted.');
    }

    public function storeTestimonial(Request $request): RedirectResponse
    {
        Testimonial::create($this->testimonialData($request));

        return back()->with('success', 'Testimonial added.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->testimonialData($request));

        return back()->with('success', 'Testimonial updated.');
    }

    public function destroyTestimonial(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted.');
    }

    private function statData(Request $request): array
    {
        $data = $request->validate([
            'value' => ['required', 'string', 'max:30'],
            'label' => ['required', 'string', 'max:80'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function highlightData(Request $request): array
    {
        $data = $request->validate([
            'icon' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:100'],
            'text' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function testimonialData(Request $request): array
    {
        $data = $request->validate([
            'quote' => ['required', 'string'],
            'reviewer_name' => ['required', 'string', 'max:100'],
            'place' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:80'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:50'],
            'avatar_image' => ['nullable', 'string', 'max:255'],
            'review_date_label' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['rating'] = $data['rating'] ?? 5;
        $data['show_on_home'] = $request->boolean('show_on_home');
        $data['show_on_package'] = $request->boolean('show_on_package');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
