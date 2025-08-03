<?php
/**
 * AI Chatbot Controller
 * 
 * This controller provides intelligent chatbot functionality for customer support
 * in the Hanaya Shop e-commerce application. It processes natural language queries
 * and provides contextual responses about products, orders, services, and store information.
 * 
 * Key Features:
 * - Natural language processing for customer queries
 * - Product search and recommendation
 * - Order status inquiry and management
 * - Store information and service details
 * - Dynamic response generation with real-time data
 * - Multi-language support (Vietnamese and English)
 * 
 * Supported Query Categories:
 * - Greetings and welcome messages
 * - Product search and filtering
 * - Category information and browsing
 * - Order tracking and inquiry
 * - News and blog content
 * - Pricing and payment information
 * - Shipping and delivery details
 * - Store contact and location
 * - Help and guidance
 * 
 * Response Features:
 * - Rich formatted responses with emojis
 * - Direct links to relevant pages
 * - Real-time data from database
 * - Personalized content based on user authentication
 * - Fallback responses for unrecognized queries
 * 
 * @package App\Http\Controllers
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;           // HTTP request handling
use App\Models\Product\Product;       // Product model for search and recommendations
use App\Models\Product\Category;      // Category model for browsing
use App\Models\Post;                  // Post model for news and blog content
use App\Models\Order\Order;           // Order model for order tracking
use Illuminate\Support\Facades\Auth;  // Authentication services for personalized responses

/**
 * Chatbot Controller Class
 * 
 * Handles AI-powered customer support through natural language processing
 * and intelligent response generation. Provides comprehensive assistance
 * for customer inquiries about products, orders, and services.
 */
class ChatbotController extends Controller
{
    /**
     * Main Chat Handler
     * 
     * Processes incoming chat messages and generates appropriate responses.
     * Handles message preprocessing, intent detection, and response generation.
     * Provides fallback greeting for empty messages.
     * 
     * Processing Flow:
     * - Input sanitization and normalization
     * - Message intent classification
     * - Context-aware response generation
     * - JSON response formatting
     * 
     * @param \Illuminate\Http\Request $request HTTP request containing user message
     * @return \Illuminate\Http\JsonResponse JSON response with chatbot reply
     */
    public function chat(Request $request)
    {
        // Message Preprocessing
        /**
         * Input Processing - Clean and normalize user input
         * Trims whitespace and converts to lowercase for consistent processing
         * Handles empty messages with default greeting response
         */
        $message = trim(strtolower($request->input('message', '')));

        // Empty Message Handling
        /**
         * Default Greeting - Handle empty or invalid messages
         * Returns configured greeting message for engagement
         * Ensures users always receive a helpful response
         */
        if (empty($message)) {
            return response()->json([
                'response' => config('constants.chatbot_greeting')
            ]);
        }

        // Message Processing
        /**
         * Intent Detection and Response Generation
         * Routes message to appropriate handler based on detected intent
         * Generates contextual response with relevant information
         */
        $response = $this->processMessage($message);

        // Response Formatting
        /**
         * JSON Response - Format response for frontend consumption
         * Consistent response structure for client-side handling
         * Enables seamless chat interface integration
         */
        return response()->json([
            'response' => $response
        ]);
    }

