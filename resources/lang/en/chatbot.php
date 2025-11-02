<?php

return [
    'greeting' => "Hello! I'm Hanaya Shop's AI assistant. I can help you with:\n"
        . "🌸 Product search & recommendations\n"
        . "📝 Order tracking & status\n"
        . "📋 Product categories & collections\n"
        . "📰 Latest news & blog updates\n"
        . "💰 Pricing & payment information\n"
        . "🚚 Shipping & delivery details\n"
        . "🏪 Store information & contact\n"
        . "❓ Frequently asked questions\n\n"
        . "What can I help you with today?",
        
    'help' => "🤖 Hanaya Shop chatbot user guide:\n\n"
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
        
    'store_info' => "🏪 Hanaya Shop store information:\n\n"
        . "📍 Address: 123 Flower Street, Son Tay, Hanoi\n"
        . "📞 Phone: 0948512463\n"
        . "📧 Email: assassincreed2k1@gmail.com\n"
        . "🕒 Business hours: 8:00 AM - 10:00 PM (Monday - Sunday)\n\n"
        . "🚚 Delivery: Nationwide shipping available\n"
        . "💳 Payment: Cash, bank transfer, credit/debit cards\n"
        . "🎁 Special services: Gift wrapping, custom arrangements\n\n"
        . "Contact us now for personalized consultation!",
        
    'status' => [
        'pending' => "Pending - Your order is being processed",
        'processing' => "Processing - We're preparing your order",
        'shipped' => "Shipped - Your order is on the way",
        'completed' => "Completed - Order delivered successfully",
        'cancelled' => "Cancelled - Order has been cancelled"
    ],
    
    'price_info' => "About our product prices:\n\n"
        . "🌸 Soap flowers: From $15 - $500\n"
        . "🎁 Souvenirs & gifts: From $5 - $800\n"
        . "💐 Fresh flowers: From $10 - $200\n"
        . "🎀 Custom arrangements: From $25 - $1000\n\n"
        . "Prices vary based on size, materials, design complexity, and customization.\n"
        . "💡 Free shipping on orders over $100!\n"
        . "🎊 Bulk order discounts available!\n\n"
        . "See detailed prices at: /products",
        
    'shipping_info' => "🚚 Shipping & delivery information:\n\n"
        . "📦 Free shipping on orders over $100\n"
        . "🚚 Standard delivery: 3-5 business days\n"
        . "⚡ Express delivery: 1-2 business days (+$15)\n"
        . "🏃 Same-day delivery: Available in Hanoi (+$25)\n\n"
        . "📍 Delivery areas: Nationwide coverage\n"
        . "📋 Tracking: Real-time order tracking available\n"
        . "📦 Packaging: Eco-friendly, secure packaging\n"
        . "🎁 Gift options: Free gift wrapping & cards\n\n"
        . "Questions about delivery? Contact us: 0948512463",
        
    'payment_info' => "💳 Payment methods & options:\n\n"
        . "💰 Cash on delivery (COD)\n"
        . "🏦 Bank transfer (instant confirmation)\n"
        . "💳 Credit/Debit cards (Visa, Mastercard)\n"
        . "📱 Digital wallets (Momo, ZaloPay)\n"
        . "💎 Installment plans available\n\n"
        . "🔒 Secure payments with SSL encryption\n"
        . "🎊 Special offers: 5% discount on bank transfers\n"
        . "💝 Loyalty points on every purchase\n\n"
        . "Need payment assistance? We're here to help!",
        
    'default' => "I understand you're looking for help! Here's what I can assist you with:\n\n"
        . "🔍 **Product search**: 'find soap flowers', 'show me gifts'\n"
        . "📂 **Categories**: 'what categories do you have'\n"
        . "📦 **Orders**: 'check my orders', 'track order'\n"
        . "📰 **News**: 'latest updates', 'blog posts'\n"
        . "🏪 **Store info**: 'contact details', 'store hours'\n"
        . "💰 **Pricing**: 'price range', 'payment methods'\n"
        . "🚚 **Shipping**: 'delivery options', 'shipping costs'\n\n"
        . "💡 **Quick tip**: Try asking questions naturally, like 'What flowers do you recommend for a birthday?' or 'How much does shipping cost?'\n\n"
        . "Type 'help' for detailed instructions or just ask me anything!",
    
    // Error handling
    'error' => '🤖 I apologize, but I encountered a technical issue. Please try again in a moment or contact our support team at :phone for immediate assistance.',
    
    // Product search responses
    'no_products_found' => "🔍 **No products found matching your search**\n\n"
        . "You might be interested in:\n"
        . "🌸 Soap flowers: Long-lasting, gentle fragrance\n"
        . "🌺 Fresh flowers: Natural, vibrant colors\n"
        . "🎁 Souvenirs: Meaningful, unique gifts\n\n"
        . "🔗 :products_url\n\n"
        . "💡 **Search tips:**\n"
        . "• Try broader terms like 'flowers' or 'gifts'\n"
        . "• Search by occasion: 'birthday', 'wedding'\n"
        . "• Browse categories for inspiration\n\n"
        . "📞 **Need help?** Call us: :phone",
    
    'products_search_results' => '🌸 **Products matching your search:**',
    
    'browse_more_products' => "✨ **Browse more products:**\n"
        . "🔗 :products_url\n\n"
        . "💡 **Shopping tips:**\n"
        . "• Check stock availability before ordering\n"
        . "• Read product descriptions for sizing\n"
        . "• Contact us for personalized recommendations\n"
        . "• Hotline: :phone",
    
    // Category responses
    'no_categories_found' => "📂 **Product Categories**\n\n"
        . "We're currently updating our product categories.\n"
        . "Please check back soon or browse all products:\n\n"
        . "🔗 :products_url\n\n"
        . "📞 **Need assistance?** :phone",
    
    'product_categories' => '📂 **Product categories at Hanaya Shop:**',
    
    'popular_categories' => "🎯 **Popular categories:**\n"
        . "🧼 Soap Flowers - Long-lasting, beautiful fragrance\n"
        . "🌺 Fresh Flowers - Natural, vibrant colors\n"
        . "🎁 Souvenirs - Meaningful, memorable gifts\n\n"
        . "💝 **Tip:** Choose based on special occasions like birthdays, weddings, anniversaries...",
    
    // Order responses
    'login_required' => "🔐 **Please log in to check your orders**\n\n"
        . "📱 **Login to access:**\n"
        . "• Order history & tracking\n"
        . "• Delivery status updates\n"
        . "• Digital receipts\n"
        . "• Reorder favorite items\n\n"
        . "🔗 :login_url\n\n"
        . "❓ **Need help?** Contact us:\n"
        . "📞 :phone\n"
        . "📧 :email",
    
    'no_orders_found' => "📦 **No orders found**\n\n"
        . "🛒 **Start shopping:**\n"
        . "🔗 :products_url\n\n"
        . "🎁 **Special offers:**\n"
        . "• Free shipping on orders over $100\n"
        . "• 10% discount for first-time customers\n"
        . "• Gift wrapping included\n\n"
        . "📞 **Questions?** :phone",
    
    'recent_orders' => '📦 **Your recent orders:**',
    
    'order_support' => "🔍 **Need more help?**\n"
        . "📞 Hotline: :phone\n"
        . "📧 Email: :email\n"
        . "⏰ Support hours: 8:00 AM - 10:00 PM daily",
    
    'order_technical_error' => "📦 **Order Information Temporarily Unavailable**\n\n"
        . "We're experiencing technical difficulties accessing order information right now.\n\n"
        . "📞 **For immediate order assistance, please contact:**\n"
        . "• Phone: :phone\n"
        . "• Email: :email\n"
        . "• Support hours: 8:00 AM - 10:00 PM daily\n\n"
        . "We apologize for the inconvenience and appreciate your patience! 🙏",
    
    // News responses
    'no_news_found' => "📰 **News & Updates**\n\n"
        . "No recent news available at the moment.\n"
        . "Check back soon for updates!\n\n"
        . "🌸 **Follow us for latest news:**\n"
        . "• Product launches\n"
        . "• Special promotions\n"
        . "• Care tips & guides\n\n"
        . "📞 **Contact:** :phone",
    
    'latest_news' => '📰 **Latest news & articles:**',
    
    'hot_topics' => "🌸 **Hot topics:**\n"
        . "• How to choose flowers for different occasions\n"
        . "• Soap flower care and maintenance tips\n"
        . "• Home decoration ideas with flowers\n"
        . "• Gift trends for 2025\n\n"
        . "💡 **Visit our website to discover more interesting articles!**",
    
    // Popular products responses
    'no_popular_products' => "🔥 **Popular Products**\n\n"
        . "We're currently updating our bestsellers list.\n"
        . "Browse all products to find amazing items:\n\n"
        . "🔗 :products_url\n\n"
        . "📞 **Recommendations?** :phone",
    
    'top_bestselling' => '🔥 **Top bestselling products:**',
    
    'why_customers_love' => "⭐ **Why customers love these:**\n"
        . "• Premium quality, long-lasting beauty\n"
        . "• Excellent value for money\n"
        . "• Elegant packaging included\n"
        . "• Outstanding customer service\n\n"
        . "🛒 **Order now to get special offers!**",
    
    // Gift suggestions
    'gift_suggestions' => "🎁 **Perfect gift ideas from Hanaya Shop:**\n\n"
        . "💝 **Popular gift categories:**\n"
        . "🌹 Romantic soap flower bouquets\n"
        . "🎀 Elegant gift sets with premium packaging\n"
        . "💐 Custom arrangements for special occasions\n"
        . "🌸 Personalized message cards included\n\n"
        . "🔗 :products_url\n\n"
        . "💡 **Gift occasions:** Birthdays, anniversaries, Valentine's Day, Mother's Day, weddings, graduations\n\n"
        . "📞 **Need personal consultation?** Call us: :phone",
    
    'perfect_gifts' => '🎁 **Perfect gift suggestions for you:**',
    
    'why_gifts_special' => "🌟 **Why our gifts are special:**\n"
        . "• Handcrafted with love and attention to detail\n"
        . "• Long-lasting beauty that preserves memories\n"
        . "• Elegant packaging included at no extra cost\n"
        . "• Personalized message cards available\n\n"
        . "💝 **Perfect for any special occasion!**",
    
    // Availability responses
    'availability_info' => '📦 **Product availability information:**',
    'limited_stock' => 'Limited stock items',
    'only_left' => 'Only :count left!',
    'out_of_stock' => 'Currently out of stock',
    'restock_soon' => 'Will restock soon',
    
    'stock_updates' => "✅ **Stock status updates:**\n"
        . "• We restock popular items weekly\n"
        . "• New arrivals every month\n"
        . "• Notify us for restock alerts\n\n"
        . "📞 **For specific availability:** :phone",


    // --- Assistant Info ---
    'name' => 'Hanaya Shop Assistant',
    'status_online' => 'Online now',
    'greeting_hanaya' => "Hello! I'm Hanaya Shop's AI assistant. I can help you with:",
    'how_can_i_assist' => "How can I assist you today ? 🌸",

    // --- Main Features ---
    'find_products' => "🔍 Find products & recommendations",
    'track_orders' => "📦 Track your orders",
    'pricing_info' => "💰 Pricing & payment options",
    'faq' => "❓ FAQ & customer support",
    'soap_flowers' => "🧼 Soap Flowers",

    // --- Quick Action Buttons ---
    'track_orders_button' => "📦 My Orders",
    'store_info_button' => "🏪 Store Info",
    'latest_news_button' => "📰 News",
    'popular_button' => "⭐ Popular",
    'help_button' => "❓ Help",

];
