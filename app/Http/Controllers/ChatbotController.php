<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Post;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Enhanced AI Chatbot Controller
 * 
 * This controller provides intelligent chatbot functionality for customer support
 * in the Hanaya Shop e-commerce application with enhanced English support and
 * improved understanding capabilities.
 */
class ChatbotController extends Controller
{
    /**
     * Main Chat Handler
     */
    public function chat(Request $request)
    {
        try {
            $message = trim(strtolower($request->input('message', '')));

            if (empty($message)) {
                return response()->json([
                    'response' => config('constants.chatbot_greeting')
                ]);
            }

            $response = $this->processMessage($message);

            return response()->json([
                'response' => $response
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Chatbot Error: ' . $e->getMessage(), [
                'message' => $request->input('message'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'response' => '🤖 I apologize, but I encountered a technical issue. Please try again in a moment or contact our support team at ' . config('constants.shop_phone') . ' for immediate assistance.'
            ], 500);
        }
    }

    /**
     * Enhanced Message Processing and Intent Detection
     */
    private function processMessage($message)
    {
        // Enhanced Greeting Detection
        if ($this->containsWords($message, [
            'hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening',
            'greetings', 'howdy', 'what\'s up', 'yo', 'hiya', 'morning', 'afternoon', 'evening',
            'xin chào', 'chào'
        ])) {
            return $this->getGreetingResponse();
        }

        // Enhanced Product Search Intent
        if ($this->containsWords($message, [
            'product', 'products', 'find', 'search', 'look', 'show', 'flower', 'flowers', 'soap', 'gift', 'gifts', 'present', 'presents',
            'buy', 'purchase', 'item', 'items', 'browse', 'available', 'have', 'sell', 'offer', 'offers',
            'recommendation', 'recommend', 'suggest', 'what do you', 'looking for', 'need',
            'fresh flowers', 'artificial', 'handmade', 'custom', 'special', 'unique',
            'birthday', 'anniversary', 'wedding', 'valentine', 'mothers day', 'christmas',
            'sản phẩm', 'tìm', 'tìm kiếm', 'hoa', 'quà'
        ])) {
            return $this->handleProductSearch($message);
        }

        // Enhanced Category Browsing Intent
        if ($this->containsWords($message, [
            'category', 'categories', 'type', 'types', 'kind', 'kinds', 'collection', 'collections',
            'section', 'sections', 'what do you sell', 'what\'s available', 'browse', 'explore',
            'menu', 'catalog', 'range', 'variety', 'selection',
            'danh mục', 'loại', 'phân loại'
        ])) {
            return $this->handleCategoryQuery();
        }

        // Enhanced Order Inquiry Intent
        if ($this->containsWords($message, [
            'order', 'orders', 'purchase', 'purchases', 'buy', 'bought', 'checkout', 'cart',
            'track', 'tracking', 'status', 'delivery', 'shipped', 'delivered',
            'my order', 'order status', 'where is my', 'when will', 'receipt', 'confirmation',
            'đơn hàng', 'mua', 'thanh toán'
        ])) {
            return $this->handleOrderQuery();
        }

        // Enhanced News and Content Intent
        if ($this->containsWords($message, [
            'news', 'blog', 'post', 'posts', 'article', 'articles', 'update', 'updates',
            'latest', 'new', 'recent', 'what\'s new', 'announcements', 'events',
            'tin tức', 'bài viết'
        ])) {
            return $this->handleNewsQuery();
        }

        // Enhanced Pricing Intent
        if ($this->containsWords($message, [
            'price', 'prices', 'cost', 'costs', 'expensive', 'cheap', 'affordable',
            'how much', 'pricing', 'budget', 'range', 'fee', 'charge', 'money',
            'discount', 'sale', 'offer', 'promotion', 'deal', 'deals',
            'giá', 'bao nhiêu', 'chi phí'
        ])) {
            return $this->handlePriceQuery($message);
        }

        // Enhanced Store Information Intent
        if ($this->containsWords($message, [
            'store', 'shop', 'location', 'address', 'contact', 'phone', 'email',
            'hours', 'open', 'close', 'where', 'find you', 'visit', 'directions',
            'about', 'information', 'details', 'business hours',
            'cửa hàng', 'địa chỉ', 'liên hệ'
        ])) {
            return $this->handleStoreInfo();
        }

        // Enhanced Shipping Information Intent
        if ($this->containsWords($message, [
            'ship', 'shipping', 'delivery', 'deliver', 'send', 'transport',
            'freight', 'courier', 'post', 'mail', 'fast delivery', 'express',
            'same day', 'overnight', 'free shipping', 'shipping cost', 'shipping fee',
            'giao hàng', 'vận chuyển'
        ])) {
            return $this->handleShippingInfo();
        }

        // Enhanced Payment Information Intent
        if ($this->containsWords($message, [
            'payment', 'pay', 'paying', 'card', 'cash', 'bank', 'transfer',
            'method', 'methods', 'option', 'options', 'credit', 'debit',
            'wallet', 'installment', 'secure', 'safe', 'payment methods',
            'thanh toán', 'tiền'
        ])) {
            return $this->handlePaymentInfo();
        }

        // Enhanced Help Intent
        if ($this->containsWords($message, [
            'help', 'assist', 'support', 'guide', 'instruction', 'how to',
            'tutorial', 'explain', 'confused', 'don\'t understand', 'stuck',
            'problem', 'issue', 'trouble', 'difficulty', 'assistance',
            'giúp', 'hướng dẫn', 'hỗ trợ'
        ])) {
            return $this->getHelpResponse();
        }

        // Enhanced Popular Products Intent
        if ($this->containsWords($message, [
            'popular', 'bestseller', 'best selling', 'trending', 'hot', 'favorite', 'favorites',
            'top', 'most', 'recommended', 'featured', 'highlighted', 'star', 'bestsellers',
            'bán chạy', 'nổi bật'
        ])) {
            return $this->handlePopularProducts();
        }

        // Gift Suggestion Intent
        if ($this->containsWords($message, [
            'gift', 'present', 'surprise', 'for her', 'for him', 'for mom',
            'for dad', 'for wife', 'for husband', 'for girlfriend', 'for boyfriend',
            'romantic', 'love', 'special occasion', 'gift ideas'
        ])) {
            return $this->handleGiftSuggestions($message);
        }

        // Availability Intent
        if ($this->containsWords($message, [
            'available', 'in stock', 'out of stock', 'when available',
            'restock', 'inventory', 'quantity', 'left', 'remaining', 'stock'
        ])) {
            return $this->handleAvailabilityQuery($message);
        }

        // Fallback Response
        return $this->getEnhancedDefaultResponse();
    }

    /**
     * Generate Enhanced Greeting Response
     */
    private function getGreetingResponse()
    {
        $greetings = [
            "🌸 **Welcome to Hanaya Shop!**\n\nI'm your AI assistant, ready to help you with:\n\n✨ **My Services:**\n🔍 Product search & recommendations\n📦 Order tracking & status\n🏪 Store information & contact\n📰 News & special offers\n💡 Product consultation\n🎁 Gift suggestions\n\n**What can I help you with today?** 😊",

            "🌺 **Hello! Great to meet you!**\n\n🎯 **I can help you with:**\n• Find the most beautiful soap flowers\n• Choose meaningful gifts\n• Check your order status\n• Get personalized product advice\n• Find the perfect items for special occasions\n\n🔗 " . route('user.products.index') . "\n\n**Let me know what you're interested in!** 🌸",

            "🌹 **Hi there! Welcome to Hanaya Shop!**\n\n🎊 **Today's highlights:**\n• New soap flower collections\n• Unique Valentine's gifts\n• Free shipping on orders over $100\n• Personalized gift wrapping\n\n💬 **Ask me anything about:**\nProducts, pricing, shipping, promotions, gift ideas...\n\n**Let's start our conversation!** ✨",

            "🌻 **Greetings! How wonderful to see you here!**\n\n🌟 **Why choose Hanaya Shop:**\n• Premium quality soap flowers\n• Handcrafted with love & care\n• Perfect for any occasion\n• Nationwide delivery available\n\n💝 **Popular requests:**\n'Find romantic gifts' • 'Best soap flowers' • 'Wedding decorations'\n\n**How may I assist you today?** 🎈"
        ];

        return $greetings[array_rand($greetings)];
    }

    /**
     * Enhanced Product Search Handler
     */
    private function handleProductSearch($message)
    {
        $keywords = [
            'flower', 'flowers', 'soap', 'gift', 'gifts', 'souvenir', 'fresh', 'special', 
            'romantic', 'love', 'birthday', 'anniversary', 'wedding', 'valentine', 
            'christmas', 'mothers day', 'handmade', 'custom', 'unique', 'beautiful',
            'hoa', 'xà phòng', 'quà', 'tươi', 'đặc biệt'
        ];
        $foundKeywords = [];

        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                $foundKeywords[] = $keyword;
            }
        }

