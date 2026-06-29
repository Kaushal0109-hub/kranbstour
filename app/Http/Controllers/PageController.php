<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Services\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(private CatalogService $catalog) {}

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['required', 'string', 'max:100'],
            'travel_date' => ['nullable', 'date', 'after_or_equal:today'],
            'travelers' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you, '.$validated['name'].'! We received your enquiry and will reply within 2 hours.');
    }

    public function destination(string $slug): View
    {
        $name = str($slug)->replace('-', ' ')->title();

        return view('pages.placeholder', [
            'title' => "{$name} Tours",
            'heading' => "{$name} Tours",
        ]);
    }

    public function page(string $slug): View
    {
        $cms = $this->catalog->cmsPage($slug);

        if ($cms) {
            return view('pages.cms', array_merge($cms, ['slug' => $slug]));
        }

        abort(404);
    }

    public function search(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $results = $this->catalog->search($query);

        return view('pages.search', [
            'query' => $query,
            'packages' => $results['packages'],
            'categories' => $results['categories'],
        ]);
    }

    public function subscribeNewsletter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        return back()->with('success', 'Thank you for subscribing with '.$validated['email'].'!');
    }
}
