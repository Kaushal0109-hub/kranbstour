<?php

return [

    'gateways' => [
        'paypal' => [
            'name' => 'PayPal',
            'description' => 'Accept payments worldwide via PayPal.',
            'icon' => 'fab fa-paypal',
            'brand_color' => '#003087',
            'regions' => 'International',
            'docs_url' => 'https://developer.paypal.com/dashboard/applications',
            'fields' => [
                'client_id' => ['label' => 'Client ID', 'type' => 'text', 'required' => true],
                'client_secret' => ['label' => 'Client Secret', 'type' => 'password', 'required' => true],
            ],
        ],
        'stripe' => [
            'name' => 'Stripe',
            'description' => 'Cards, Apple Pay, Google Pay and more.',
            'icon' => 'fab fa-stripe',
            'brand_color' => '#635BFF',
            'regions' => 'International',
            'docs_url' => 'https://dashboard.stripe.com/apikeys',
            'fields' => [
                'publishable_key' => ['label' => 'Publishable Key', 'type' => 'text', 'required' => true],
                'secret_key' => ['label' => 'Secret Key', 'type' => 'password', 'required' => true],
            ],
        ],
        'razorpay' => [
            'name' => 'Razorpay',
            'description' => 'UPI, cards and wallets — popular in India.',
            'icon' => 'fas fa-indian-rupee-sign',
            'brand_color' => '#0C2451',
            'regions' => 'India & international cards',
            'docs_url' => 'https://dashboard.razorpay.com/app/keys',
            'fields' => [
                'key_id' => ['label' => 'Key ID', 'type' => 'text', 'required' => true],
                'key_secret' => ['label' => 'Key Secret', 'type' => 'password', 'required' => true],
            ],
        ],
    ],

];
