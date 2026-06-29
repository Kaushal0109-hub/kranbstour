<?php

namespace App\Contracts;

use App\Models\Booking;
use App\Models\PaymentGateway;

interface PaymentGatewayDriver
{
    public function slug(): string;

    public function isConfigured(PaymentGateway $gateway): bool;

    /**
     * @return array<string, mixed>
     */
    public function createPayment(PaymentGateway $gateway, Booking $booking, float $amount): array;

    /**
     * @param  array<string, mixed>  $payload
     * @return array{success: bool, amount: float, transaction_id: ?string, message?: string}
     */
    public function confirmPayment(PaymentGateway $gateway, Booking $booking, array $payload): array;
}