    /**
     * Message Processing and Intent Detection
     * 
     * Analyzes user messages to detect intent and generate appropriate responses.
     * Uses keyword matching and pattern recognition to classify user queries
     * and route them to specialized response handlers.
     * 
     * Intent Categories:
     * - Greetings and welcome
     * - Product search and recommendations
     * - Category browsing
     * - Order inquiries
     * - News and content
     * - Pricing information
     * - Store details
     * - Shipping information
     * - Payment methods
     * - Help and support
     * 
     * @param string $message Preprocessed user message
     * @return string Generated response text with formatting
     */
    private function processMessage($message)
    {
        // Greeting Detection
        /**
         * Welcome Intent - Detect greeting messages
         * Responds to hello, hi, and Vietnamese greetings
         * Provides welcoming response with service overview
         */
        if ($this->containsWords($message, ['xin chào', 'chào', 'hello', 'hi', 'hey'])) {
            return $this->getGreetingResponse();
        }

        // Product Search Intent
        /**
         * Product Search - Handle product and gift inquiries
         * Detects product-related keywords in multiple languages
         * Routes to product search and recommendation system
         */
        if ($this->containsWords($message, ['sản phẩm', 'tìm', 'tìm kiếm', 'product', 'hoa', 'quà', 'gift'])) {
            return $this->handleProductSearch($message);
        }

        // Category Browsing Intent
        /**
         * Category Information - Handle category and classification queries
         * Provides category listing and product organization information
         * Helps users navigate product catalog efficiently
         */
        if ($this->containsWords($message, ['danh mục', 'category', 'loại', 'phân loại'])) {
            return $this->handleCategoryQuery();
        }

        // Order Inquiry Intent
        /**
         * Order Management - Handle order tracking and purchase queries
         * Provides order status, history, and checkout assistance
         * Requires authentication for personalized order information
         */
        if ($this->containsWords($message, ['đơn hàng', 'order', 'mua', 'thanh toán', 'checkout'])) {
            return $this->handleOrderQuery();
        }

        // News and Content Intent
        /**
         * Content Information - Handle news, blog, and article requests
         * Provides latest posts, articles, and company updates
         * Keeps customers informed about products and promotions
         */
        if ($this->containsWords($message, ['tin tức', 'bài viết', 'news', 'post', 'blog'])) {
            return $this->handleNewsQuery();
        }

        // Pricing Intent
        /**
         * Price Information - Handle pricing and cost inquiries
         * Provides product pricing, ranges, and cost information
         * Includes promotional pricing and discount details
         */
        if ($this->containsWords($message, ['giá', 'price', 'bao nhiêu', 'chi phí', 'cost'])) {
            return $this->handlePriceQuery($message);
        }

        // Store Information Intent
        /**
         * Store Details - Handle store location and contact queries
         * Provides address, contact information, and business hours
         * Includes service details and store policies
         */
        if ($this->containsWords($message, ['cửa hàng', 'store', 'shop', 'địa chỉ', 'liên hệ', 'contact'])) {
            return $this->handleStoreInfo();
        }

        // Shipping Information Intent
        /**
         * Delivery Information - Handle shipping and delivery queries
         * Provides shipping costs, timeframes, and delivery policies
         * Includes tracking and logistics information
         */
        if ($this->containsWords($message, ['giao hàng', 'ship', 'delivery', 'vận chuyển'])) {
            return $this->handleShippingInfo();
        }

        // Payment Information Intent
        /**
         * Payment Methods - Handle payment and billing inquiries
         * Provides available payment options and processing information
         * Includes security and payment policy details
         */
        if ($this->containsWords($message, ['thanh toán', 'payment', 'pay', 'tiền'])) {
            return $this->handlePaymentInfo();
        }

        // Help Intent
        /**
         * Support Request - Handle help and guidance requests
         * Provides usage instructions and support information
         * Offers guidance on effective chatbot interaction
         */
        if ($this->containsWords($message, ['help', 'giúp', 'hướng dẫn', 'hỗ trợ', 'support'])) {
            return $this->getHelpResponse();
        }

        // Popular Products Intent
        /**
         * Trending Products - Handle popular and bestselling product queries
         * Provides top-selling products and customer favorites
         * Includes popularity metrics and recommendations
         */
        if ($this->containsWords($message, ['bán chạy', 'popular', 'hot', 'bestseller', 'nổi bật'])) {
            return $this->handlePopularProducts();
        }

        // Fallback Response
        /**
         * Unrecognized Intent - Handle unknown or unclear messages
         * Provides helpful suggestions and guidance for better queries
         * Ensures users receive useful response even for unclear input
         */
        return $this->getDefaultResponse();
    }

