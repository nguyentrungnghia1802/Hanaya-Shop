<?php

return [
    // Logo image path
    'logo_path' => 'fixed_resources/logo.png',

    // Favicon path
    'favicon_path' => 'favicon.ico',

    // Fixed resources folder path
    'fixed_resources_path' => 'fixed_resources/',

    // Shop information (can be used for footer, contact...)
    'shop_name' => 'HANAYA SHOP',
    'shop_email' => 'assassincreed2k1@gmail.com',
    'shop_phone' => '0948512463',
    'shop_address' => '123 Flower Street, Son Tay, Hanoi',

    // Banner configuration
    'banners' => [
        [
            'image' => 'fixed_resources/banner_1.jpg',
            'title' => 'Welcome to Hanaya Shop',
            'subtitle' => 'Where meaningful flowers and gifts come together',
            'button_text' => 'Explore Now',
            'button_link' => '/products'
        ],
        [
            'image' => 'fixed_resources/banner_2.jpg',
            'title' => 'Soap Flower Collection',
            'subtitle' => 'Eternal flowers with gentle fragrance',
            'button_text' => 'View Collection',
            'button_link' => '/products?category_name=soap-flower'
        ],
        [
            'image' => 'fixed_resources/banner_3.jpg',
            'title' => 'Special Gifts',
            'subtitle' => 'Meaningful gifts for your loved ones',
            'button_text' => 'Find Gifts',
            'button_link' => '/products?category_name=souvenir'
        ]
    ],

    //Checkout
    'checkout' => [
        'shipping_fee' => 8, // Fixed shipping fee
    ]
        
];
