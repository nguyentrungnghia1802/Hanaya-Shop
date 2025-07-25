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
    
    // Chatbot messages
    'chatbot_greeting' =>
        "🤖 **Hello! Welcome to Hanaya Shop!**\n\n"
        . "I'm your personal shopping assistant. I can help you with:\n\n"
        . "🔍 **Product Search** - Find perfect flowers & gifts\n"
        . "🏆 **Best Sellers** - See what's trending\n"
        . "🔥 **Sale Items** - Discover amazing deals\n"
        . "� **Categories** - Browse by product type\n"
        . "📦 **Your Orders** - Track order status\n"
        . "⭐ **Reviews** - Read customer feedback\n"
        . "📰 **Latest News** - Stay updated\n"
        . "🏪 **Store Info** - Contact & location\n"
        . "🚚 **Shipping** - Delivery information\n\n"
        . "💬 **Just ask me anything!** For example:\n"
        . "• 'Show me best sellers'\n"
        . "• 'Find soap flowers'\n"
        . "• 'What's on sale?'\n\n"
        . "How can I help you today? 😊",
    
    'chatbot_help' =>
        "🤖 **Hanaya Shop Chatbot - Complete Guide**\n\n"
        . "Here are all the ways I can assist you:\n\n"
        . "🔍 **Find Products:**\n"
        . "• 'find soap flowers' or 'search gifts'\n"
        . "• 'show me fresh flowers'\n"
        . "• 'souvenir products'\n\n"
        . "🏆 **Popular Items:**\n"
        . "• 'best sellers' or 'top products'\n"
        . "• 'what's trending'\n\n"
        . "� **Deals & Sales:**\n"
        . "• 'sale products' or 'discounts'\n"
        . "• 'what's on sale?'\n\n"
        . "📂 **Browse Categories:**\n"
        . "• 'product categories'\n"
        . "• 'show me categories'\n\n"
        . "📦 **Order Management:**\n"
        . "• 'my orders' or 'order status'\n"
        . "• 'track my order'\n\n"
        . "🛒 **Shopping Cart:**\n"
        . "• 'view my cart'\n"
        . "• 'shopping cart'\n\n"
        . "⭐ **Reviews & Ratings:**\n"
        . "• 'customer reviews'\n"
        . "• 'product ratings'\n\n"
        . "📰 **News & Updates:**\n"
        . "• 'latest news' or 'blog posts'\n"
        . "• 'what's new'\n\n"
        . "🏪 **Store Information:**\n"
        . "• 'store info' or 'contact'\n"
        . "• 'address' or 'phone number'\n\n"
        . "🚚 **Shipping & Delivery:**\n"
        . "• 'shipping info'\n"
        . "• 'delivery options'\n\n"
        . "💡 **Pro Tips:**\n"
        . "• You can ask in both English and Vietnamese\n"
        . "• Click on any links I provide for quick access\n"
        . "• I'm here 24/7 to help you!\n\n"
        . "Feel free to ask me anything! 😊",
        
    'chatbot_store_info' =>
        "🏪 **Hanaya Shop - Store Information**\n\n"
        . "**📍 Location:**\n"
        . "123 Flower Street, Son Tay, Hanoi, Vietnam\n\n"
        . "**📞 Contact:**\n"
        . "• Phone: 0948512463\n"
        . "• Email: assassincreed2k1@gmail.com\n\n"
        . "**🕒 Business Hours:**\n"
        . "• Monday - Sunday: 8:00 AM - 10:00 PM\n"
        . "• Online: 24/7 shopping available\n\n"
        . "**🚚 Services:**\n"
        . "• Nationwide delivery\n"
        . "• Same-day delivery (Hanoi area)\n"
        . "• Gift wrapping available\n"
        . "• Custom arrangements\n\n"
        . "**💳 Payment Methods:**\n"
        . "• Cash on delivery\n"
        . "• Bank transfer\n"
        . "• Credit/Debit cards\n"
        . "• Digital wallets\n\n"
        . "**🌸 Specialties:**\n"
        . "• Handcrafted soap flowers\n"
        . "• Fresh flower arrangements\n"
        . "• Unique gift collections\n"
        . "• Special occasion packages\n\n"
        . "📞 **Call us now for personalized consultation!**\n"
        . "🌸 [Start shopping](/products)",
        
    'chatbot_status' => [
        'pending' => "Pending confirmation",
        'processing' => "Being prepared",
        'completed' => "Successfully delivered",
        'cancelled' => "Cancelled"
    ],
    
    'chatbot_price_info' =>
        "💰 **Hanaya Shop - Pricing Information**\n\n"
        . "**🌸 Soap Flowers:**\n"
        . "• Single flowers: 15 - 50 USD\n"
        . "• Small bouquets: 50 - 150 USD\n"
        . "• Large arrangements: 150 - 500 USD\n\n"
        . "**🌹 Fresh Flowers:**\n"
        . "• Single stems: 5 - 25 USD\n"
        . "• Bouquets: 30 - 200 USD\n"
        . "• Premium arrangements: 200 - 800 USD\n\n"
        . "**🎁 Souvenirs & Gifts:**\n"
        . "• Small items: 5 - 30 USD\n"
        . "• Gift sets: 30 - 150 USD\n"
        . "• Premium collections: 150 - 500 USD\n\n"
        . "**💝 Special Packages:**\n"
        . "• Wedding collections: 200 - 1000 USD\n"
        . "• Corporate gifts: 100 - 800 USD\n"
        . "• Custom arrangements: Price on request\n\n"
        . "**💡 Price depends on:**\n"
        . "• Size and complexity\n"
        . "• Materials and quality\n"
        . "• Design and customization\n"
        . "• Seasonal availability\n\n"
        . "🔥 **Current discounts up to 30% OFF!**\n"
        . "🛍️ [View all products with prices](/products)",
        
    'chatbot_default' =>
        "🤔 **I'm not sure I understand that question.**\n\n"
        . "But don't worry! I can help you with:\n\n"
        . "🔍 **Product Search:**\n"
        . "• 'find soap flowers'\n"
        . "• 'search for gifts'\n\n"
        . "🏆 **Popular Items:**\n"
        . "• 'best sellers'\n"
        . "• 'what's trending'\n\n"
        . "🔥 **Deals & Sales:**\n"
        . "• 'sale products'\n"
        . "• 'discounts available'\n\n"
        . "📂 **Browse:**\n"
        . "• 'product categories'\n"
        . "• 'latest news'\n\n"
        . "📦 **Account:**\n"
        . "• 'my orders'\n"
        . "• 'shopping cart'\n\n"
        . "🏪 **Information:**\n"
        . "• 'store info'\n"
        . "• 'shipping details'\n\n"
        . "💬 **Or simply type 'help' for a complete guide!**\n\n"
        . "🌸 [Browse all products](/products)",
];