    /**
     * Generate Greeting Response
     * 
     * Provides welcoming messages with service overview and engagement prompts.
     * Rotates between different greeting styles to maintain conversation freshness.
     * Includes service highlights and direct links to key features.
     * 
     * Response Features:
     * - Multiple greeting variations for diversity
     * - Service capability overview
     * - Direct navigation links
     * - Engagement prompts for interaction
     * - Emoji formatting for visual appeal
     * 
     * @return string Formatted greeting response with service information
     */
    private function getGreetingResponse()
    {
        // Greeting Response Variations
        /**
         * Multiple Greeting Options - Provide variety in welcome messages
         * Each greeting includes different service highlights and engagement styles
         * Random selection ensures fresh experience for returning users
         */
        $greetings = [
            "🌸 **Chào mừng bạn đến với Hanaya Shop!**\n\nTôi là trợ lý ảo, sẵn sàng hỗ trợ bạn:\n\n✨ **Dịch vụ của tôi:**\n🔍 Tìm kiếm sản phẩm\n📦 Kiểm tra đơn hàng\n🏪 Thông tin cửa hàng\n📰 Tin tức & khuyến mãi\n💡 Tư vấn sản phẩm\n\n**Bạn muốn tôi giúp gì hôm nay?** 😊",

            "🌺 **Xin chào! Rất vui được gặp bạn!**\n\n🎯 **Tôi có thể giúp bạn:**\n• Tìm hoa xà phòng đẹp nhất\n• Chọn quà tặng ý nghĩa\n• Kiểm tra tình trạng đơn hàng\n• Tư vấn sản phẩm phù hợp\n\n🔗 " . route('user.products.index') . "\n\n**Hãy cho tôi biết bạn đang quan tâm đến gì nhé!** 🌸",

            "🌹 **Chào bạn! Welcome to Hanaya Shop!**\n\n🎊 **Hôm nay có gì đặc biệt:**\n• Bộ sưu tập hoa xà phòng mới\n• Quà tặng Valentine độc đáo\n• Miễn phí giao hàng đơn từ 100 USD\n\n💬 **Hỏi tôi bất cứ điều gì về:**\nSản phẩm, giá cả, giao hàng, khuyến mãi...\n\n**Bắt đầu cuộc trò chuyện nào!** ✨"
        ];

        // Random Greeting Selection
        /**
         * Greeting Rotation - Select random greeting for variety
         * Prevents repetitive responses and maintains user engagement
         * Each greeting provides complete service overview
         */
        return $greetings[array_rand($greetings)];
    }

