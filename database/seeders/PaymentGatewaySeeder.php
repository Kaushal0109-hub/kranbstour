<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'paypal' => ['name' => 'PayPal', 'description' => 'Accept payments worldwide via PayPal.', 'sort_order' => 1],
            'stripe' => ['name' => 'Stripe', 'description' => 'Cards, Apple Pay, Google Pay and more.', 'sort_order' => 2],
            'razorpay' => ['name' => 'Razorpay', 'description' => 'UPI, cards and wallets — popular in India.', 'sort_order' => 3],
        ];

        $legacyPaypal = [
            'client_id' => SiteSetting::get('paypal_client_id'),
            'client_secret' => SiteSetting::get('paypal_client_secret'),
        ];
        $legacyActive = filter_var(SiteSetting::get('paypal_enabled', '0'), FILTER_VALIDATE_BOOLEAN);
        $legacyTest = SiteSetting::get('paypal_mode', 'sandbox') !== 'live';

        foreach ($defaults as $slug => $data) {
            $credentials = null;
            $isActive = false;
            $isTest = true;

            if ($slug === 'paypal' && filled($legacyPaypal['client_id'])) {
                $credentials = array_filter($legacyPaypal);
                $isActive = $legacyActive;
                $isTest = $legacyTest;
            }

            PaymentGateway::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'is_active' => $isActive,
                    'is_test_mode' => $isTest,
                    'credentials' => $credentials,
                ])
            );
        }
    }
}
