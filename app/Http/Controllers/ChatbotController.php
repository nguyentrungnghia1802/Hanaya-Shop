<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Post;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
                    'response' => __('chatbot.greeting')
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            $response = $this->processMessage($message);
            
            // Ensure UTF-8 encoding
            $response = mb_convert_encoding($response, 'UTF-8', 'UTF-8');

            return response()->json([
                'response' => $response
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Chatbot Error: ' . $e->getMessage(), [
                'message' => $request->input('message'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'response' => __('chatbot.error', ['phone' => config('constants.shop_phone')])
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
            'xin chào', 'chào',
            'こんにちは', 'こんばんは', 'おはよう', 'やあ', 'もしもし', 'ごきげんよう', 'お疲れ様', 'おっす', 'お元気ですか', 'ご挨拶'
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
            'sản phẩm', 'tìm', 'tìm kiếm', 'hoa', 'quà', 'quà tặng',
            '花','はな','商品', '商品一覧', '探す', '検索', '見る', '表示', '花', '石鹸', 'ギフト', '贈り物', 'プレゼント', '購入', '買う', 'アイテム', '在庫', '販売', 'おすすめ', '提案', '新商品', '誕生日', '記念日', '結婚', 'バレンタイン', '母の日', 'クリスマス'
        ])) {
            return $this->handleProductSearch($message);
        }

        // Enhanced Category Browsing Intent
        if ($this->containsWords($message, [
            'category', 'categories', 'type', 'types', 'kind', 'kinds', 'collection', 'collections',
            'section', 'sections', 'what do you sell', 'what\'s available', 'browse', 'explore',
            'menu', 'catalog', 'range', 'variety', 'selection',
            'danh mục', 'loại', 'phân loại',
            'カテゴリ', 'カテゴリー', '種類', 'タイプ', 'コレクション', 'メニュー', 'カタログ', '分類', 'セクション', '一覧'
        ])) {
            return $this->handleCategoryQuery();
        }

        // Enhanced Order Inquiry Intent
        if ($this->containsWords($message, [
            'order', 'orders', 'purchase', 'purchases', 'buy', 'bought', 'checkout', 'cart',
            'track', 'tracking', 'status', 'delivery', 'shipped', 'delivered',
            'my order', 'order status', 'where is my', 'when will', 'receipt', 'confirmation',
            'đơn hàng', 'mua', 'thanh toán',
            '注文', '注文履歴', '購入', 'カート', 'チェックアウト', '追跡', '配送', '配達', '発送', 'ステータス', '領収書', '確認', '支払い'
        ])) {
            return $this->handleOrderQuery();
        }

        // Enhanced News and Content Intent
        if ($this->containsWords($message, [
            'news', 'blog', 'post', 'posts', 'article', 'articles', 'update', 'updates',
            'latest', 'new', 'recent', 'what\'s new', 'announcements', 'events',
            'tin tức', 'bài viết',
            'ニュース', 'ブログ', '投稿', '記事', 'アップデート', '最新', '新着', 'イベント', 'お知らせ'
        ])) {
            return $this->handleNewsQuery();
        }

        // Enhanced Pricing Intent
        if ($this->containsWords($message, [
            'price', 'prices', 'cost', 'costs', 'expensive', 'cheap', 'affordable',
            'how much', 'pricing', 'budget', 'range', 'fee', 'charge', 'money',
            'discount', 'sale', 'offer', 'promotion', 'deal', 'deals',
            'giá', 'bao nhiêu', 'chi phí',
            '値段', '価格', '費用', '高い', '安い', 'お得', '割引', 'セール', 'プロモーション', 'ディール', 'いくら', '料金', '金額', '予算'
        ])) {
            return $this->handlePriceQuery($message);
        }

        // Enhanced Store Information Intent
        if ($this->containsWords($message, [
            'store', 'shop', 'location', 'address', 'contact', 'phone', 'email',
            'hours', 'open', 'close', 'where', 'find you', 'visit', 'directions',
            'about', 'information', 'details', 'business hours',
            'cửa hàng', 'địa chỉ', 'liên hệ',
            '店舗', 'ショップ', '場所', '住所', '連絡先', '電話', 'メール', '営業時間', '開店', '閉店', 'どこ', '案内', '訪問', '詳細', 'インフォメーション'
        ])) {
            return $this->handleStoreInfo();
        }

        // Enhanced Shipping Information Intent
        if ($this->containsWords($message, [
            'ship', 'shipping', 'delivery', 'deliver', 'send', 'transport',
            'freight', 'courier', 'post', 'mail', 'fast delivery', 'express',
            'same day', 'overnight', 'free shipping', 'shipping cost', 'shipping fee',
            'giao hàng', 'vận chuyển',
            '配送', '配達', '発送', '送料', '宅配', '宅急便', '速達', '当日配送', '翌日配送', '無料配送', '運送', '運輸', '郵送', '郵便'
        ])) {
            return $this->handleShippingInfo();
        }

        // Enhanced Payment Information Intent
        if ($this->containsWords($message, [
            'payment', 'pay', 'paying', 'card', 'cash', 'bank', 'transfer',
            'method', 'methods', 'option', 'options', 'credit', 'debit',
            'wallet', 'installment', 'secure', 'safe', 'payment methods',
            'thanh toán', 'tiền',
            '支払い', '決済', 'カード', '現金', '銀行', '振込', '方法', 'オプション', 'クレジット', 'デビット', 'ウォレット', '分割', '安全', 'セキュア'
        ])) {
            return $this->handlePaymentInfo();
        }

        // Enhanced Help Intent
        if ($this->containsWords($message, [
            'help', 'assist', 'support', 'guide', 'instruction', 'how to',
            'tutorial', 'explain', 'confused', 'don\'t understand', 'stuck',
            'problem', 'issue', 'trouble', 'difficulty', 'assistance',
            'giúp', 'hướng dẫn', 'hỗ trợ',
            '助けて', 'サポート', 'ガイド', '案内', '説明', '困った', '分からない', '問題', 'トラブル', '支援', '手伝い', '教えて'
        ])) {
            return $this->getHelpResponse();
        }

        // Enhanced Popular Products Intent
        if ($this->containsWords($message, [
            'popular', 'bestseller', 'best selling', 'trending', 'hot', 'favorite', 'favorites',
            'top', 'most', 'recommended', 'featured', 'highlighted', 'star', 'bestsellers',
            'bán chạy', 'nổi bật',
            '人気', '売れ筋', 'おすすめ', '注目', '話題', '特集', 'ランキング', 'トップ', 'ベストセラー', '人気商品'
        ])) {
            return $this->handlePopularProducts();
        }

        // Gift Suggestion Intent
        if ($this->containsWords($message, [
            'gift', 'present', 'surprise', 'for her', 'for him', 'for mom',
            'for dad', 'for wife', 'for husband', 'for girlfriend', 'for boyfriend',
            'romantic', 'love', 'special occasion', 'gift ideas',
            'ギフト', 'プレゼント', '贈り物', 'サプライズ', '彼女', '彼氏', '母', '父', '妻', '夫', '恋人', 'ロマンチック', '愛', '特別な日', 'ギフトアイデア'
        ])) {
            return $this->handleGiftSuggestions($message);
        }

        // Availability Intent
        if ($this->containsWords($message, [
            'available', 'in stock', 'out of stock', 'when available',
            'restock', 'inventory', 'quantity', 'left', 'remaining', 'stock',
            '在庫', '入荷', '在庫あり', '在庫切れ', '残り', '数量', '再入荷', 'ストック', '販売中', '品切れ'
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
        return __('chatbot.greeting');
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
            'hoa', 'sáp', 'quà', 'tươi', 'đặc biệt'
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
            return __('chatbot.no_products_found', [
                'products_url' => route('user.products.index'),
                'phone' => config('constants.shop_phone')
            ]);
        }

        $response = __('chatbot.products_search_results') . "\n\n";
        foreach ($products as $product) {
            $response .= "💝 **{$product->name}**\n";
            $response .= "📂 " . __('common.category') . ": {$product->category->name}\n";
            $response .= "💰 " . __('common.price') . ": \${$product->price}\n";
            $response .= "📦 " . __('common.stock') . ": {$product->stock_quantity} " . __('common.available') . "\n";
            $response .= "👀 " . __('common.views') . ": {$product->view_count}\n";
            $response .= "🔗 " . route('user.products.show', $product->id) . "\n\n";
        }

        $response .= __('chatbot.browse_more_products', [
            'products_url' => route('user.products.index'),
            'phone' => config('constants.shop_phone')
        ]);

        return $response;
    }

    /**
     * Category Query Handler
     */
    private function handleCategoryQuery()
    {
        $categories = Category::withCount('product')->get();

        if ($categories->count() === 0) {
            return __('chatbot.no_categories_found', [
                'products_url' => route('user.products.index'),
                'phone' => config('constants.shop_phone')
            ]);
        }

        $response = __('chatbot.product_categories') . "\n\n";
        foreach ($categories as $category) {
            $response .= "🌟 **{$category->name}**\n";
            $response .= "📦 {$category->product_count} " . __('common.products_available') . "\n";
            $response .= "🔗 " . route('user.products.index', ['category' => $category->id]) . "\n\n";
        }

        $response .= __('chatbot.popular_categories');

        return $response;
    }

    /**
     * Order Query Handler
     */
    private function handleOrderQuery()
    {
        try {
            if (!Auth::check()) {
                return __('chatbot.login_required', [
                    'login_url' => route('login'),
                    'phone' => config('constants.shop_phone'),
                    'email' => config('constants.shop_email')
                ]);
            }

            $orders = Order::where('user_id', Auth::id())->latest()->take(3)->get();

            if ($orders->count() === 0) {
                return __('chatbot.no_orders_found', [
                    'products_url' => route('user.products.index'),
                    'phone' => config('constants.shop_phone')
                ]);
            }

            $response = __('chatbot.recent_orders') . "\n\n";
            foreach ($orders as $order) {
                $response .= "🛍️ **" . __('common.order') . " #{$order->id}**\n";
                $response .= "📅 " . __('common.date') . ": " . $order->created_at->format('M d, Y') . "\n";
                $response .= "💰 " . __('common.total') . ": \${$order->total_amount}\n";
                $response .= "📋 " . __('common.status') . ": " . $this->translateStatus($order->status) . "\n";
                if (Route::has('order.show')) {
                    $response .= "🔗 " . route('order.show', $order->id) . "\n\n";
                } else {
                    $response .= "\n";
                }
            }

            $response .= __('chatbot.order_support', [
                'phone' => config('constants.shop_phone'),
                'email' => config('constants.shop_email')
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Order Query Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return __('chatbot.order_technical_error', [
                'phone' => config('constants.shop_phone'),
                'email' => config('constants.shop_email')
            ]);
        }
    }

    /**
     * News Query Handler
     */
    private function handleNewsQuery()
    {
        $posts = Post::where('status', true)
            ->latest()
            ->take(3)
            ->get();

        if ($posts->count() === 0) {
            return __('chatbot.no_news_found', [
                'phone' => config('constants.shop_phone')
            ]);
        }

        $response = __('chatbot.latest_news') . "\n\n";

        foreach ($posts as $index => $post) {
            // Clean and ensure UTF-8 encoding
            $title = mb_convert_encoding($post->title, 'UTF-8', 'UTF-8');
            $content = html_entity_decode(strip_tags($post->content));
            // Remove extra whitespace and carriage returns
            $content = preg_replace('/\s+/', ' ', $content);
            $content = trim($content);
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
            
            $response .= "📝 **{$title}**\n";
            $response .= "📅 " . $post->created_at->format('M d, Y') . "\n";
            $response .= "📖 " . mb_substr($content, 0, 100) . "...\n";
            if (Route::has('posts.show')) {
                $response .= "🔗 " . route('posts.show', $post->id) . "\n\n";
            } else {
                $response .= "\n";
            }
        }

        $response .= __('chatbot.hot_topics');

        return $response;
    }

    /**
     * Price Query Handler
     */
    private function handlePriceQuery($message)
    {
        return __('chatbot.price_info');
    }

    /**
     * Store Information Handler
     */
    private function handleStoreInfo()
    {
        return __('chatbot.store_info');
    }

    /**
     * Shipping Information Handler
     */
    private function handleShippingInfo()
    {
        return __('chatbot.shipping_info');
    }

    /**
     * Payment Information Handler
     */
    private function handlePaymentInfo()
    {
        return __('chatbot.payment_info');
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
            return __('chatbot.no_popular_products', [
                'products_url' => route('user.products.index'),
                'phone' => config('constants.shop_phone')
            ]);
        }

        $response = __('chatbot.top_bestselling') . "\n\n";
        foreach ($popularProducts as $index => $product) {
            $medalEmoji = $index === 0 ? '🥇' : ($index === 1 ? '🥈' : '🥉');
            $response .= "{$medalEmoji} **{$product->name}**\n";
            $response .= "📂 " . __('common.category') . ": {$product->category->name}\n";
            $response .= "💰 " . __('common.price') . ": \${$product->price}\n";
            $response .= "👀 {$product->view_count} " . __('common.customers_viewed') . "\n";
            $response .= "🔗 " . route('user.products.show', $product->id) . "\n\n";
        }

        $response .= __('chatbot.why_customers_love');

        return $response;
    }

    /**
     * Help Response Handler
     */
    private function getHelpResponse()
    {
        return __('chatbot.help');
    }

    /**
     * Enhanced Default Response
     */
    private function getEnhancedDefaultResponse()
    {
        return __('chatbot.default');
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
            return __('chatbot.gift_suggestions', [
                'products_url' => route('user.products.index'),
                'phone' => config('constants.shop_phone')
            ]);
        }

        $response = __('chatbot.perfect_gifts') . "\n\n";
        foreach ($giftProducts as $product) {
            $response .= "💝 **{$product->name}**\n";
            $response .= "💰 " . __('common.price') . ": \${$product->price}\n";
            $response .= "📦 " . __('common.in_stock') . ": {$product->stock_quantity} " . __('common.items') . "\n";
            $response .= "🔗 " . route('user.products.show', $product->id) . "\n\n";
        }

        $response .= __('chatbot.why_gifts_special');

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

        $response = __('chatbot.availability_info') . "\n\n";

        if ($lowStockProducts->count() > 0) {
            $response .= "⚠️ **" . __('chatbot.limited_stock') . ":**\n";
            foreach ($lowStockProducts as $product) {
                $response .= "• {$product->name} - " . __('chatbot.only_left', ['count' => $product->stock_quantity]) . "\n";
            }
            $response .= "\n";
        }

        if ($outOfStockProducts->count() > 0) {
            $response .= "❌ **" . __('chatbot.out_of_stock') . ":**\n";
            foreach ($outOfStockProducts as $product) {
                $response .= "• {$product->name} - " . __('chatbot.restock_soon') . "\n";
            }
            $response .= "\n";
        }

        $response .= __('chatbot.stock_updates', [
            'phone' => config('constants.shop_phone')
        ]);

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
        $statusKey = "chatbot.status.{$status}";
        return __($statusKey, [], 'en');
    }
}
