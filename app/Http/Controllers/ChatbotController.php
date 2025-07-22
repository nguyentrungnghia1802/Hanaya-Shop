<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Post;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $message = trim(strtolower($request->input('message', '')));

        if (empty($message)) {
            return response()->json([
                'response' => 'Xin chào! Tôi có thể giúp gì cho bạn hôm nay?'
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

        // Product search
        if ($this->containsWords($message, ['sản phẩm', 'tìm', 'tìm kiếm', 'product'])) {
            return $this->handleProductSearch($message);
        }

        // Categories
        if ($this->containsWords($message, ['danh mục', 'category', 'loại'])) {
            return $this->handleCategoryQuery();
        }

        // Order inquiry
        if ($this->containsWords($message, ['đơn hàng', 'order', 'mua'])) {
            return $this->handleOrderQuery();
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
        if ($this->containsWords($message, ['cửa hàng', 'store', 'shop', 'địa chỉ', 'liên hệ'])) {
            return $this->handleStoreInfo();
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
            return "Không tìm thấy sản phẩm nào. Bạn có thể xem tất cả sản phẩm tại: " . route('product.index');
        }

        $response = "Đây là một số sản phẩm phù hợp:\n\n";
        foreach ($products as $product) {
            $response .= "🌸 {$product->name}\n";
            $response .= "💰 " . number_format($product->price, 0, ',', '.') . "₫\n";
            $response .= "📋 {$product->category->name}\n";
            $response .= "🔗 " . route('product.show', $product->id) . "\n\n";
        }

        $response .= "Xem thêm sản phẩm tại: " . route('product.index');

        return $response;
    }

    private function handleCategoryQuery()
    {
        $categories = Category::withCount('product')->get();

        if ($categories->count() === 0) {
            return "Hiện tại chưa có danh mục sản phẩm nào.";
        }

        $response = "Chúng tôi có các danh mục sản phẩm sau:\n\n";
        foreach ($categories as $category) {
            $response .= "📂 {$category->name} ({$category->product_count} sản phẩm)\n";
            $response .= "🔗 " . route('product.index', ['category' => $category->id]) . "\n\n";
        }

        return $response;
    }

    private function handleOrderQuery()
    {
        if (!Auth::check()) {
            return "Để xem thông tin đơn hàng, bạn cần đăng nhập tại: " . route('login') .
                "\n\nNếu bạn muốn mua hàng, hãy xem sản phẩm tại: " . route('product.index');
        }

        $orders = Order::where('user_id', Auth::id())->latest()->take(3)->get();

        if ($orders->count() === 0) {
            return "Bạn chưa có đơn hàng nào. Hãy khám phá sản phẩm của chúng tôi tại: " . route('product.index');
        }

        $response = "Đây là các đơn hàng gần đây của bạn:\n\n";
        foreach ($orders as $order) {
            $response .= "📦 Đơn hàng #{$order->id}\n";
            $response .= "💰 " . number_format($order->total_amount, 0, ',', '.') . "₫\n";
            $response .= "📅 {$order->created_at->format('d/m/Y H:i')}\n";
            $response .= "🔄 Trạng thái: " . $this->translateStatus($order->status) . "\n\n";
        }

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
            return "📝 Hiện tại chưa có bài viết nào được đăng.\n\n" .
                "Hãy quay lại sau để cập nhật tin tức mới nhất từ Hanaya Shop! 🌸";
        }

        $response = "📰 **Tin tức & Bài viết mới nhất từ Hanaya Shop:**\n\n";

        foreach ($posts as $index => $post) {
            $response .= "� **" . ($index + 1) . ". " . $post->title . "**\n";
            $response .= "📅 Ngày đăng: " . $post->created_at->format('d/m/Y H:i') . "\n";
            $response .= "✍️ Tác giả: " . ($post->author->name ?? 'Admin Hanaya Shop') . "\n";

            // Lấy 150 ký tự đầu của nội dung
            $excerpt = strip_tags($post->content);
            $excerpt = mb_strlen($excerpt) > 150 ? mb_substr($excerpt, 0, 150) . '...' : $excerpt;
            $response .= "📖 Tóm tắt: " . $excerpt . "\n\n";
        }

        $response .= "🌸 **Mẹo hay:**\n";
        $response .= "• Theo dõi blog của chúng tôi để cập nhật xu hướng hoa trang trí mới nhất\n";
        $response .= "• Tìm hiểu cách chăm sóc và bảo quản sản phẩm hoa\n";
        $response .= "• Khám phá ý tưởng trang trí và quà tặng độc đáo\n\n";
        $response .= "💡 Truy cập website để đọc toàn bộ bài viết và khám phá thêm nhiều nội dung thú vị!";

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
