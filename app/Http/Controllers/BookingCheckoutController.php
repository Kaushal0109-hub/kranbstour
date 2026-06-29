<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use App\Models\Booking;
use App\Models\PaymentGateway;
use App\Services\Payment\PaymentGatewayManager;
use App\Services\TourCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingCheckoutController extends Controller
{
    public function __construct(private PaymentGatewayManager $gateways) {}

    public function create(string $category, string $packageSlug): View
    {
        $data = TourCatalog::findPackage($category, $packageSlug);
        abort_unless($data, 404);

        $activeGateways = $this->gateways->active();

        return view('bookings.checkout', [
            'category' => $data['category'],
            'package' => $data['package'],
            'categorySlug' => $category,
            'activeGateways' => $activeGateways,
            'onlinePaymentAvailable' => $activeGateways->isNotEmpty(),
            'mapsApiKey' => config('site.maps.google_api_key'),
            'pickupCity' => $data['category']['city'] ?? $data['category']['name'] ?? 'India',
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'category_slug' => ['required', 'string'],
            'package_slug' => ['required', 'string'],
            'customer_first_name' => ['required', 'string', 'max:60'],
            'customer_last_name' => ['required', 'string', 'max:60'],
            'customer_email' => ['required', 'email', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:80'],
            'travel_date' => ['required', 'date', 'after_or_equal:today'],
            'travelers' => ['required', 'integer', 'min:1', 'max:50'],
            'pickup_preference' => ['required', 'in:operator,location'],
            'pickup_location' => ['nullable', 'required_if:pickup_preference,location', 'string', 'max:255'],
            'pickup_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'pickup_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_option' => ['required', 'in:arrival,deposit,full'],
            'payment_gateway' => ['nullable', 'string', 'in:paypal,stripe,razorpay'],
        ]);

        $customerName = trim($validated['customer_first_name'].' '.$validated['customer_last_name']);
        $notesParts = [];
        if ($validated['country']) {
            $notesParts[] = 'Country: '.$validated['country'];
        }
        if (! empty($validated['notes'])) {
            $notesParts[] = $validated['notes'];
        }
        $notes = $notesParts ? implode("\n", $notesParts) : null;
        $pickupLocation = $validated['pickup_preference'] === 'operator'
            ? 'Contact tour operator (details in voucher)'
            : $validated['pickup_location'];

        $packageData = TourCatalog::findPackage($validated['category_slug'], $validated['package_slug']);
        abort_unless($packageData, 404);

        $category = $packageData['category'];
        $package = $packageData['package'];
        $unitPrice = CurrencyHelper::parseNumeric($package['price']);
        $totalAmount = round($unitPrice * $validated['travelers'], 2);

        $amountDue = match ($validated['payment_option']) {
            'deposit' => round($totalAmount * 0.30, 2),
            'full' => $totalAmount,
            default => $totalAmount,
        };

        $paymentStatus = $validated['payment_option'] === 'arrival' ? 'pay_on_arrival' : 'pending';

        $booking = Booking::create([
            'booking_ref' => $this->generateBookingRef(),
            'user_id' => Auth::id(),
            'customer_name' => $customerName,
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'package_id' => $packageData['package_model']->id ?? $package['id'] ?? null,
            'category_slug' => $validated['category_slug'],
            'package_slug' => $validated['package_slug'],
            'package_title' => $package['title'],
            'city' => $category['city'],
            'price' => CurrencyHelper::formatAmount($unitPrice),
            'travel_date' => $validated['travel_date'],
            'travelers' => $validated['travelers'],
            'status' => $validated['payment_option'] === 'arrival' ? 'confirmed' : 'pending',
            'notes' => $notes,
            'pickup_location' => $pickupLocation,
            'pickup_latitude' => $validated['pickup_latitude'] ?? null,
            'pickup_longitude' => $validated['pickup_longitude'] ?? null,
            'payment_option' => $validated['payment_option'],
            'payment_status' => $paymentStatus,
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,
            'amount_paid' => 0,
            'amount_due' => $amountDue,
            'currency' => config('site.currency.code', 'USD'),
        ]);

        if ($validated['payment_option'] === 'arrival') {
            if ($request->expectsJson()) {
                return response()->json(['redirect' => route('bookings.success', $booking)]);
            }

            return redirect()->route('bookings.success', $booking);
        }

        $gatewaySlug = $validated['payment_gateway']
            ?? $this->gateways->active()->first()?->slug;

        $gateway = $gatewaySlug ? $this->gateways->findActive($gatewaySlug) : null;

        if (! $gateway) {
            $message = 'No payment gateway is active. Please choose Pay on Arrival or contact us.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->withErrors(['payment_gateway' => $message])->withInput();
        }

        $driver = $this->gateways->driver($gateway->slug);
        $payment = $driver->createPayment($gateway, $booking, $amountDue);

        $booking->update([
            'payment_gateway' => $gateway->slug,
            'gateway_order_id' => $payment['order_id'] ?? null,
            'paypal_order_id' => $gateway->slug === 'paypal' ? ($payment['order_id'] ?? null) : null,
        ]);

        return response()->json(array_merge($payment, [
            'booking_id' => $booking->id,
            'success_url' => route('bookings.success', $booking),
        ]));
    }

    public function confirmPayment(Request $request, Booking $booking): JsonResponse
    {
        if (in_array($booking->payment_status, ['partial', 'paid'], true)) {
            return response()->json(['redirect' => route('bookings.success', $booking)]);
        }

        abort_unless($booking->payment_gateway, 422, 'No payment gateway on this booking.');

        $gateway = $this->gateways->findActive($booking->payment_gateway)
            ?? PaymentGateway::query()->where('slug', $booking->payment_gateway)->firstOrFail();

        $driver = $this->gateways->driver($booking->payment_gateway);
        $result = $driver->confirmPayment($gateway, $booking, $request->all());

        if (! $result['success']) {
            return response()->json(['message' => $result['message'] ?? 'Payment failed.'], 422);
        }

        $paid = $result['amount'];
        $paymentStatus = $booking->payment_option === 'full' ? 'paid' : 'partial';
        $remaining = max(0, round($booking->total_amount - $paid, 2));

        $booking->update([
            'gateway_transaction_id' => $result['transaction_id'],
            'paypal_capture_id' => $booking->payment_gateway === 'paypal' ? $result['transaction_id'] : null,
            'amount_paid' => $paid,
            'amount_due' => $remaining,
            'payment_status' => $paymentStatus,
            'status' => 'confirmed',
        ]);

        return response()->json(['redirect' => route('bookings.success', $booking)]);
    }

    public function success(Booking $booking): View
    {
        return view('bookings.success', compact('booking'));
    }

    private function generateBookingRef(): string
    {
        do {
            $ref = 'KB-'.now()->format('ymd').'-'.strtoupper(Str::random(5));
        } while (Booking::query()->where('booking_ref', $ref)->exists());

        return $ref;
    }
}