    /**
     * Handle Product Search Queries
     * 
     * Processes product search requests with intelligent keyword matching and filtering.
     * Provides comprehensive product information including pricing, availability, and links.
     * Includes search suggestions and shopping guidance for enhanced user experience.
     * 
     * Search Features:
     * - Keyword extraction from natural language
     * - Multi-field search across product data
     * - Category-based filtering
     * - Popularity-based sorting
     * - Stock availability validation
     * 
     * Response Elements:
     * - Product details with pricing
     * - Category information
     * - View count and popularity metrics
     * - Direct product links
     * - Search suggestions for improvement
     * 
     * @param string $message User search query
     * @return string Formatted product search results
     */
    private function handleProductSearch($message)
    {
        // Keyword Extraction
        /**
         * Search Term Identification - Extract relevant keywords from user message
         * Supports both Vietnamese and English search terms
         * Covers product types, features, and categories
         */
        $keywords = ['hoa', 'xà phòng', 'soap', 'flower', 'quà', 'gift', 'souvenir', 'tươi', 'fresh', 'đặc biệt', 'special'];
        $foundKeywords = [];

        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                $foundKeywords[] = $keyword;
            }
        }

        // Product Query Construction
        /**
         * Database Query Building - Construct search query with filters
         * Includes product relationships and stock validation
         * Limits results for optimal response formatting
         */
        $query = Product::with('category')->where('stock_quantity', '>', 0)->take(3);

        // Keyword-Based Filtering
        /**
         * Search Filtering - Apply keyword filters when available
         * Searches across product name, description, and category
         * Uses OR logic for broader search results
         */
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

        // Execute Search Query
        /**
         * Query Execution - Get products ordered by popularity
         * Sorts by view count to prioritize popular products
         * Returns collection for result processing
         */
        $products = $query->orderBy('view_count', 'desc')->get();

        // No Results Handling
        /**
         * Empty Results Response - Handle queries with no matching products
         * Provides alternative suggestions and search guidance
         * Includes navigation links and search improvement tips
         */
        if ($products->count() === 0) {
            return "🔍 **Không tìm thấy sản phẩm phù hợp**\n\n"
                . "Có thể bạn quan tâm đến:\n"
                . "🌸 Hoa xà phòng: Bền đẹp, thơm nhẹ\n"
                . "🌺 Hoa tươi: Tự nhiên, rực rỡ\n"
                . "🎁 Quà lưu niệm: Ý nghĩa, độc đáo\n\n"
                . "🔗 " . route('user.products.index') . "\n\n"
                . "💡 **Gợi ý tìm kiếm:**\n"
                . "• 'hoa xà phòng hồng'\n"
                . "• 'quà tặng sinh nhật'\n"
                . "• 'hoa tươi cưới'";
        }

        // Results Formatting
        /**
         * Product Information Display - Format search results for presentation
         * Includes comprehensive product details and navigation links
         * Provides shopping guidance and contact information
         */
        $response = "🌸 **Sản phẩm phù hợp với yêu cầu của bạn:**\n\n";
        foreach ($products as $product) {
            $response .= "🌺 **{$product->name}**\n";
            $response .= "💰 " . number_format($product->price, 0, ',', '.') . " USD\n";
            $response .= "📂 {$product->category->name}\n";
            $response .= "👁️ {$product->view_count} lượt xem\n";
            $response .= "📦 Còn lại: {$product->stock_quantity} sản phẩm\n";
            $response .= "🔗 " . route('user.products.show', $product->id) . "\n\n";
        }

        // Additional Information
        /**
         * Shopping Guidance - Provide additional navigation and shopping tips
         * Includes catalog link and shopping best practices
         * Offers contact information for personalized assistance
         */
        $response .= "✨ **Xem thêm sản phẩm:**\n";
        $response .= "🔗 " . route('user.products.index') . "\n\n";

        $response .= "💡 **Mẹo mua sắm:**\n";
        $response .= "• Kiểm tra số lượng tồn kho trước khi đặt hàng\n";
        $response .= "• Đọc mô tả sản phẩm để chọn đúng kích thước\n";
        $response .= "• Liên hệ hotline nếu cần tư vấn: " . config('constants.shop_phone');

        return $response;
    }

    private function handleCategoryQuery()
    {
        $categories = Category::withCount('product')->get();

        if ($categories->count() === 0) {
            return "📂 **Hiện tại chưa có danh mục sản phẩm nào.**\n\nVui lòng quay lại sau!";
        }

        $response = "📂 **Danh mục sản phẩm tại Hanaya Shop:**\n\n";
        foreach ($categories as $category) {
            $response .= "🌸 **{$category->name}**\n";
            $response .= "📊 {$category->product_count} sản phẩm có sẵn\n";
            $response .= "🔗 " . route('user.products.index', ['category' => $category->id]) . "\n\n";
        }

        $response .= "🎯 **Danh mục phổ biến:**\n";
        $response .= "🧼 Hoa xà phòng - Bền đẹp, thơm lâu\n";
        $response .= "🌺 Hoa tươi - Tự nhiên, rực rỡ sắc màu\n";
        $response .= "🎁 Quà lưu niệm - Ý nghĩa, đáng nhớ\n\n";
        $response .= "💝 **Gợi ý:** Chọn theo dịp đặc biệt như sinh nhật, cưới hỏi, kỷ niệm...";

        return $response;
    }

    private function handleOrderQuery()
    {
        if (!Auth::check()) {
            return "🔐 **Vui lòng đăng nhập để kiểm tra đơn hàng**\n\n"
                . "🌟 **Lợi ích khi đăng nhập:**\n"
                . "• Theo dõi đơn hàng realtime\n"
                . "• Lưu địa chỉ giao hàng\n"
                . "• Nhận thông báo khuyến mãi\n"
                . "• Tích điểm thành viên\n\n"
                . "🔗 " . route('login') . "\n\n"
                . "📞 **Hỗ trợ:** " . config('constants.shop_phone') . "\n"
                . "📧 **Email:** " . config('constants.shop_email');
        }

        $orders = Order::where('user_id', Auth::id())->latest()->take(3)->get();

        if ($orders->count() === 0) {
            return "📦 **Bạn chưa có đơn hàng nào**\n\n"
                . "🛒 **Bắt đầu mua sắm ngay:**\n"
                . "🔗 " . route('user.products.index') . "\n\n"
                . "🎁 **Ưu đãi đặc biệt:**\n"
                . "• Miễn phí ship đơn từ 100 USD\n"
                . "• Tặng thiệp chúc mừng\n"
                . "• Bao bì sang trọng miễn phí";
        }

        $response = "📦 **Đơn hàng gần đây của bạn:**\n\n";
        foreach ($orders as $order) {
            $response .= "🏷️ **#{$order->id}**\n";
            $response .= "📅 " . $order->created_at->format('d/m/Y H:i') . "\n";
            $response .= "💰 " . number_format($order->total_price, 0, ',', '.') . " USD\n";
            $response .= "📊 Trạng thái: " . $this->translateStatus($order->status) . "\n";

            if ($order->status === 'processing') {
                $response .= "🚚 Đang chuẩn bị hàng cho bạn...\n";
            } elseif ($order->status === 'completed') {
                $response .= "✅ Giao hàng thành công!\n";
            }
            $response .= "\n";
        }

        $response .= "🔍 **Cần hỗ trợ thêm?**\n";
        $response .= "📞 Hotline: " . config('constants.shop_phone') . "\n";
        $response .= "📧 Email: " . config('constants.shop_email') . "\n";
        $response .= "⏰ Thời gian hỗ trợ: 8:00 - 22:00 hàng ngày";

        return $response;
    }

    private function handleNewsQuery()
    {
        $posts = Post::where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        if ($posts->count() === 0) {
            return "📰 **Hiện tại chưa có tin tức mới**\n\n"
                . "🌸 **Theo dõi chúng tôi để cập nhật:**\n"
                . "• Xu hướng hoa trang trí mới nhất\n"
                . "• Mẹo chăm sóc và bảo quản\n"
                . "• Ý tưởng quà tặng độc đáo\n"
                . "• Khuyến mãi đặc biệt\n\n"
                . "🔔 **Đăng ký nhận thông báo để không bỏ lỡ!**";
        }

        $response = "📰 **Tin tức & Bài viết mới nhất:**\n\n";

        foreach ($posts as $index => $post) {
            $response .= "📝 **" . ($index + 1) . ". {$post->title}**\n";
            $response .= "📅 " . $post->created_at->format('d/m/Y') . "\n";

            // Truncate excerpt
            $excerpt = strlen($post->excerpt) > 100 ? substr($post->excerpt, 0, 100) . '...' : $post->excerpt;
            $response .= "📄 {$excerpt}\n";
            $response .= "🔗 " . route('posts.show', $post->id) . "\n\n";
        }

        $response .= "🌸 **Chủ đề hot:**\n";
        $response .= "• Cách chọn hoa phù hợp với từng dịp\n";
        $response .= "• Bí quyết bảo quản hoa xà phòng\n";
        $response .= "• Ý tưởng trang trí nhà cửa với hoa\n";
        $response .= "• Xu hướng quà tặng 2025\n\n";
        $response .= "💡 **Đọc thêm tại website để khám phá nhiều bài viết thú vị!**";

        return $response;
    }

    private function handlePriceQuery($message)
    {
        $priceRanges = [
            'budget' => ['từ 5 USD', 'rẻ', 'tiết kiệm', 'budget'],
            'mid' => ['từ 20 USD', 'trung bình', 'medium'],
            'premium' => ['từ 50 USD', 'cao cấp', 'premium', 'sang trọng']
        ];

        $response = "💰 **Bảng giá sản phẩm Hanaya Shop:**\n\n";
        $response .= "🌸 **Hoa xà phòng:**\n";
        $response .= "• Cơ bản: 5 - 15 USD\n";
        $response .= "• Cao cấp: 20 - 50 USD\n";
        $response .= "• Đặc biệt: 60 - 200 USD\n\n";

        $response .= "🎁 **Quà lưu niệm:**\n";
        $response .= "• Nhỏ gọn: 5 - 20 USD\n";
        $response .= "• Trung bình: 25 - 80 USD\n";
        $response .= "• Cao cấp: 100 - 500 USD\n\n";

        $response .= "💝 **Ưu đãi đặc biệt:**\n";
        $response .= "• 🚚 Miễn phí ship đơn từ 100 USD\n";
        $response .= "• 🎀 Tặng thiệp & bao bì sang trọng\n";
        $response .= "• 🎊 Giảm 10% cho khách hàng thân thiết\n\n";

        $response .= "🔗 " . route('user.products.index') . "\n\n";
        $response .= "📞 **Tư vấn giá:** " . config('constants.shop_phone');

        return $response;
    }

    private function handleStoreInfo()
    {
        return "🏪 **Thông tin cửa hàng Hanaya Shop:**\n\n"
            . "📍 **Địa chỉ:** " . config('constants.shop_address') . "\n"
            . "📞 **Hotline:** " . config('constants.shop_phone') . "\n"
            . "📧 **Email:** " . config('constants.shop_email') . "\n"
            . "🕒 **Giờ làm việc:** 8:00 - 22:00 (Thứ 2 - Chủ nhật)\n\n"
            . "🚚 **Dịch vụ:**\n"
            . "• Giao hàng toàn quốc\n"
            . "• Đóng gói sang trọng miễn phí\n"
            . "• Tư vấn chọn quà 24/7\n"
            . "• Thanh toán đa dạng\n\n"
            . "💳 **Phương thức thanh toán:**\n"
            . "• Tiền mặt khi nhận hàng\n"
            . "• Chuyển khoản ngân hàng\n"
            . "• Thẻ tín dụng/ghi nợ\n"
            . "• Ví điện tử\n\n"
            . "🌟 **Cam kết chất lượng 100%!**";
    }

    private function handleShippingInfo()
    {
        return "🚚 **Thông tin giao hàng:**\n\n"
            . "📦 **Phí giao hàng:**\n"
            . "• Nội thành: " . number_format(config('checkout.shipping_fee'), 0, ',', '.') . " USD\n"
            . "• Ngoại thành: 15 USD\n"
            . "• Miễn phí với đơn từ 100 USD\n\n"
            . "⏱️ **Thời gian giao hàng:**\n"
            . "• Nội thành: 1-2 ngày\n"
            . "• Ngoại thành: 2-3 ngày\n"
            . "• Tỉnh khác: 3-5 ngày\n\n"
            . "📞 **Liên hệ giao hàng:**\n"
            . "• Hotline: " . config('constants.shop_phone') . "\n"
            . "• Email: " . config('constants.shop_email') . "\n\n"
            . "✅ **Đảm bảo:**\n"
            . "• Đóng gói cẩn thận\n"
            . "• Bảo hiểm hàng hóa\n"
            . "• Theo dõi đơn hàng realtime";
    }

    private function handlePaymentInfo()
    {
        return "💳 **Phương thức thanh toán:**\n\n"
            . "🏪 **Thanh toán trực tiếp:**\n"
            . "• Tiền mặt tại cửa hàng\n"
            . "• COD khi nhận hàng\n\n"
            . "🏦 **Chuyển khoản ngân hàng:**\n"
            . "• Vietcombank, Techcombank\n"
            . "• ACB, VPBank\n\n"
            . "💳 **Thẻ tín dụng/ghi nợ:**\n"
            . "• Visa, Mastercard\n"
            . "• JCB, American Express\n\n"
            . "📱 **Ví điện tử:**\n"
            . "• Momo, ZaloPay\n"
            . "• VNPay, ShopeePay\n\n"
            . "🔒 **Bảo mật 100%**\n"
            . "🎁 **Ưu đãi:** Giảm 5% khi thanh toán online\n\n"
            . "📞 **Hỗ trợ:** " . config('constants.shop_phone');
    }

    private function handlePopularProducts()
    {
        $popularProducts = Product::with('category')
            ->where('stock_quantity', '>', 0)
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        if ($popularProducts->count() === 0) {
            return "🔥 **Hiện tại chưa có dữ liệu sản phẩm phổ biến**\n\n"
                . "🌟 **Khám phá bộ sưu tập mới nhất:**\n"
                . "🔗 " . route('user.products.index');
        }

        $response = "🔥 **Top sản phẩm bán chạy nhất:**\n\n";
        foreach ($popularProducts as $index => $product) {
            $response .= "🏆 **" . ($index + 1) . ". {$product->name}**\n";
            $response .= "💰 " . number_format($product->price, 0, ',', '.') . " USD\n";
            $response .= "👁️ {$product->view_count} lượt xem\n";
            $response .= "📂 {$product->category->name}\n";
            $response .= "🔗 " . route('user.products.show', $product->id) . "\n\n";
        }

        $response .= "⭐ **Tại sao khách hàng yêu thích:**\n";
        $response .= "• Chất lượng cao, bền đẹp\n";
        $response .= "• Giá cả hợp lý\n";
        $response .= "• Đóng gói sang trọng\n";
        $response .= "• Dịch vụ tận tâm\n\n";
        $response .= "🛒 **Đặt hàng ngay để nhận ưu đãi!**";

        return $response;
    }

    private function getHelpResponse()
    {
        return "🤖 **Hướng dẫn sử dụng chatbot:**\n\n"
            . "💬 **Cách đặt câu hỏi hiệu quả:**\n"
            . "• 'Tìm hoa xà phòng màu hồng'\n"
            . "• 'Quà tặng sinh nhật dưới 50 USD'\n"
            . "• 'Kiểm tra đơn hàng #123'\n"
            . "• 'Thông tin giao hàng'\n\n"
            . "🔍 **Chủ đề tôi có thể hỗ trợ:**\n"
            . "📦 Sản phẩm & Danh mục\n"
            . "🛒 Đơn hàng & Thanh toán\n"
            . "🚚 Giao hàng & Vận chuyển\n"
            . "🏪 Thông tin cửa hàng\n"
            . "📰 Tin tức & Khuyến mãi\n\n"
            . "💡 **Mẹo:** Hãy mô tả cụ thể nhu cầu để tôi hỗ trợ tốt nhất!\n\n"
            . "📞 **Hỗ trợ trực tiếp:** " . config('constants.shop_phone');
    }

    private function getDefaultResponse()
    {
        $suggestions = [
            "🤔 **Tôi chưa hiểu câu hỏi của bạn.**\n\n🌟 **Gợi ý tìm kiếm:**\n• 'Tìm hoa xà phòng'\n• 'Thông tin cửa hàng'\n• 'Kiểm tra đơn hàng'\n• 'Tin tức mới nhất'\n\n💬 **Hoặc gõ 'help' để được hướng dẫn chi tiết!**",

            "😅 **Xin lỗi, tôi chưa hiểu ý bạn.**\n\n✨ **Bạn có thể hỏi tôi về:**\n🌸 Sản phẩm hoa & quà tặng\n📦 Tình trạng đơn hàng\n💰 Giá cả & khuyến mãi\n🚚 Giao hàng & thanh toán\n\n🎯 **Hãy thử câu hỏi cụ thể hơn nhé!**"
        ];

        return $suggestions[array_rand($suggestions)];
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
