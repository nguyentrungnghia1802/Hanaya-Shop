<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    /**
     * Handle chatbot messages
     */
    public function handleMessage(Request $request): JsonResponse
    {
        $message = $request->input('message', '');
        $response = $this->generateResponse($message);
        
        return response()->json([
            'success' => true,
            'response' => $response
        ]);
    }

    /**
     * Generate chatbot response based on user message
     */
    private function generateResponse(string $message): array
    {
        $message = strtolower($message);
        
        // Product related keywords
        if (str_contains($message, 'sản phẩm') || str_contains($message, 'hoa')) {
            return [
                'message' => '🌸 Chúng tôi có nhiều loại sản phẩm tuyệt vời:',
                'options' => [
                    ['text' => 'Hoa Xà Phòng - Hoa không bao giờ tàn', 'link' => route('user.products.index', ['category' => 1])],
                    ['text' => 'Hoa Tươi - Vẻ đẹp tự nhiên', 'link' => route('user.products.index', ['category' => 2])],
                    ['text' => 'Hoa Đặc Biệt - Độc đáo và ý nghĩa', 'link' => route('user.products.index', ['category' => 3])],
                    ['text' => 'Quà Lưu Niệm - Kỷ niệm đáng nhớ', 'link' => route('user.products.index', ['category' => 4])]
                ]
            ];
        }

        // Category related keywords
        if (str_contains($message, 'danh mục') || str_contains($message, 'loại')) {
            return [
                'message' => '📱 Các danh mục sản phẩm của chúng tôi:',
                'options' => [
                    ['text' => 'Xem tất cả danh mục', 'link' => route('user.products.index')],
                    ['text' => 'Sản phẩm bán chạy', 'link' => route('user.products.index', ['sort' => 'bestseller'])],
                    ['text' => 'Sản phẩm mới nhất', 'link' => route('user.products.index', ['sort' => 'newest'])]
                ]
            ];
        }

        // Cart related keywords
        if (str_contains($message, 'giỏ hàng') || str_contains($message, 'đặt hàng')) {
            return [
                'message' => '🛒 Giỏ hàng và đặt hàng:',
                'options' => [
                    ['text' => 'Xem giỏ hàng', 'link' => route('cart.index')],
                    ['text' => 'Lịch sử đơn hàng', 'link' => route('order.index')],
                    ['text' => 'Hướng dẫn đặt hàng', 'action' => 'guide']
                ]
            ];
        }

        // Contact related keywords
        if (str_contains($message, 'liên hệ') || str_contains($message, 'thông tin')) {
            return [
                'message' => '📞 Thông tin liên hệ:',
                'options' => [
                    ['text' => '📧 Email: ' . config('constants.shop_email'), 'action' => 'copy', 'value' => config('constants.shop_email')],
                    ['text' => '📱 Hotline: ' . config('constants.shop_phone'), 'action' => 'copy', 'value' => config('constants.shop_phone')],
                    ['text' => '📍 Địa chỉ: ' . config('constants.shop_address'), 'action' => 'info'],
                    ['text' => 'Xem bài viết', 'link' => route('posts.index')]
                ]
            ];
        }

        // Price related keywords
        if (str_contains($message, 'giá') || str_contains($message, 'tiền')) {
            return [
                'message' => '💰 Về giá cả sản phẩm:',
                'options' => [
                    ['text' => 'Sản phẩm giá tốt', 'link' => route('user.products.index', ['sort' => 'price_low'])],
                    ['text' => 'Sản phẩm cao cấp', 'link' => route('user.products.index', ['sort' => 'price_high'])],
                    ['text' => 'Sản phẩm khuyến mãi', 'link' => route('user.products.index', ['sale' => true])]
                ]
            ];
        }

        // Default response
        return [
            'message' => 'Xin chào! Tôi có thể giúp bạn tìm hiểu về sản phẩm, đặt hàng, thông tin liên hệ. Bạn cần hỗ trợ gì?',
            'options' => [
                ['text' => '🌸 Xem sản phẩm', 'action' => 'products'],
                ['text' => '📱 Danh mục', 'action' => 'categories'],
                ['text' => '🛒 Giỏ hàng', 'action' => 'cart'],
                ['text' => '📞 Liên hệ', 'action' => 'contact']
            ]
        ];
    }

    /**
     * Get quick response data
     */
    public function getQuickResponse(Request $request): JsonResponse
    {
        $type = $request->input('type');
        
        $responses = [
            'products' => [
                'message' => '🌸 Chúng tôi có nhiều loại sản phẩm tuyệt vời:',
                'options' => [
                    ['text' => 'Hoa Xà Phòng - Hoa không bao giờ tàn', 'link' => route('user.products.index', ['category' => 1])],
                    ['text' => 'Hoa Tươi - Vẻ đẹp tự nhiên', 'link' => route('user.products.index', ['category' => 2])],
                    ['text' => 'Hoa Đặc Biệt - Độc đáo và ý nghĩa', 'link' => route('user.products.index', ['category' => 3])],
                    ['text' => 'Quà Lưu Niệm - Kỷ niệm đáng nhớ', 'link' => route('user.products.index', ['category' => 4])]
                ]
            ],
            'categories' => [
                'message' => '📱 Các danh mục sản phẩm của chúng tôi:',
                'options' => [
                    ['text' => 'Xem tất cả danh mục', 'link' => route('user.products.index')],
                    ['text' => 'Sản phẩm bán chạy', 'link' => route('user.products.index', ['sort' => 'bestseller'])],
                    ['text' => 'Sản phẩm mới nhất', 'link' => route('user.products.index', ['sort' => 'newest'])]
                ]
            ],
            'cart' => [
                'message' => '🛒 Giỏ hàng và đặt hàng:',
                'options' => [
                    ['text' => 'Xem giỏ hàng', 'link' => route('cart.index')],
                    ['text' => 'Lịch sử đơn hàng', 'link' => route('order.index')],
                    ['text' => 'Hướng dẫn đặt hàng', 'action' => 'guide']
                ]
            ],
            'contact' => [
                'message' => '📞 Thông tin liên hệ:',
                'options' => [
                    ['text' => '📧 Email: ' . config('constants.shop_email'), 'action' => 'copy', 'value' => config('constants.shop_email')],
                    ['text' => '📱 Hotline: ' . config('constants.shop_phone'), 'action' => 'copy', 'value' => config('constants.shop_phone')],
                    ['text' => '📍 Địa chỉ: ' . config('constants.shop_address'), 'action' => 'info'],
                    ['text' => 'Xem bài viết', 'link' => route('posts.index')]
                ]
            ],
            'guide' => [
                'message' => "📋 Hướng dẫn đặt hàng:\n1. Chọn sản phẩm yêu thích\n2. Thêm vào giỏ hàng\n3. Điền thông tin giao hàng\n4. Xác nhận và thanh toán\n5. Chờ giao hàng tận nơi!"
            ]
        ];

        return response()->json([
            'success' => true,
            'response' => $responses[$type] ?? $responses['products']
        ]);
    }
}