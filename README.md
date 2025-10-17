
# 🌸 Hanaya Shop

<details>
<summary><strong>🇯🇵 日本語</strong></summary>

## 概要

**Hanaya Shop**は、ユーザーが美しい生花を簡単に選択・購入・決済できるよう設計された、最新のUI/UXを備えたオンラインフラワーショップWebアプリケーションです。
ECサイト構築の実務経験をアピールするために開発しました。

---

## 🎯 プロジェクト目的

- 花屋向けのシンプルかつ拡張性の高いECプラットフォームの構築
- 商品（花）、カート、注文の効率的な管理
- 管理者用ダッシュボードの実装
- **Docker**による迅速な環境構築（.env不要）

---

## 🌟 主な機能

### 👤 顧客向け
- 商品一覧の閲覧、カテゴリ・用途・価格による絞り込み
- 商品詳細・画像・価格の表示
- カートへの追加・注文作成
- 購入履歴の確認（会員登録時）

### 🛠️ 管理者向け
- 商品カテゴリ管理
- 商品のCRUD（追加・編集・削除・表示/非表示切替）
- 注文管理（承認・キャンセル・ステータス更新）
- 顧客管理

---

## 🛠️ 技術スタック

- **PHP 8.2**, **Laravel 12.2** – バックエンドAPI・管理システム
- **MySQL** – 商品・ユーザー・注文データ管理
- **Bladeテンプレート** – サーバーサイドレンダリング
- **Docker Compose** – Laravel + MySQLの迅速な環境構築

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

</details>

---

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

## 🛠️ Technology Stack

- **PHP 8.2**, **Laravel 12.2** – Backend API & management system
- **MySQL** – Data storage for products, users, orders
- **Blade template** – Server-side rendering
- **Docker Compose** – Fast setup for Laravel + MySQL environment

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

</details>

---

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

## 🛠️ Công nghệ sử dụng

- **PHP 8.2**, **Laravel 12.2** – Backend API & hệ thống quản lý
- **MySQL** – Lưu trữ dữ liệu sản phẩm, người dùng, đơn hàng
- **Blade template** – Giao diện server-side
- **Docker Compose** – Triển khai môi trường Laravel + MySQL nhanh chóng

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

</details>

