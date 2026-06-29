<?php

return [

    'name' => 'Kranbstour',
    'tagline' => 'Agra · Delhi · Jaipur · Varanasi Tours',
    'description' => 'Kranbstour — expert-led tours in Agra, Delhi, Jaipur & Varanasi. Taj Mahal trips, heritage walks, palace tours & Ganga aarti experiences with local guides.',
    'phone' => '+919634361164',
    'phone_display' => '+91-9634361164',
    'email' => 'info@kranbstour.com',
    'whatsapp' => '+919634361164',
    'logo' => [
        'default' => '/images/kranbstour-logo.svg',
        'white' => '/images/kranbstour-logo-white.svg',
        'icon' => '/images/favicon.svg',
    ],

    'cities' => ['agra', 'delhi', 'jaipur', 'varanasi'],

    'currency' => [
        'code' => 'USD',
        'symbol' => '$',
        'starting_from' => 'Starting from',
    ],

    'maps' => [
        'google_api_key' => env('GOOGLE_MAPS_API_KEY', ''),
    ],

    'paypal' => [
        'enabled' => false,
        'mode' => 'sandbox',
        'client_id' => '',
        'client_secret' => '',
    ],

    'images' => [
        'hero' => [
            'main' => [
                'url' => 'cities/hero-main.jpg',
                'alt' => 'Taj Mahal, Agra — Kranbstour',
            ],
            'agra' => [
                'url' => 'cities/agra-card.jpg',
                'alt' => 'Taj Mahal sunrise, Agra',
            ],
            'delhi' => [
                'url' => 'cities/delhi-card.jpg',
                'alt' => 'Humayun\'s Tomb, New Delhi',
            ],
            'jaipur' => [
                'url' => 'cities/jaipur-card.jpg',
                'alt' => 'Hawa Mahal, Jaipur',
            ],
            'varanasi' => [
                'url' => 'cities/varanasi-card.jpg',
                'alt' => 'Varanasi Ganges ghats',
            ],
        ],
        'cities' => [
            'agra' => [
                'card' => 'cities/agra-card.jpg',
                'banner' => 'cities/agra-banner.jpg',
                'alt' => 'Taj Mahal and Agra heritage tours',
            ],
            'delhi' => [
                'card' => 'cities/delhi-card.jpg',
                'banner' => 'cities/delhi-banner.jpg',
                'alt' => 'Humayun\'s Tomb and New Delhi heritage tours',
            ],
            'jaipur' => [
                'card' => 'cities/jaipur-card.jpg',
                'banner' => 'cities/jaipur-banner.jpg',
                'alt' => 'Jaipur forts and Pink City tours',
            ],
            'varanasi' => [
                'card' => 'cities/varanasi-card.jpg',
                'banner' => 'cities/varanasi-banner.jpg',
                'alt' => 'Varanasi spiritual Ganga ghats tours',
            ],
        ],
        'avatars' => [
            'cities/avatar-1.jpg',
            'cities/avatar-2.jpg',
            'cities/avatar-3.jpg',
        ],
        'fallback' => 'cities/fallback.jpg',
    ],

];
