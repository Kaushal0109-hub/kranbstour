<?php

namespace App\Services\Payment\Gateways;

use App\Contracts\PaymentGatewayDriver;
use App\Models\Booking;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class RazorpayGateway implements PaymentGatewayDriver
{
    public function slug(): string
    {
        return 'razorpay';
    }

    public function isConfigured(PaymentGateway $gateway): bool
    {
        return filled($gateway->credential('key_id'))
            && filled($gateway->credential('key_secret'));
    }

    public function createPayment(PaymentGateway $gateway, Booking $booking, float $amount): array
    {
        $order = $this->api($gateway)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => $this->toMinorUnits($amount, $booking->currency),
                'currency' => strtoupper($booking->currency),
                'receipt' => $booking->booking_ref,
                'notes' => [
                    'booking_ref' => $booking->booking_ref,
                    'package' => $booking->package_title,
                ],
            ])
            ->throw()
            ->json();

        return [
            'gateway' => 'razorpay',
            'order_id' => $order['id'] ?? null,
            'key_id' => $gateway->credential('key_id'),
            'amount' => $amount,
            'currency' => $booking->currency,
            'customer_name' => $booking->customer_name,
            'customer_email' => $booking->customer_email,
            'customer_phone' => $booking->customer_phone,
        ];
    }

    public function confirmPayment(PaymentGateway $gateway, Booking $booking, array $payload): array
    {
        $orderId = $payload['razorpay_order_id'] ?? $booking->gateway_order_id;
        $paymentId = $payload['razorpay_payment_id'] ?? null;
        $signature = $payload['razorpay_signature'] ?? null;

        if (! $paymentId || ! $signature) {
            return ['success' => false, 'amount' => 0, 'transaction_id' => null, 'message' => 'Invalid payment data.'];
        }

        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, $gateway->credential('key_secret'));

        if (! hash_equals($expected, $signature)) {
            return ['success' => false, 'amount' => 0, 'transaction_id' => null, 'message' => 'Payment verification failed.'];
        }

        $payment = $this->api($gateway)
            ->get("https://api.razorpay.com/v1/payments/{$paymentId}")
            ->throw()
            ->json();

        if (($payment['status'] ?? '') !== 'captured' && ($payment['status'] ?? '') !== 'authorized') {
            return ['success' => false, 'amount' => 0, 'transaction_id' => null, 'message' => 'Payment not completed.'];
        }

        return [
            'success' => true,
            'amount' => ($payment['amount'] ?? 0) / 100,
            'transaction_id' => $paymentId,
        ];
    }

    private function api(PaymentGateway $gateway)
    {
        return Http::withBasicAuth($gateway->credential('key_id'), $gateway->credential('key_secret'));
    }

    private function toMinorUnits(float $amount, string $currency): int
    {
        $zeroDecimal = in_array(strtoupper($currency), ['JPY'], true);

        return $zeroDecimal ? (int) round($amount) : (int) round($amount * 100);
    }
}
