<?php

namespace App\Services\Payment\Gateways;

use App\Contracts\PaymentGatewayDriver;
use App\Models\Booking;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class StripeGateway implements PaymentGatewayDriver
{
    public function slug(): string
    {
        return 'stripe';
    }

    public function isConfigured(PaymentGateway $gateway): bool
    {
        return filled($gateway->credential('publishable_key'))
            && filled($gateway->credential('secret_key'));
    }

    public function createPayment(PaymentGateway $gateway, Booking $booking, float $amount): array
    {
        $intent = $this->api($gateway)
            ->asForm()
            ->post('https://api.stripe.com/v1/payment_intents', [
                'amount' => $this->toMinorUnits($amount, $booking->currency),
                'currency' => strtolower($booking->currency),
                'description' => $booking->package_title,
                'metadata[booking_ref]' => $booking->booking_ref,
            ])
            ->throw()
            ->json();

        return [
            'gateway' => 'stripe',
            'order_id' => $intent['id'] ?? null,
            'client_secret' => $intent['client_secret'] ?? null,
            'publishable_key' => $gateway->credential('publishable_key'),
            'amount' => $amount,
            'currency' => $booking->currency,
        ];
    }

    public function confirmPayment(PaymentGateway $gateway, Booking $booking, array $payload): array
    {
        $intentId = $payload['payment_intent_id'] ?? $booking->gateway_order_id;

        $intent = $this->api($gateway)
            ->get("https://api.stripe.com/v1/payment_intents/{$intentId}")
            ->throw()
            ->json();

        if (($intent['status'] ?? '') !== 'succeeded') {
            return ['success' => false, 'amount' => 0, 'transaction_id' => null, 'message' => 'Payment not completed.'];
        }

        return [
            'success' => true,
            'amount' => ($intent['amount_received'] ?? 0) / 100,
            'transaction_id' => $intent['id'] ?? null,
        ];
    }

    private function api(PaymentGateway $gateway)
    {
        return Http::withBasicAuth($gateway->credential('secret_key'), '');
    }

    private function toMinorUnits(float $amount, string $currency): int
    {
        $zeroDecimal = in_array(strtoupper($currency), ['JPY', 'KRW', 'VND'], true);

        return $zeroDecimal ? (int) round($amount) : (int) round($amount * 100);
    }
}
