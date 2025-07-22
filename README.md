
# 🌸 Hanaya Shop - Laravel E-commerce Platform

![Laravel](https://img.shields.io/badge/Laravel-12.2-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)

> **Hanaya Shop** là nền tảng thương mại điện tử hiện đại dành cho cửa hàng hoa, được phát triển với Laravel và tối ưu cho deployment.

---

## 📋 Mục lục

- [🎯 Tổng quan dự án](#-tổng-quan-dự-án)
- [🌟 Tính năng chính](#-tính-năng-chính)
- [🛠️ Công nghệ sử dụng](#️-công-nghệ-sử-dụng)
- [🚀 Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [📈 Lịch sử cập nhật](#-lịch-sử-cập-nhật)
- [🐳 Docker Deployment](#-docker-deployment)
- [📚 Tài liệu tham khảo](#-tài-liệu-tham-khảo)

---

## 🎯 Tổng quan dự án

**Hanaya Shop** là hệ thống quản lý cửa hàng hoa trực tuyến, được thiết kế để giúp người dùng dễ dàng:
- Duyệt và mua hoa tươi với giao diện thân thiện
- Quản lý sản phẩm, đơn hàng hiệu quả
- Triển khai nhanh chóng với Docker
- Mở rộng linh hoạt cho nhiều loại hình kinh doanh

### 🎯 Mục tiêu
- Xây dựng nền tảng e-commerce mở rộng cho cửa hàng hoa
- Quản lý sản phẩm, giỏ hàng, đơn hàng hiệu quả
- Giao diện quản trị trực quan cho admin
- Triển khai production với Docker (không cần config phức tạp)

---

## 🗂️ ディレクトリ構成

```bash
hanaya-shop/
├── app/                # コントローラー、モデル、サービス
├── bootstrap/          # Laravel初期化
├── config/             # システム設定
├── database/           # マイグレーション・シーダー
├── public/             # 画像・エントリポイント
├── resources/          # CSS・JS・Bladeテンプレート
├── routes/             # Web/APIルーティング
├── storage/            # アップロード・ログ
├── tests/              # ユニット・機能テスト
├── Dockerfile          # Docker設定
├── docker-compose.yml  # Docker環境構築
└── README.md           # ドキュメント
```

---

## 💡 アピールポイント

- 実務レベルのLaravel設計・実装力
- Dockerによる開発・本番環境の自動化
- 管理画面・顧客画面の両方を考慮したUI/UX設計
- 拡張性・保守性を意識したディレクトリ構成

---

## 🚀 利用方法

1. 必要なツール: [Docker Desktop](https://www.docker.com/products/docker-desktop/) をインストール
2. プロジェクトディレクトリで以下を実行:
   ```bash
   docker-compose up --build
   ```
3. ブラウザで `http://localhost:8000` にアクセス
4. 管理画面: `/admin` からログイン
5. サンプルデータ投入:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```
6. テスト実行:
   ```bash
   docker-compose exec app php artisan test
   ```

詳細は `README.md` 内の各セクションをご参照ください。

</details>


<details>
<summary><strong>🇺🇸 English</strong></summary>

## Overview

**Hanaya Shop** is a modern online flower shop web application, designed to help users easily browse, purchase, and pay for fresh flowers with an optimized and user-friendly interface.
This project demonstrates practical experience in building scalable e-commerce platforms.

---

## 🎯 Project Goals

- Build a simple yet extensible e-commerce platform for flower shops
- Efficient management of products (flowers), cart, and orders
- Integrated admin dashboard for shop management
- Rapid environment setup using **Docker** (no `.env` configuration required)

---

## 🌟 Key Features

### 👤 For Customers
- Browse flower products, filter by category/occasion/price
- View product details, images, and prices
- Add products to cart and place orders
- View purchase history (for registered users)

### 🛠️ For Admins
- Manage flower categories
- CRUD operations for products: add, edit, delete, toggle visibility
- Manage orders: confirm, cancel, update status
- Manage customers

---

## 🛠️ Technology Stack & Benefits

- **PHP 8.2**: Latest version for improved security, performance, and maintainability.
- **Laravel 12.2**: Modern MVC framework enabling rapid development, robust authentication/authorization, RESTful API design, and easy testing.
- **MySQL**: Reliable relational database for fast processing and transaction management.
- **Blade template**: Server-side rendering for SEO and performance, reusable UI components.
- **Docker Compose**: Automated environment setup, unified dependency management, eliminates environment differences, CI/CD ready.
- **Tailwind CSS**: Modern UI design, responsive and user-friendly experience.
- **PHPUnit**: Unit and feature testing for quality assurance.

These technologies ensure high development efficiency, maintainability, scalability, security, and performance.

---

## 🗂️ Project Structure

```bash
hanaya-shop/
├── app/                # Controllers, models, services
├── bootstrap/          # Laravel initialization
├── config/             # System configuration
├── database/           # Migrations & seeders
├── public/             # Images & entry point
├── resources/          # CSS, JS, Blade templates
├── routes/             # Web/API routing
├── storage/            # Uploads, logs
├── tests/              # Unit & feature tests
├── Dockerfile          # Docker configuration
├── docker-compose.yml  # Docker setup
└── README.md           # Documentation
```

---

## 💡 Highlights

- Professional Laravel architecture and implementation
- Automated development & production environment with Docker
- Thoughtful UI/UX for both admin and customer sides
- Scalable and maintainable project structure

---

## 🚀 Getting Started

1. Prerequisite: Install [Docker Desktop](https://www.docker.com/products/docker-desktop/)
2. In the project directory, run:
   ```bash
   docker-compose up --build
   ```
3. Open your browser and go to `http://localhost:8000`
4. Admin dashboard: access via `/admin`
5. Seed sample data:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```
6. Run tests:
   ```bash
   docker-compose exec app php artisan test
   ```

See each section in `README.md` for more details.

</details>


<details>
<summary><strong>🇻🇳 Tiếng Việt</strong></summary>

## Giới thiệu

**Hanaya Shop** là ứng dụng web bán hoa online hiện đại, giúp người dùng dễ dàng lựa chọn, đặt mua và thanh toán hoa tươi qua giao diện tối ưu, thân thiện.
Dự án này thể hiện kinh nghiệm thực tế xây dựng nền tảng thương mại điện tử mở rộng.

---

## 🎯 Mục tiêu dự án

- Xây dựng nền tảng thương mại điện tử đơn giản, dễ mở rộng cho cửa hàng hoa
- Quản lý sản phẩm (hoa), giỏ hàng, đơn hàng hiệu quả
- Tích hợp giao diện quản trị cho admin
- Triển khai nhanh bằng **Docker** (không cần chỉnh `.env`)

---

## 🌟 Tính năng chính

### 👤 Dành cho khách hàng
- Xem danh sách hoa, lọc theo loại/dịp/giá
- Xem chi tiết sản phẩm, hình ảnh, giá
- Thêm vào giỏ hàng, tạo đơn hàng
- Xem lịch sử mua hàng (nếu đã đăng ký)

### 🛠️ Dành cho quản trị viên
- Quản lý danh mục hoa
- CRUD sản phẩm: thêm, sửa, xóa, bật/tắt hiển thị
- Quản lý đơn hàng: xác nhận, huỷ, cập nhật trạng thái
- Quản lý khách hàng

---

## 🛠️ Công nghệ sử dụng & Hiệu quả

- **PHP 8.2**: Phiên bản mới nhất, tăng bảo mật, hiệu năng và dễ bảo trì.
- **Laravel 12.2**: Framework hiện đại, phát triển nhanh, xác thực/ủy quyền mạnh mẽ, API RESTful, dễ kiểm thử.
- **MySQL**: CSDL quan hệ, xử lý dữ liệu lớn, quản lý giao dịch hiệu quả.
- **Blade template**: Giao diện server-side, tối ưu SEO, hiệu năng, tái sử dụng UI.
- **Docker Compose**: Tự động hóa môi trường, quản lý phụ thuộc, loại bỏ lỗi môi trường, sẵn sàng CI/CD.
- **Tailwind CSS**: UI hiện đại, responsive, nâng cao trải nghiệm người dùng.
- **PHPUnit**: Đảm bảo chất lượng qua kiểm thử đơn vị và chức năng.

Những công nghệ này giúp dự án đạt hiệu quả cao về tốc độ phát triển, bảo trì, mở rộng, bảo mật và hiệu năng.

---

## 🗂️ Cấu trúc dự án

```bash
hanaya-shop/
├── app/                # Controller, model, service
├── bootstrap/          # Khởi tạo Laravel
├── config/             # Cấu hình hệ thống
├── database/           # Migration & seeder
├── public/             # Hình ảnh, entry point
├── resources/          # CSS, JS, Blade template
├── routes/             # Tuyến web/API
├── storage/            # Upload, log
├── tests/              # Unit test & feature test
├── Dockerfile          # Docker config
├── docker-compose.yml  # Docker setup
└── README.md           # Tài liệu dự án
```

---

## 💡 Điểm nổi bật

- Kiến trúc Laravel chuyên nghiệp, dễ mở rộng
- Tự động hóa môi trường phát triển & triển khai với Docker
- UI/UX tối ưu cho cả admin và khách hàng
- Cấu trúc dự án rõ ràng, dễ bảo trì

---

## 🚀 Hướng dẫn sử dụng

1. Cài đặt [Docker Desktop](https://www.docker.com/products/docker-desktop/)
2. Trong thư mục dự án, chạy:
   ```bash
   docker-compose up --build
   ```
3. Mở trình duyệt và truy cập `http://localhost:8000`
4. Đăng nhập admin tại `/admin`
5. Tạo dữ liệu mẫu:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```
6. Chạy kiểm thử:
   ```bash
   docker-compose exec app php artisan test
   ```

Xem chi tiết từng phần trong `README.md`.

</details>

