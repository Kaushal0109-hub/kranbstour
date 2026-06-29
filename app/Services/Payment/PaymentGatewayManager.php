<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayDriver;
use App\Models\PaymentGateway;
use App\Services\Payment\Gateways\PayPalGateway;
use App\Services\Payment\Gateways\RazorpayGateway;
use App\Services\Payment\Gateways\StripeGateway;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class PaymentGatewayManager
{
    /** @var array<string, PaymentGatewayDriver> */
    private array $drivers = [];

    public function __construct()
    {
        $this->register(new PayPalGateway);
        $this->register(new StripeGateway);
        $this->register(new RazorpayGateway);
    }

    public function register(PaymentGatewayDriver $driver): void
    {
        $this->drivers[$driver->slug()] = $driver;
    }

    public function driver(string $slug): PaymentGatewayDriver
    {
        if (! isset($this->drivers[$slug])) {
            throw new InvalidArgumentException("Payment gateway [{$slug}] is not supported.");
        }

        return $this->drivers[$slug];
    }

    public function all(): Collection
    {
        return PaymentGateway::query()->orderBy('sort_order')->get();
    }

    public function active(): Collection
    {
        return $this->all()->filter(fn (PaymentGateway $g) => $g->isReady())->values();
    }

    public function isOnlinePaymentAvailable(): bool
    {
        return $this->active()->isNotEmpty();
    }

    public function findActive(string $slug): ?PaymentGateway
    {
        $gateway = PaymentGateway::query()->where('slug', $slug)->first();

        return ($gateway && $gateway->isReady()) ? $gateway : null;
    }
}
