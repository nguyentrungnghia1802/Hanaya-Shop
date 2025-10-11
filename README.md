# 🌸 Hanaya Shop - Website Bán Hoa Online

**Hanaya Shop** là một ứng dụng web bán hoa online được xây dựng nhằm hỗ trợ người dùng dễ dàng lựa chọn, đặt mua và thanh toán các sản phẩm hoa tươi thông qua giao diện web hiện đại, tiện lợi và tối ưu trải nghiệm người dùng.

---

## 🎯 Mục Tiêu Dự Án

- Xây dựng nền tảng thương mại điện tử đơn giản cho các cửa hàng hoa.
- Quản lý sản phẩm (hoa), giỏ hàng và đơn hàng hiệu quả.
- Tích hợp giao diện quản trị cho admin.
- Triển khai hoàn toàn bằng **Docker**, không cần chỉnh `.env`, giúp dễ dàng cấu hình môi trường.

---

## 🌟 Tính Năng Chính

### 👤 Dành cho Khách hàng
- Xem danh sách sản phẩm hoa, lọc theo loại hoa / dịp / giá.
- Xem chi tiết sản phẩm, hình ảnh, giá cả.
- Thêm sản phẩm vào giỏ hàng và tạo đơn hàng.
- Xem lịch sử mua hàng *(nếu đã đăng ký tài khoản)*.

### 🛠️ Dành cho Quản trị viên (Admin)
- Quản lý danh mục hoa.
- CRUD sản phẩm: thêm, sửa, xóa, bật/tắt hiển thị.
- Quản lý đơn hàng: xác nhận, huỷ, cập nhật trạng thái.
- Quản lý khách hàng.

---

## 🛠️ Công Nghệ Sử Dụng

- **PHP 8.2**, **Laravel 12.2** – Backend API và hệ thống quản lý.
- **MySQL** – Lưu trữ dữ liệu sản phẩm, người dùng, đơn hàng.
- **Blade template** Dùng giao diện server-side
- **Docker Compose** – Triển khai môi trường Laravel + MySQL nhanh chóng.

---

## 🗂️ Cấu Trúc Dự Án
hanaya-shop/
├── app/
│   ├── Http/                   # Controller, Middleware, Request
│   ├── Models/                 # Các model chia theo module nghiệp vụ
│   │   ├── Cart/               # Mô hình giỏ hàng
│   │   ├── Offer/              # Mô hình ưu đãi, giảm giá
│   │   ├── Order/              # Mô hình đơn hàng
│   │   ├── Product/            # Mô hình sản phẩm (hoa)
│   │   └── User.php            # Mô hình người dùng (authentication)
│   └── Providers/              # Service providers của Laravel
│
├── bootstrap/                  # Khởi tạo ứng dụng Laravel
│   └── cache/                  # Cache bootstrap
│
├── config/                     # Các file cấu hình hệ thống
│   ├── app.php                 # Thông tin app, timezone, locale
│   ├── auth.php                # Xác thực người dùng
│   ├── database.php            # Kết nối CSDL
│   └── ...                     # Các cấu hình khác (mail, queue, session,...)
│
├── database/
│   ├── factories/              # Tạo dữ liệu mẫu tự động
│   ├── migrations/             # Quản lý cấu trúc bảng dữ liệu
│   └── seeders/                # Thêm dữ liệu mẫu (sản phẩm, user, đơn hàng...)
│
├── public/
│   ├── build/                  # Asset frontend (nếu có Vite/build)
│   ├── images/                 # Hình ảnh tĩnh (hoa, banner)
│   ├── .htaccess               # Rewrite cho Apache
│   ├── favicon.ico             # Icon trình duyệt
│   ├── hot                     # (dev) HMR indicator
│   ├── index.php               # Entry point chính
│   └── robots.txt              # SEO config
│
├── resources/
│   ├── css/                    # File CSS thuần
│   ├── js/                     # JavaScript (nếu không dùng frontend tách riêng)
│   └── views/                  # Giao diện Blade (nếu dùng server-side rendering)
│
├── routes/
│   ├── web.php                 # Tuyến web (giao diện)
│   └── api.php                 # API (cho frontend hoặc app mobile)
│
├── storage/                    # File upload, logs, cache,...
│
├── tests/                      # Unit test và feature test
│
├── .env                        # Cấu hình môi trường (Docker override)
├── artisan                     # CLI của Laravel
├── composer.json              # Quản lý package PHP
├── Dockerfile                 # Docker build Laravel app
├── docker-compose.yml         # Docker setup Laravel + MySQL
├── database.sqlite            # Tùy chọn: SQLite cho phát triển nhanh
└── README.md                  # Tài liệu mô tả dự án

