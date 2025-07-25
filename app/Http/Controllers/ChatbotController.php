<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Post;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $message = trim(strtolower($request->input('message', '')));

        if (empty($message)) {
            return response()->json([
                'response' => 'Hello! I can help you today. What would you like to know?'
            ]);
        }

        $response = $this->processMessage($message);

        return response()->json([
            'response' => $response
        ]);
    }

    private function processMessage($message)
    {
        // Greetings
        if ($this->containsWords($message, ['xin chào', 'chào', 'hello', 'hi'])) {
            return config('constants.chatbot_greeting');
        }

        // Best sellers / popular products
        if ($this->containsWords($message, ['best seller', 'bán chạy', 'popular', 'phổ biến', 'top'])) {
            return $this->handleBestSellers();
        }

        // Sale/discount products
        if ($this->containsWords($message, ['sale', 'giảm giá', 'khuyến mãi', 'discount', 'ưu đãi'])) {
            return $this->handleSaleProducts();
        }

        // Product search
        if ($this->containsWords($message, ['sản phẩm', 'tìm', 'tìm kiếm', 'product', 'find'])) {
            return $this->handleProductSearch($message);
        }

        // Categories
        if ($this->containsWords($message, ['danh mục', 'category', 'loại', 'categories'])) {
            return $this->handleCategoryQuery();
        }

        // Reviews and ratings
        if ($this->containsWords($message, ['review', 'đánh giá', 'rating', 'feedback'])) {
            return $this->handleReviewsQuery();
        }

        // Order inquiry
        if ($this->containsWords($message, ['đơn hàng', 'order', 'mua'])) {
            return $this->handleOrderQuery();
        }

        // Cart inquiry
        if ($this->containsWords($message, ['giỏ hàng', 'cart', 'shopping cart'])) {
            return $this->handleCartQuery();
        }

        // Latest posts/news
        if ($this->containsWords($message, ['tin tức', 'bài viết', 'news', 'post'])) {
            return $this->handleNewsQuery();
        }

        // Pricing
        if ($this->containsWords($message, ['giá', 'price', 'bao nhiêu'])) {
            return $this->handlePriceQuery($message);
        }

        // Store information
        if ($this->containsWords($message, ['cửa hàng', 'store', 'shop', 'địa chỉ', 'liên hệ', 'contact'])) {
            return $this->handleStoreInfo();
        }

        // Shipping info
        if ($this->containsWords($message, ['shipping', 'giao hàng', 'delivery', 'vận chuyển'])) {
            return $this->handleShippingInfo();
        }

        // Help
        if ($this->containsWords($message, ['help', 'giúp', 'hướng dẫn'])) {
            return config('constants.chatbot_help');
        }

        // Default response with suggestions
        return config('constants.chatbot_default');
    }

    private function handleProductSearch($message)
    {
        // Extract keywords from message
        $keywords = ['hoa', 'xà phòng', 'soap', 'flower', 'quà', 'gift', 'souvenir'];
        $foundKeyword = null;

        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                $foundKeyword = $keyword;
                break;
            }
        }

        $query = Product::with('category')->take(5);

        if ($foundKeyword) {
            $query->where(function ($q) use ($foundKeyword) {
                $q->where('name', 'like', "%$foundKeyword%")
                    ->orWhere('descriptions', 'like', "%$foundKeyword%")
                    ->orWhereHas('category', function ($catQ) use ($foundKeyword) {
                        $catQ->where('name', 'like', "%$foundKeyword%");
                    });
            });
        }

        $products = $query->get();

        if ($products->count() === 0) {
            return "❌ No products found matching your search.\n\n" .
                "🔍 **Try these suggestions:**\n" .
                "• Search for 'soap flowers'\n" .
                "• Search for 'fresh flowers'\n" .
                "• Search for 'souvenirs'\n\n" .
                "🌸 [View all products](" . route('user.products.index') . ")";
        }

        $response = "🔍 **Found " . $products->count() . " products for you:**\n\n";
        
        foreach ($products as $index => $product) {
            $price = $product->discount_percent > 0 ? 
                "~~" . number_format($product->price, 0, ',', '.') . " USD~~ **" . number_format($product->discounted_price, 0, ',', '.') . " USD**" :
                "**" . number_format($product->price, 0, ',', '.') . " USD**";
                
            $response .= "🌸 **" . ($index + 1) . ". " . $product->name . "**\n";
            $response .= "💰 " . $price . "\n";
            $response .= "📂 " . $product->category->name . "\n";
            $response .= "👁️ " . ($product->view_count ?? 0) . " views\n";
            if ($product->discount_percent > 0) {
                $response .= "🏷️ **-" . $product->discount_percent . "% OFF**\n";
            }
            $response .= "🔗 [View details](" . route('user.products.show', $product->id) . ")\n\n";
        }

        $response .= "🛒 [View all products](" . route('user.products.index') . ")";

        return $response;
    }

    private function handleBestSellers()
    {
        $products = Product::with('category')
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->select('products.*', DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold'))
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        if ($products->count() === 0) {
            return "📈 No sales data available yet.\n\n🌸 [Explore our products](" . route('user.products.index') . ")";
        }

        $response = "🏆 **Best Selling Products:**\n\n";
        
        foreach ($products as $index => $product) {
            $sold = $product->total_sold > 0 ? $product->total_sold . " sold" : "New product";
            $price = $product->discount_percent > 0 ? 
                "~~" . number_format($product->price, 0, ',', '.') . " USD~~ **" . number_format($product->discounted_price, 0, ',', '.') . " USD**" :
                "**" . number_format($product->price, 0, ',', '.') . " USD**";
                
            $response .= "🥇 **" . ($index + 1) . ". " . $product->name . "**\n";
            $response .= "💰 " . $price . "\n";
            $response .= "📦 " . $sold . "\n";
            $response .= "🔗 [Buy now](" . route('user.products.show', $product->id) . ")\n\n";
        }

        $response .= "🛍️ [View all best sellers](" . route('user.products.index', ['sort' => 'bestseller']) . ")";

        return $response;
    }

    private function handleSaleProducts()
    {
        $products = Product::with('category')
            ->where('discount_percent', '>', 0)
            ->orderBy('discount_percent', 'desc')
            ->take(5)
            ->get();

        if ($products->count() === 0) {
            return "🎯 No products on sale right now.\n\n" .
                "💡 **Don't worry!** New sales are coming soon.\n" .
                "🔔 Keep checking back for amazing deals!\n\n" .
                "🌸 [Browse all products](" . route('user.products.index') . ")";
        }

        $response = "🔥 **Products on Sale - Limited Time!**\n\n";
        
        foreach ($products as $index => $product) {
            $response .= "🏷️ **" . ($index + 1) . ". " . $product->name . "**\n";
            $response .= "💥 **-" . $product->discount_percent . "% OFF**\n";
            $response .= "💰 ~~" . number_format($product->price, 0, ',', '.') . " USD~~ **" . number_format($product->discounted_price, 0, ',', '.') . " USD**\n";
            $response .= "💵 You save: **" . number_format($product->price - $product->discounted_price, 0, ',', '.') . " USD**\n";
            $response .= "🛒 [Get this deal](" . route('user.products.show', $product->id) . ")\n\n";
        }

        $response .= "🎉 [View all sale products](" . route('user.products.index', ['sort' => 'sale']) . ")";

        return $response;
    }

    private function handleReviewsQuery()
    {
        return "⭐ **Customer Reviews & Ratings:**\n\n" .
            "🌟 **Average Rating:** 4.8/5 stars\n" .
            "📝 **Total Reviews:** 500+ happy customers\n\n" .
            "💬 **What customers say:**\n" .
            "• \"Beautiful soap flowers, exactly as described!\"\n" .
            "• \"Fast delivery and excellent packaging\"\n" .
            "• \"Perfect gifts for special occasions\"\n" .
            "• \"High quality products at reasonable prices\"\n\n" .
            "📖 [Read all reviews](" . route('user.products.index') . ")\n" .
            "✍️ [Leave a review after purchase](" . route('user.products.index') . ")";
    }

    private function handleCartQuery()
    {
        if (!Auth::check()) {
            return "🛒 **Shopping Cart Information:**\n\n" .
                "To view your cart, you need to sign in first.\n\n" .
                "🔐 [Sign in](" . route('login') . ")\n" .
                "📝 [Create account](" . route('register') . ")\n\n" .
                "🌸 [Continue shopping](" . route('user.products.index') . ")";
        }

        return "🛒 **Your Shopping Cart:**\n\n" .
            "To view your current cart items and checkout:\n\n" .
            "🛍️ [View cart](" . route('cart.index') . ")\n" .
            "💳 [Proceed to checkout](" . route('cart.index') . ")\n\n" .
            "🌸 [Continue shopping](" . route('user.products.index') . ")";
    }

    private function handleShippingInfo()
    {
        return "🚚 **Shipping & Delivery Information:**\n\n" .
            "📦 **Delivery Options:**\n" .
            "• Standard shipping: 2-3 business days\n" .
            "• Express shipping: 1-2 business days\n" .
            "• Same-day delivery (Hanoi area only)\n\n" .
            "💰 **Shipping Costs:**\n" .
            "• Free shipping for orders over 100 USD\n" .
            "• Standard: 10 USD nationwide\n" .
            "• Express: 20 USD nationwide\n\n" .
            "📍 **Coverage:** Nationwide delivery\n" .
            "📞 **Track orders:** Call " . config('constants.shop_phone') . "\n\n" .
            "🛍️ [Start shopping](" . route('user.products.index') . ")";
    }

    private function handleCategoryQuery()
    {
        $categories = Category::withCount('product')->get();

        if ($categories->count() === 0) {
            return "📂 No product categories available yet.";
        }

        $response = "📂 **Our Product Categories:**\n\n";
        
        $categoryIcons = [
            'soap' => '🧼',
            'flower' => '🌸',
            'fresh' => '🌹',
            'souvenir' => '🎁',
            'gift' => '🎀'
        ];
        
        foreach ($categories as $category) {
            $icon = '📦';
            foreach ($categoryIcons as $key => $categoryIcon) {
                if (stripos($category->name, $key) !== false) {
                    $icon = $categoryIcon;
                    break;
                }
            }
            
            $response .= "$icon **{$category->name}**\n";
            $response .= "📊 {$category->product_count} products available\n";
            if ($category->descriptions) {
                $response .= "ℹ️ " . substr($category->descriptions, 0, 80) . (strlen($category->descriptions) > 80 ? '...' : '') . "\n";
            }
            $response .= "🛍️ [Shop now](" . route('user.products.index', ['category' => $category->id]) . ")\n\n";
        }

        $response .= "🌸 [Browse all products](" . route('user.products.index') . ")";

        return $response;
    }

    private function handleOrderQuery()
    {
        if (!Auth::check()) {
            return "📦 **Order Information:**\n\n" .
                "To view your orders, please sign in first.\n\n" .
                "🔐 [Sign in](" . route('login') . ")\n" .
                "📝 [Create new account](" . route('register') . ")\n\n" .
                "🛍️ **Want to place an order?**\n" .
                "🌸 [Browse products](" . route('user.products.index') . ")";
        }

        $orders = Order::where('user_id', Auth::id())->latest()->take(3)->get();

        if ($orders->count() === 0) {
            return "📦 **Your Orders:**\n\n" .
                "You haven't placed any orders yet.\n\n" .
                "🛍️ [Start shopping](" . route('user.products.index') . ")\n" .
                "🎁 [View gift ideas](" . route('user.products.index', ['category_name' => 'souvenir']) . ")";
        }

        $response = "📦 **Your Recent Orders:**\n\n";
        
        foreach ($orders as $index => $order) {
            $statusIcons = [
                'pending' => '⏳',
                'processing' => '⚙️',
                'completed' => '✅',
                'cancelled' => '❌'
            ];
            
            $statusIcon = $statusIcons[$order->status] ?? '📦';
            $statusText = $this->translateStatus($order->status);
            
            $response .= "🧾 **Order #" . ($index + 1) . " (#{$order->id})**\n";
            $response .= "💰 **" . number_format($order->total_amount, 0, ',', '.') . " USD**\n";
            $response .= "📅 " . $order->created_at->format('M d, Y \a\t H:i') . "\n";
            $response .= "$statusIcon Status: **$statusText**\n";
            $response .= "🔍 [View details](" . route('order.show', $order->id) . ")\n\n";
        }

        $response .= "📋 [View all orders](" . route('order.index') . ")\n";
        $response .= "🛍️ [Continue shopping](" . route('user.products.index') . ")";

        return $response;
    }

    private function handleNewsQuery()
    {
        $posts = Post::where('status', 'published')
            ->with('author')
            ->latest()
            ->take(5)
            ->get();

        if ($posts->count() === 0) {
            return "📝 Currently no articles have been published.\n\n" .
                "Please come back later for the latest news from Hanaya Shop! 🌸";
        }

        $response = "📰 **Latest News & Articles from Hanaya Shop:**\n\n";

        foreach ($posts as $index => $post) {
            $response .= "📄 **" . ($index + 1) . ". " . $post->title . "**\n";
            $response .= "📅 Published: " . $post->created_at->format('M d, Y H:i') . "\n";
            $response .= "✍️ Author: " . ($post->author->name ?? 'Admin Hanaya Shop') . "\n";

            // Get first 150 characters of content
            $excerpt = strip_tags($post->content);
            $excerpt = mb_strlen($excerpt) > 150 ? mb_substr($excerpt, 0, 150) . '...' : $excerpt;
            $response .= "📖 Summary: " . $excerpt . "\n\n";
        }

        $response .= "🌸 **Tips:**\n";
        $response .= "• Follow our blog for the latest flower decoration trends\n";
        $response .= "• Learn how to care for and preserve flower products\n";
        $response .= "• Discover unique decoration and gift ideas\n\n";
        $response .= "💡 Visit our website to read full articles and discover more interesting content!";

        return $response;
    }

    private function handlePriceQuery($message)
    {
        return config('constants.chatbot_price_info');
    }

    private function handleStoreInfo()
    {
        return config('constants.chatbot_store_info');
    }

    private function showHelp()
    {
        return config('constants.chatbot_help');
    }

    private function containsWords($text, $words)
    {
        foreach ($words as $word) {
            if (strpos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    private function translateStatus($status)
    {
        $statuses = config('constants.chatbot_status');
        return $statuses[$status] ?? ucfirst($status);
    }
}
