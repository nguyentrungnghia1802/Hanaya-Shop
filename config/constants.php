<?php

return [
    // Đường dẫn ảnh logo
    'logo_path' => 'fixed_resources/logo.png',

    // Đường dẫn favicon
    'favicon_path' => 'favicon.ico',

    // Đường dẫn thư mục tài nguyên cố định
    'fixed_resources_path' => 'fixed_resources/',

    // Thông tin shop (có thể dùng cho footer, contact...)
    'shop_name' => 'HANAYA SHOP',
    'shop_email' => 'assassincreed2k1@gmail.com',
    'shop_phone' => '0948512463',
    'shop_address' => '123 Đường Hoa, Sơn Tây, Hà Nội',

    // Banner cấu hình
    'banners' => [
        [
            'image' => 'fixed_resources/banner_1.jpg',
            'title' => 'Chào mừng đến với Hanaya Shop',
            'subtitle' => 'Nơi mang đến những sản phẩm hoa và quà tặng ý nghĩa',
            'button_text' => 'Khám phá ngay',
            'button_link' => '/products'
        ],
        [
            'image' => 'fixed_resources/banner_2.jpg',
            'title' => 'Bộ sưu tập Hoa Xà Phòng',
            'subtitle' => 'Những bông hoa vĩnh cửu với hương thơm dịu nhẹ',
            'button_text' => 'Xem bộ sưu tập',
            'button_link' => '/products?category_name=soap-flower'
        ],
        [
            'image' => 'fixed_resources/banner_3.jpg',
            'title' => 'Quà tặng đặc biệt',
            'subtitle' => 'Những món quà ý nghĩa cho người thân yêu',
            'button_text' => 'Tìm quà ngay',
            'button_link' => '/products?category_name=souvenir'
        ]
    ],
    // Chatbot messages
    'chatbot_greeting' =>
        "Xin chào! Tôi là chatbot của Hanaya Shop. Tôi có thể giúp bạn:\n"
        . "🌸 Tìm kiếm sản phẩm\n"
        . "📝 Thông tin về đơn hàng\n"
        . "📋 Danh mục sản phẩm\n"
        . "📰 Tin tức mới nhất\n"
        . "❓ Trả lời các câu hỏi thường gặp\n\n"
        . "Bạn cần hỗ trợ gì?",    
    'chatbot_help' =>
        "🤖 Hướng dẫn sử dụng chatbot Hanaya Shop:\n\n"
        . "🔍 Tìm sản phẩm:\n"
        . "• 'tìm hoa xà phòng'\n"
        . "• 'sản phẩm quà tặng'\n\n"
        . "📂 Xem danh mục:\n"
        . "• 'danh mục sản phẩm'\n"
        . "• 'loại sản phẩm'\n\n"
        . "📦 Kiểm tra đơn hàng:\n"
        . "• 'đơn hàng của tôi'\n"
        . "• 'order'\n\n"
        . "📰 Tin tức:\n"
        . "• 'tin tức mới nhất'\n"
        . "• 'bài viết'\n\n"
        . "🏪 Thông tin cửa hàng:\n"
        . "• 'thông tin cửa hàng'\n"
        . "• 'địa chỉ liên hệ'\n\n"
        . "Hãy thử hỏi tôi bất cứ điều gì!",
    'chatbot_store_info' =>
        "🏪 Thông tin cửa hàng Hanaya Shop:\n\n"
        . "📍 Địa chỉ: 123 Đường Hoa, Sơn Tây, Hà Nội\n"
        . "📞 Điện thoại: 0948512463\n"
        . "📧 Email: assassincreed2k1@gmail.com\n"
        . "🕒 Giờ mở cửa: 8:00 - 22:00 (Thứ 2 - Chủ nhật)\n\n"
        . "🚚 Giao hàng: Toàn quốc\n"
        . "💳 Thanh toán: Tiền mặt, chuyển khoản, thẻ\n\n"
        . "Liên hệ ngay để được tư vấn!",
    'chatbot_status' => [
        'pending' => "Chờ xử lý",
        'processing' => "Đang xử lý",
        'completed' => "Hoàn thành",
        'cancelled' => "Đã hủy"
    ],
    'chatbot_price_info' =>
        "Về giá cả sản phẩm của chúng tôi:\n\n"
        . "🌸 Hoa xà phòng: Từ 50.000₫ - 500.000₫\n"
        . "🎁 Quà lưu niệm: Từ 30.000₫ - 300.000₫\n\n"
        . "Giá cụ thể tùy thuộc vào kích thước, chất liệu và thiết kế.\n"
        . "Xem chi tiết giá tại: /products",
    'chatbot_default' =>
        "Xin lỗi, tôi chưa hiểu câu hỏi của bạn. Bạn có thể hỏi tôi về:\n"
        . "• Sản phẩm (ví dụ: 'tìm hoa xà phòng')\n"
        . "• Danh mục sản phẩm\n"
        . "• Đơn hàng của bạn\n"
        . "• Tin tức mới nhất\n"
        . "• Thông tin cửa hàng\n\n"
        . "Hoặc gõ 'help' để xem hướng dẫn chi tiết.",
];
