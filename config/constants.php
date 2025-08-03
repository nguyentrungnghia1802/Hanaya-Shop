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

    // Chatbot messages
    'chatbot_greeting' =>
    "Hello! I'm Hanaya Shop's AI assistant. I can help you with:\n"
        . "🌸 Product search & recommendations\n"
        . "📝 Order tracking & status\n"
        . "📋 Product categories & collections\n"
        . "📰 Latest news & blog updates\n"
        . "💰 Pricing & payment information\n"
        . "🚚 Shipping & delivery details\n"
        . "🏪 Store information & contact\n"
        . "❓ Frequently asked questions\n\n"
        . "What can I help you with today?",
    'chatbot_help' =>
    "🤖 Hanaya Shop chatbot user guide:\n\n"
        . "🔍 Find products:\n"
        . "• 'find soap flowers'\n"
        . "• 'show me gifts'\n"
        . "• 'what's popular'\n"
        . "• 'recommend something'\n\n"
        . "📂 Browse categories:\n"
        . "• 'product categories'\n"
        . "• 'show categories'\n"
        . "• 'what do you sell'\n\n"
        . "📦 Check orders:\n"
        . "• 'my orders'\n"
        . "• 'order status'\n"
        . "• 'track my order'\n\n"
        . "📰 News & updates:\n"
        . "• 'latest news'\n"
        . "• 'blog posts'\n"
        . "• 'what's new'\n\n"
        . "🏪 Store information:\n"
        . "• 'store info'\n"
        . "• 'contact details'\n"
        . "• 'store hours'\n"
        . "• 'location'\n\n"
        . "💰 Pricing & payments:\n"
        . "• 'price range'\n"
        . "• 'payment methods'\n"
        . "• 'shipping costs'\n\n"
        . "Feel free to ask me anything in natural language!",
    'chatbot_store_info' =>
    "🏪 Hanaya Shop store information:\n\n"
        . "📍 Address: 123 Flower Street, Son Tay, Hanoi\n"
        . "📞 Phone: 0948512463\n"
        . "📧 Email: assassincreed2k1@gmail.com\n"
        . "🕒 Business hours: 8:00 AM - 10:00 PM (Monday - Sunday)\n\n"
        . "🚚 Delivery: Nationwide shipping available\n"
        . "💳 Payment: Cash, bank transfer, credit/debit cards\n"
        . "🎁 Special services: Gift wrapping, custom arrangements\n\n"
        . "Contact us now for personalized consultation!",
    'chatbot_status' => [
        'pending' => "Pending - Your order is being processed",
        'processing' => "Processing - We're preparing your order",
        'shipped' => "Shipped - Your order is on the way",
        'completed' => "Completed - Order delivered successfully",
        'cancelled' => "Cancelled - Order has been cancelled"
    ],
    'chatbot_price_info' =>
    "About our product prices:\n\n"
        . "🌸 Soap flowers: From $15 - $500\n"
        . "🎁 Souvenirs & gifts: From $5 - $800\n"
        . "💐 Fresh flowers: From $10 - $200\n"
        . "🎀 Custom arrangements: From $25 - $1000\n\n"
        . "Prices vary based on size, materials, design complexity, and customization.\n"
        . "💡 Free shipping on orders over $100!\n"
        . "🎊 Bulk order discounts available!\n\n"
        . "See detailed prices at: /products",
    'chatbot_shipping_info' =>
    "🚚 Shipping & delivery information:\n\n"
        . "📦 Free shipping on orders over $100\n"
        . "🚚 Standard delivery: 3-5 business days\n"
        . "⚡ Express delivery: 1-2 business days (+$15)\n"
        . "🏃 Same-day delivery: Available in Hanoi (+$25)\n\n"
        . "📍 Delivery areas: Nationwide coverage\n"
        . "📋 Tracking: Real-time order tracking available\n"
        . "📦 Packaging: Eco-friendly, secure packaging\n"
        . "🎁 Gift options: Free gift wrapping & cards\n\n"
        . "Questions about delivery? Contact us: 0948512463",
    'chatbot_payment_info' =>
    "💳 Payment methods & options:\n\n"
        . "💰 Cash on delivery (COD)\n"
        . "🏦 Bank transfer (instant confirmation)\n"
        . "💳 Credit/Debit cards (Visa, Mastercard)\n"
        . "📱 Digital wallets (Momo, ZaloPay)\n"
        . "💎 Installment plans available\n\n"
        . "🔒 Secure payments with SSL encryption\n"
        . "🎊 Special offers: 5% discount on bank transfers\n"
        . "💝 Loyalty points on every purchase\n\n"
        . "Need payment assistance? We're here to help!",
    'chatbot_default' =>
    "I understand you're looking for help! Here's what I can assist you with:\n\n"
        . "🔍 **Product search**: 'find soap flowers', 'show me gifts'\n"
        . "📂 **Categories**: 'what categories do you have'\n"
        . "📦 **Orders**: 'check my orders', 'track order'\n"
        . "📰 **News**: 'latest updates', 'blog posts'\n"
        . "🏪 **Store info**: 'contact details', 'store hours'\n"
        . "💰 **Pricing**: 'price range', 'payment methods'\n"
        . "🚚 **Shipping**: 'delivery options', 'shipping costs'\n\n"
        . "💡 **Quick tip**: Try asking questions naturally, like 'What flowers do you recommend for a birthday?' or 'How much does shipping cost?'\n\n"
        . "Type 'help' for detailed instructions or just ask me anything!",
        
];
