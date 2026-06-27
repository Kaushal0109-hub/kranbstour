<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    private array $pages = [
        'taj-mahal' => ['title' => 'Taj Mahal Tours', 'heading' => 'Taj Mahal Tours'],
        'jaipur' => ['title' => 'Jaipur Tours', 'heading' => 'Jaipur Tours'],
        'delhi' => ['title' => 'New Delhi Tours', 'heading' => 'New Delhi Tours'],
        'golden-triangle' => ['title' => 'Golden Triangle Tours', 'heading' => 'Golden Triangle Tours'],
        'varanasi' => ['title' => 'Varanasi Tours', 'heading' => 'Varanasi Tours'],
        'packages' => ['title' => 'Tour Packages', 'heading' => 'Tour Packages'],
        'taxi' => ['title' => 'Taxi Service & Transfers', 'heading' => 'Taxi Service & Transfers'],
        'contact' => ['title' => 'Contact Us', 'heading' => 'Contact Us'],
        'about' => ['title' => 'About Us', 'heading' => 'About Us'],
        'blog' => ['title' => 'Blog', 'heading' => 'Blog'],
        'awards' => ['title' => 'Our Awards', 'heading' => 'Our Awards'],
        'terms' => ['title' => 'Terms of Service', 'heading' => 'Terms of Service'],
        'privacy' => ['title' => 'Privacy Policy', 'heading' => 'Privacy Policy'],
        'login' => ['title' => 'Login', 'heading' => 'Login'],
    ];

    public function tour(string $category): View
    {
        abort_unless(isset($this->pages[$category]), 404);

        return view('pages.placeholder', [
            'title' => $this->pages[$category]['title'],
            'heading' => $this->pages[$category]['heading'],
        ]);
    }

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

        return back()->with('success', 'Thank you, ' . $validated['name'] . '! We received your enquiry and will reply within 2 hours.');
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
        abort_unless(isset($this->pages[$slug]), 404);

        return view('pages.placeholder', [
            'title' => $this->pages[$slug]['title'],
            'heading' => $this->pages[$slug]['heading'],
        ]);
    }

    public function search(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        return view('pages.search', compact('query'));
    }

    public function subscribeNewsletter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        return back()->with('success', 'Thank you for subscribing with ' . $validated['email'] . '!');
    }
}