        $query = Product::with('category')->where('stock_quantity', '>', 0)->take(3);

        if (!empty($foundKeywords)) {
            $query->where(function ($q) use ($foundKeywords) {
                foreach ($foundKeywords as $keyword) {
                    $q->orWhere('name', 'like', "%$keyword%")
                        ->orWhere('descriptions', 'like', "%$keyword%")
                        ->orWhereHas('category', function ($catQ) use ($keyword) {
                            $catQ->where('name', 'like', "%$keyword%");
                        });
                }
            });
        }

        $products = $query->orderBy('view_count', 'desc')->get();

        if ($products->count() === 0) {
            return "🔍 **No products found matching your search**\n\n"
                . "You might be interested in:\n"
                . "🌸 Soap flowers: Long-lasting, gentle fragrance\n"
                . "🌺 Fresh flowers: Natural, vibrant colors\n"
                . "🎁 Souvenirs: Meaningful, unique gifts\n\n"
                . "🔗 " . route('user.products.index') . "\n\n"
                . "💡 **Search tips:**\n"
                . "• Try broader terms like 'flowers' or 'gifts'\n"
                . "• Search by occasion: 'birthday', 'wedding'\n"
                . "• Browse categories for inspiration\n\n"
                . "📞 **Need help?** Call us: " . config('constants.shop_phone');
        }

