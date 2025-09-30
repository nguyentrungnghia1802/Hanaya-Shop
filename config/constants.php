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
    'shop_email' => 'support@hanayashop.com',
    'shop_phone' => '0353295709',
    'shop_address' => '123 Flower Street, Son Tay, Hanoi',

    // Banner configuration
    'banners' => [
        [
            'image' => 'fixed_resources/banner_1.jpg',
            'title_key' => 'banners.welcome_title',
            'subtitle_key' => 'banners.welcome_subtitle',
            'button_text_key' => 'banners.explore_now',
            'button_link' => '/products',
        ],
        [
            'image' => 'fixed_resources/banner_2.jpg',
            'title_key' => 'banners.soap_flower_title',
            'subtitle_key' => 'banners.soap_flower_subtitle',
            'button_text_key' => 'banners.view_collection',
            'button_link' => '/products?category_name=soap-flower',
        ],
        [
            'image' => 'fixed_resources/banner_3.jpg',
            'title_key' => 'banners.special_gifts_title',
            'subtitle_key' => 'banners.special_gifts_subtitle',
            'button_text_key' => 'banners.find_gifts',
            'button_link' => '/products?category_name=souvenir',
        ],
    ],

    // Checkout
    'checkout' => [
        'shipping_fee' => 8, // Fixed shipping fee
    ],

    // Order status configuration
    'order_statuses' => [
        'pending' => 'pending',
        'processing' => 'processing',
        'shipped' => 'shipped',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
    ],

    // Review configuration
    'review' => [
        'can_review_status' => 'completed', // Status when user can review
        'max_rating' => 5,
        'min_rating' => 1,
        'default_rating' => 5,
    ],

    // Chatbot messages - now use translation keys
    'chatbot_greeting_key' => 'chatbot.greeting',
    'chatbot_help_key' => 'chatbot.help',
    'chatbot_store_info_key' => 'chatbot.store_info',
    'chatbot_status_keys' => [
        'pending' => 'chatbot.status.pending',
        'processing' => 'chatbot.status.processing',
        'shipped' => 'chatbot.status.shipped',
        'completed' => 'chatbot.status.completed',
        'cancelled' => 'chatbot.status.cancelled',
    ],
    'chatbot_price_info_key' => 'chatbot.price_info',
    'chatbot_shipping_info_key' => 'chatbot.shipping_info',
    'chatbot_payment_info_key' => 'chatbot.payment_info',
    'chatbot_default_key' => 'chatbot.default',

];
