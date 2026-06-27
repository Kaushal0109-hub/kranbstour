<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('dashboard.index', [
            'bookings' => $user->bookings()->latest()->limit(5)->get(),
            'stats' => [
                'total' => $user->bookings()->count(),
                'pending' => $user->bookings()->where('status', 'pending')->count(),
                'confirmed' => $user->bookings()->where('status', 'confirmed')->count(),
            ],
        ]);
    }

    public function bookings(): View
    {
        return view('dashboard.bookings', [
            'bookings' => Auth::user()->bookings()->latest()->paginate(10),
        ]);
    }

    public function profile(): View
    {
        return view('dashboard.profile', [
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