        $response = "🌸 **Products matching your search:**\n\n";
        foreach ($products as $product) {
            $response .= "💝 **{$product->name}**\n";
            $response .= "📂 Category: {$product->category->name}\n";
            $response .= "💰 Price: \${$product->price}\n";
            $response .= "📦 Stock: {$product->stock_quantity} available\n";
            $response .= "👀 Views: {$product->view_count}\n";
            $response .= "🔗 " . route('user.products.show', $product->id) . "\n\n";
        }

        $response .= "✨ **Browse more products:**\n";
        $response .= "🔗 " . route('user.products.index') . "\n\n";
        $response .= "💡 **Shopping tips:**\n";
        $response .= "• Check stock availability before ordering\n";
        $response .= "• Read product descriptions for sizing\n";
        $response .= "• Contact us for personalized recommendations\n";
        $response .= "• Hotline: " . config('constants.shop_phone');

        return $response;
    }

    /**
     * Category Query Handler
     */
    private function handleCategoryQuery()
    {
        $categories = Category::withCount('product')->get();

        if ($categories->count() === 0) {
            return "📂 **Product Categories**\n\n"
                . "We're currently updating our product categories.\n"
                . "Please check back soon or browse all products:\n\n"
                . "🔗 " . route('user.products.index') . "\n\n"
                . "📞 **Need assistance?** " . config('constants.shop_phone');
        }

        $response = "📂 **Product categories at Hanaya Shop:**\n\n";
        foreach ($categories as $category) {
            $response .= "🌟 **{$category->name}**\n";
            $response .= "📦 {$category->product_count} products available\n";
            $response .= "🔗 " . route('user.products.index', ['category' => $category->id]) . "\n\n";
        }

        $response .= "🎯 **Popular categories:**\n";
        $response .= "🧼 Soap Flowers - Long-lasting, beautiful fragrance\n";
        $response .= "🌺 Fresh Flowers - Natural, vibrant colors\n";
        $response .= "🎁 Souvenirs - Meaningful, memorable gifts\n\n";
        $response .= "💝 **Tip:** Choose based on special occasions like birthdays, weddings, anniversaries...";

        return $response;
    }

    /**
     * Order Query Handler
     */
    private function handleOrderQuery()
    {
        try {
            if (!Auth::check()) {
                return "🔐 **Please log in to check your orders**\n\n"
                    . "📱 **Login to access:**\n"
                    . "• Order history & tracking\n"
                    . "• Delivery status updates\n"
                    . "• Digital receipts\n"
                    . "• Reorder favorite items\n\n"
                    . "🔗 " . route('login') . "\n\n"
                    . "❓ **Need help?** Contact us:\n"
                    . "📞 " . config('constants.shop_phone') . "\n"
                    . "📧 " . config('constants.shop_email');
            }

            $orders = Order::where('user_id', Auth::id())->latest()->take(3)->get();

            if ($orders->count() === 0) {
                return "📦 **No orders found**\n\n"
                    . "🛒 **Start shopping:**\n"
                    . "🔗 " . route('user.products.index') . "\n\n"
                    . "🎁 **Special offers:**\n"
                    . "• Free shipping on orders over $100\n"
                    . "• 10% discount for first-time customers\n"
                    . "• Gift wrapping included\n\n"
                    . "📞 **Questions?** " . config('constants.shop_phone');
            }

            $response = "📦 **Your recent orders:**\n\n";
            foreach ($orders as $order) {
                $response .= "🛍️ **Order #{$order->id}**\n";
                $response .= "📅 Date: " . $order->created_at->format('M d, Y') . "\n";
                $response .= "💰 Total: \${$order->total_amount}\n";
                $response .= "📋 Status: " . $this->translateStatus($order->status) . "\n";
                $response .= "🔗 " . route('order.show', $order->id) . "\n\n";
            }

            $response .= "🔍 **Need more help?**\n";
            $response .= "📞 Hotline: " . config('constants.shop_phone') . "\n";
            $response .= "📧 Email: " . config('constants.shop_email') . "\n";
            $response .= "⏰ Support hours: 8:00 AM - 10:00 PM daily";

            return $response;
        } catch (\Exception $e) {
            Log::error('Order Query Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return "📦 **Order Information Temporarily Unavailable**\n\n"
                . "We're experiencing technical difficulties accessing order information right now.\n\n"
                . "📞 **For immediate order assistance, please contact:**\n"
                . "• Phone: " . config('constants.shop_phone') . "\n"
                . "• Email: " . config('constants.shop_email') . "\n"
                . "• Support hours: 8:00 AM - 10:00 PM daily\n\n"
                . "We apologize for the inconvenience and appreciate your patience! 🙏";
        }
    }

    /**
     * News Query Handler
     */
    private function handleNewsQuery()
    {
        $posts = Post::where('status', '1')
            ->latest()
            ->take(3)
            ->get();

        if ($posts->count() === 0) {
            return "📰 **News & Updates**\n\n"
                . "No recent news available at the moment.\n"
                . "Check back soon for updates!\n\n"
                . "🌸 **Follow us for latest news:**\n"
                . "• Product launches\n"
                . "• Special promotions\n"
                . "• Care tips & guides\n\n"
                . "📞 **Contact:** " . config('constants.shop_phone');
        }

        $response = "📰 **Latest news & articles:**\n\n";

        foreach ($posts as $index => $post) {
            $response .= "📝 **{$post->title}**\n";
            $response .= "📅 " . $post->created_at->format('M d, Y') . "\n";
            $response .= "📖 " . substr(strip_tags($post->content), 0, 100) . "...\n";
            $response .= "🔗 " . route('posts.show', $post->slug) . "\n\n";
        }

        $response .= "🌸 **Hot topics:**\n";
        $response .= "• How to choose flowers for different occasions\n";
        $response .= "• Soap flower care and maintenance tips\n";
        $response .= "• Home decoration ideas with flowers\n";
        $response .= "• Gift trends for 2025\n\n";
        $response .= "💡 **Visit our website to discover more interesting articles!**";

        return $response;
    }

    /**
     * Price Query Handler
     */
    private function handlePriceQuery($message)
    {
        return config('constants.chatbot_price_info');
    }

    /**
     * Store Information Handler
     */
    private function handleStoreInfo()
    {
        return config('constants.chatbot_store_info');
    }

    /**
     * Shipping Information Handler
     */
    private function handleShippingInfo()
    {
        return config('constants.chatbot_shipping_info');
    }

    /**
     * Payment Information Handler
     */
    private function handlePaymentInfo()
    {
        return config('constants.chatbot_payment_info');
    }

    /**
     * Popular Products Handler
     */
    private function handlePopularProducts()
    {
        $popularProducts = Product::with('category')
            ->where('stock_quantity', '>', 0)
            ->orderBy('view_count', 'desc')
            ->take(3)
            ->get();

        if ($popularProducts->count() === 0) {
            return "🔥 **Popular Products**\n\n"
                . "We're currently updating our bestsellers list.\n"
                . "Browse all products to find amazing items:\n\n"
                . "🔗 " . route('user.products.index') . "\n\n"
                . "📞 **Recommendations?** " . config('constants.shop_phone');
        }

        $response = "🔥 **Top bestselling products:**\n\n";
        foreach ($popularProducts as $index => $product) {
            $medalEmoji = $index === 0 ? '🥇' : ($index === 1 ? '🥈' : '🥉');
            $response .= "{$medalEmoji} **{$product->name}**\n";
            $response .= "📂 Category: {$product->category->name}\n";
            $response .= "💰 Price: \${$product->price}\n";
            $response .= "👀 {$product->view_count} customers viewed\n";
            $response .= "🔗 " . route('user.products.show', $product->id) . "\n\n";
        }

        $response .= "⭐ **Why customers love these:**\n";
        $response .= "• Premium quality, long-lasting beauty\n";
        $response .= "• Excellent value for money\n";
        $response .= "• Elegant packaging included\n";
        $response .= "• Outstanding customer service\n\n";
        $response .= "🛒 **Order now to get special offers!**";

        return $response;
    }

    /**
     * Help Response Handler
     */
    private function getHelpResponse()
    {
        return config('constants.chatbot_help');
    }

    /**
     * Enhanced Default Response
     */
    private function getEnhancedDefaultResponse()
    {
        return config('constants.chatbot_default');
    }

    /**
     * Gift Suggestions Handler
     */
    private function handleGiftSuggestions($message)
    {
        $giftProducts = Product::with('category')
            ->where('stock_quantity', '>', 0)
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%gift%')
                  ->orWhere('name', 'like', '%souvenir%')
                  ->orWhere('name', 'like', '%present%');
            })
            ->orderBy('view_count', 'desc')
            ->take(3)
            ->get();

        if ($giftProducts->count() === 0) {
            return "🎁 **Perfect gift ideas from Hanaya Shop:**\n\n"
                . "💝 **Popular gift categories:**\n"
                . "🌹 Romantic soap flower bouquets\n"
                . "🎀 Elegant gift sets with premium packaging\n"
                . "💐 Custom arrangements for special occasions\n"
                . "🌸 Personalized message cards included\n\n"
                . "🔗 " . route('user.products.index') . "\n\n"
                . "💡 **Gift occasions:** Birthdays, anniversaries, Valentine's Day, Mother's Day, weddings, graduations\n\n"
                . "📞 **Need personal consultation?** Call us: " . config('constants.shop_phone');
        }

        $response = "🎁 **Perfect gift suggestions for you:**\n\n";
        foreach ($giftProducts as $product) {
            $response .= "💝 **{$product->name}**\n";
            $response .= "💰 Price: \${$product->price}\n";
            $response .= "📦 In stock: {$product->stock_quantity} items\n";
            $response .= "🔗 " . route('user.products.show', $product->id) . "\n\n";
        }

        $response .= "🌟 **Why our gifts are special:**\n";
        $response .= "• Handcrafted with love and attention to detail\n";
        $response .= "• Long-lasting beauty that preserves memories\n";
        $response .= "• Elegant packaging included at no extra cost\n";
        $response .= "• Personalized message cards available\n\n";
        $response .= "💝 **Perfect for any special occasion!**";

        return $response;
    }

    /**
     * Availability Query Handler
     */
    private function handleAvailabilityQuery($message)
    {
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->where('stock_quantity', '>', 0)
            ->take(3)
            ->get();

        $outOfStockProducts = Product::where('stock_quantity', 0)->take(3)->get();

        $response = "📦 **Product availability information:**\n\n";

        if ($lowStockProducts->count() > 0) {
            $response .= "⚠️ **Limited stock items:**\n";
            foreach ($lowStockProducts as $product) {
                $response .= "• {$product->name} - Only {$product->stock_quantity} left!\n";
            }
            $response .= "\n";
        }

        if ($outOfStockProducts->count() > 0) {
            $response .= "❌ **Currently out of stock:**\n";
            foreach ($outOfStockProducts as $product) {
                $response .= "• {$product->name} - Will restock soon\n";
            }
            $response .= "\n";
        }

        $response .= "✅ **Stock status updates:**\n";
        $response .= "• We restock popular items weekly\n";
        $response .= "• New arrivals every month\n";
        $response .= "• Notify us for restock alerts\n\n";
        $response .= "📞 **For specific availability:** " . config('constants.shop_phone');

        return $response;
    }

    /**
     * Helper method to check if message contains specific words
     */
    private function containsWords($text, $words)
    {
        foreach ($words as $word) {
            if (strpos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Translate order status to readable format
     */
    private function translateStatus($status)
    {
        $statuses = config('constants.chatbot_status');
        return $statuses[$status] ?? ucfirst($status);
    }
}
