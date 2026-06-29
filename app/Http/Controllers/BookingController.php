<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use App\Models\Booking;
use App\Services\TourCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_slug' => ['required', 'string'],
            'package_slug' => ['required', 'string'],
            'travel_date' => ['nullable', 'date', 'after_or_equal:today'],
            'travelers' => ['required', 'integer', 'min:1', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $packageData = TourCatalog::findPackage($validated['category_slug'], $validated['package_slug']);
        abort_unless($packageData, 404);

        $category = $packageData['category'];
        $package = $packageData['package'];

        Booking::create([
            'user_id' => Auth::id(),
            'package_id' => $packageData['package_model']->id ?? $package['id'] ?? null,
            'category_slug' => $validated['category_slug'],
            'package_slug' => $validated['package_slug'],
            'package_title' => $package['title'],
            'city' => $category['city'],
            'price' => CurrencyHelper::formatAmount(null, $package['price']),
            'travel_date' => $validated['travel_date'] ?? null,
            'travelers' => $validated['travelers'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard.bookings')->with('success', 'Booking request submitted! We will confirm within 2 hours.');
    }
}
