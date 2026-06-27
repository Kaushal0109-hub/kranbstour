<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
                'bookings' => Booking::count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'messages' => ContactMessage::where('is_read', false)->count(),
            ],
            'recentBookings' => Booking::with('user')->latest()->limit(6)->get(),
            'recentMessages' => ContactMessage::latest()->limit(5)->get(),
        ]);
    }

    public function bookings(): View
    {
        return view('admin.bookings', [
            'bookings' => Booking::with('user')->latest()->paginate(15),
        ]);
    }

    public function updateBookingStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Booking status updated.');
    }

    public function customers(): View
    {
        return view('admin.customers', [
            'customers' => User::where('role', User::ROLE_CUSTOMER)->latest()->paginate(15),
        ]);
    }

    public function messages(): View
    {
        return view('admin.messages', [
            'messages' => ContactMessage::latest()->paginate(15),
        ]);
    }

    public function markMessageRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read.');
    }
}
