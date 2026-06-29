<?php

namespace App\Services\Payment\Gateways;

use App\Contracts\PaymentGatewayDriver;
use App\Models\Booking;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class PayPalGateway implements PaymentGatewayDriver
{
    public function slug(): string
    {
        return 'paypal';
    }

    public function isConfigured(PaymentGateway $gateway): bool
    {
        return filled($gateway->credential('client_id'))
            && filled($gateway->credential('client_secret'));
    }

    public function createPayment(PaymentGateway $gateway, Booking $booking, float $amount): array
    {
        $order = $this->api($gateway)
            ->post($this->baseUrl($gateway).'/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $booking->booking_ref,
                    'description' => $booking->package_title,
                    'amount' => [
                        'currency_code' => strtoupper($booking->currency),
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'brand_name' => config('site.name', 'Kranbstour'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                ],
            ])
            ->throw()
            ->json();

        return [
            'gateway' => 'paypal',
            'order_id' => $order['id'] ?? null,
            'client_id' => $gateway->credential('client_id'),
            'amount' => $amount,
            'currency' => $booking->currency,
        ];
    }

    public function confirmPayment(PaymentGateway $gateway, Booking $booking, array $payload): array
    {
        $orderId = $payload['order_id'] ?? $booking->gateway_order_id;

        $capture = $this->api($gateway)
            ->withHeaders(['Prefer' => 'return=representation'])
            ->post($this->baseUrl($gateway)."/v2/checkout/orders/{$orderId}/capture")
            ->throw()
            ->json();

        if (($capture['status'] ?? '') !== 'COMPLETED') {
            return ['success' => false, 'amount' => 0, 'transaction_id' => null, 'message' => 'Payment not completed.'];
        }

        $unit = $capture['purchase_units'][0]['payments']['captures'][0] ?? null;

        return [
            'success' => true,
            'amount' => (float) ($unit['amount']['value'] ?? 0),
            'transaction_id' => $unit['id'] ?? null,
        ];
    }

    private function baseUrl(PaymentGateway $gateway): string
    {
        return $gateway->is_test_mode
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    private function api(PaymentGateway $gateway)
    {
        $token = Http::asForm()
            ->withBasicAuth($gateway->credential('client_id'), $gateway->credential('client_secret'))
            ->post($this->baseUrl($gateway).'/v1/oauth2/token', ['grant_type' => 'client_credentials'])
            ->throw()
            ->json('access_token');

        return Http::withToken($token);
    }
}
