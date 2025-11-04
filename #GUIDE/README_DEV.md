# 🌟 Development

<details>
<summary><strong>🇯🇵 日本語</strong></summary>

# 🌸 Hanaya Shop — 開発環境セットアップガイド

## 目次

- [1) 必要環境](#1-必要環境)
- [2) 取得と依存関係のインストール](#2-取得と依存関係のインストール)
- [3) 環境変数の設定](#3-環境変数の設定)
- [4) データベース準備](#4-データベース準備)
- [5) アプリとアセットの起動](#5-アプリとアセットの起動)
- [6) Redis・Cache・Queue](#6-rediscachequeue)
- [7) 開発に便利なコマンド](#7-開発に便利なコマンド)
- [8) テスト（ある場合）](#8-テストある場合)
- [9) 開発支援ツール](#9-開発支援ツール)
- [10) よくある問題](#10-よくある問題)

このドキュメントでは、ローカル開発環境で Hanaya Shop を実行する方法（`php artisan serve`、`npm run dev`、`queue:work` などの手動コマンド）を説明します。

## 1) 必要環境
- PHP 8.2（XAMPP/WAMP/MAMP またはネイティブ PHP）
- Composer 2.x
- MySQL 8.0（互換 MariaDB 可）
- Node.js 18+ と NPM
- Redis（cache/queue 用に推奨）

Windows ヒント:
- XAMPP（Apache + PHP + MySQL）
- Redis for Windows: `https://github.com/tporadowski/redis/releases`
- PHP の Redis 拡張をインストールし、`php.ini` で有効化（`extension=php_redis.dll`）

## 2) 取得と依存関係のインストール
```bash
git clone <repo_url> hanaya-shop
cd hanaya-shop

# PHP 依存
composer install

# フロントエンド依存
npm install
```

## 3) 環境変数の設定
`.env` を作成（必要に応じて `.env.example` を参照）:
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hanaya_shop
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@localhost"

TINYMCE_API_KEY=your_tiny_api_key
MAPS_API_KEY=your_map_api_key

# 開発での支払いオプション（例）
PAYMENT_PAYPAL_ENABLED=false
PAYMENT_CARD_ENABLED=false
PAYMENT_COD_ENABLED=true
```

アプリキーの生成:
```bash
php artisan key:generate
```

## 4) データベース準備
MySQL に空の `hanaya_shop` を作成し、以下を実行:
```bash
php artisan migrate
php artisan db:seed   # あれば
```

## 5) アプリとアセットの起動

Laravel（内蔵サーバー）:
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

開発ビルド（Vite）:
```bash
npm run dev
```

ヒント: ターミナルを 2 つ開き、`php artisan serve` と `npm run dev` を分けて起動。

## 6) Redis・Cache・Queue

Redis の起動:
- Windows: `redis-server.exe`
- Linux/macOS: `redis-server`（macOS は `brew services start redis` など）

キャッシュクリア:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Queue ワーカー:
```bash
php artisan queue:work --queue=default --sleep=1 --tries=3
```

## 7) 開発に便利なコマンド
```bash
# すばやいバックエンド更新
php artisan clear-compiled
composer dump-autoload

# 生成系
php artisan make:view components.example
php artisan make:controller User/ProductController
php artisan make:model Product -m

# DB
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

## 8) テスト（ある場合）
```bash
php artisan test
```

## 9) 開発支援ツール
- Mailpit（メールテスト）: `https://github.com/axllent/mailpit/releases`
- ブラウザ DevTools（CSP/JS エラー確認）
- Laravel Telescope（任意）

## 10) よくある問題
- CSS/JS が読めない: `npm run dev` が動作しているか、Vite 設定を確認
- 500（Vite manifest）: `npm run build` または `npm run dev` で `public/build` を生成
- DB エラー: `.env` と接続権限を確認
- Redis 不足: Redis をインストールし PHP 拡張を有効化。暫定で `CACHE_DRIVER=file` も可
- パーミッション: `storage` と `bootstrap/cache` の書き込み権限

---

Hanaya Shop — 開発環境セットアップガイド

</details>

<details>
<summary><strong>🇺🇸 English</strong></summary>

# 🌸 Hanaya Shop — Development Environment Guide

## Table of Contents

- [1) System requirements](#1-system-requirements)
- [2) Clone and install dependencies](#2-clone-and-install-dependencies)
- [3) Configure environment](#3-configure-environment)
- [4) Prepare database](#4-prepare-database)
- [5) Run app and assets](#5-run-app-and-assets)
- [6) Redis, cache, and queues](#6-redis-cache-and-queues)
- [7) Helpful dev commands](#7-helpful-dev-commands)
- [8) Testing (if present)](#8-testing-if-present)
- [9) Dev tools](#9-dev-tools)
- [10) Common issues](#10-common-issues)

This document explains how to run Hanaya Shop locally for development with manual commands such as `php artisan serve`, `npm run dev`, and `php artisan queue:work`.

## 1) System requirements
- PHP 8.2 (XAMPP/WAMP/MAMP or native PHP)
- Composer 2.x
- MySQL 8.0 (or compatible MariaDB)
- Node.js 18+ and NPM
- Redis (recommended for cache/queue)

Windows hints:
- XAMPP (Apache + PHP + MySQL)
- Redis for Windows: `https://github.com/tporadowski/redis/releases`
- Install PHP Redis extension and enable in `php.ini` (`extension=php_redis.dll`)

## 2) Clone and install dependencies
```bash
git clone <repo_url> hanaya-shop
cd hanaya-shop

# PHP dependencies
composer install

# Frontend dependencies
npm install
```

## 3) Configure environment
Create `.env` (use `.env.example` as a reference if present):
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hanaya_shop
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@localhost"

TINYMCE_API_KEY=your_tiny_api_key
MAPS_API_KEY=your_map_api_key

# Payment options in dev
PAYMENT_PAYPAL_ENABLED=false
PAYMENT_CARD_ENABLED=false
PAYMENT_COD_ENABLED=true
```

Generate app key:
```bash
php artisan key:generate
```

## 4) Prepare database
Create an empty `hanaya_shop` DB, then run:
```bash
php artisan migrate
php artisan db:seed   # if available
```

## 5) Run app and assets

Laravel built-in server:
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Dev build (Vite):
```bash
npm run dev
```

Tip: open two terminals, one for `php artisan serve` and one for `npm run dev`.

## 6) Redis, cache, and queues

Start Redis:
- Windows: `redis-server.exe`
- Linux/macOS: `redis-server` (or `brew services start redis` on macOS)

Clear caches when needed:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Run the queue worker:
```bash
php artisan queue:work --queue=default --sleep=1 --tries=3
```

## 7) Helpful dev commands
```bash
# Quick backend refresh
php artisan clear-compiled
composer dump-autoload

# Generators
php artisan make:view components.example
php artisan make:controller User/ProductController
php artisan make:model Product -m

# Database
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

## 8) Testing (if present)
```bash
php artisan test
```

## 9) Dev tools
- Mailpit for SMTP testing: `https://github.com/axllent/mailpit/releases`
- Browser DevTools for CSP/JS errors
- Laravel Telescope (optional)

## 10) Common issues
- CSS/JS not loading: ensure `npm run dev` is running; verify Vite config
- 500 due to Vite manifest: run `npm run build` or `npm run dev` to generate `public/build`
- DB errors: verify `.env` and connection privileges
- Missing Redis: install Redis and enable PHP extension; temporarily switch to `CACHE_DRIVER=file` if needed
- Permissions: ensure `storage` and `bootstrap/cache` are writable

---

Hanaya Shop — Development Environment Guide

</details>

<details>
<summary><strong>🇻🇳 Tiếng Việt</strong></summary>

# 🌸 Hanaya Shop — Hướng dẫn cài đặt môi trường Developing

## Mục lục

- [1) Yêu cầu hệ thống](#1-yêu-cầu-hệ-thống)
- [2) Clone mã nguồn và cài dependencies](#2-clone-mã-nguồn-và-cài-dependencies)
- [3) Cấu hình môi trường](#3-cấu-hình-môi-trường)
- [4) Chuẩn bị database](#4-chuẩn-bị-database)
- [5) Chạy ứng dụng và assets](#5-chạy-ứng-dụng-và-assets)
- [6) Redis, Cache và Queue](#6-redis-cache-và-queue)
- [7) Lệnh hữu ích cho phát triển](#7-lệnh-hữu-ích-cho-phát-triển)
- [8) Testing nhanh (nếu có tests)](#8-testing-nhanh-nếu-có-tests)
- [9) Gợi ý công cụ hỗ trợ phát triển](#9-gợi-ý-công-cụ-hỗ-trợ-phát-triển)
- [10) Khắc phục sự cố thường gặp](#10-khắc-phục-sự-cố-thường-gặp)

Tài liệu này hướng dẫn bạn cài đặt và chạy Hanaya Shop trên máy local để phát triển với các lệnh thủ công: PHP artisan serve, npm run dev, queue worker, v.v.

## 1) Yêu cầu hệ thống
- PHP 8.2 (XAMPP/WAMP/MAMP hoặc PHP native)
- Composer 2.x
- MySQL 8.0 (hoặc MariaDB tương thích)
- Node.js 18+ và NPM
- Redis (khuyến nghị cho cache/queue)

Windows gợi ý:
- XAMPP (Apache + PHP + MySQL)
- Redis cho Windows: tải từ `https://github.com/tporadowski/redis/releases`
- Cài PHP Redis extension tương ứng và enable trong `php.ini` (extension=php_redis.dll)

## 2) Clone mã nguồn và cài dependencies
```bash
git clone <repo_url> hanaya-shop
cd hanaya-shop

# PHP dependencies
composer install

# Frontend dependencies
npm install
```

## 3) Cấu hình môi trường
Tạo file `.env` nếu chưa có (có thể tham khảo `.env.example` nếu đi kèm):
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hanaya_shop
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@localhost"

TINYMCE_API_KEY=your_tiny_api_key
MAPS_API_KEY=your_map_api_key

# Tùy chọn thanh toán trong môi trường dev
PAYMENT_PAYPAL_ENABLED=false
PAYMENT_CARD_ENABLED=false
PAYMENT_COD_ENABLED=true
```

Tạo key ứng dụng:
```bash
php artisan key:generate
```

## 4) Chuẩn bị database
Tạo database rỗng `hanaya_shop` trong MySQL, sau đó chạy:
```bash
php artisan migrate
php artisan db:seed   # nếu có seeder
```

## 5) Chạy ứng dụng và assets

Chạy Laravel (PHP built-in server):
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Build assets ở chế độ dev (Vite):
```bash
npm run dev
```

Gợi ý: mở 2 terminal, một terminal cho `php artisan serve`, một terminal cho `npm run dev`.

## 6) Redis, Cache và Queue

Khởi chạy Redis:
- Windows: chạy `redis-server.exe`
- Linux/macOS: `redis-server` (hoặc `brew services start redis` trên macOS)

Xóa cache khi cần:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Chạy queue worker:
```bash
php artisan queue:work --queue=default --sleep=1 --tries=3
```

## 7) Lệnh hữu ích cho phát triển
```bash
# Refresh backend nhanh
php artisan clear-compiled
composer dump-autoload

# Tạo view/component/controller/model...
php artisan make:view components.example
php artisan make:controller User/ProductController
php artisan make:model Product -m

# Database
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

## 8) Testing nhanh (nếu có tests)
```bash
php artisan test
```

## 9) Gợi ý công cụ hỗ trợ phát triển
- Mailpit để test email: `https://github.com/axllent/mailpit/releases`
- Browser DevTools để kiểm tra lỗi CSP/JS
- Laravel Telescope (tùy chọn) cho debug sâu

## 10) Khắc phục sự cố thường gặp
- CSS/JS không tải: kiểm tra `npm run dev` đang chạy; kiểm tra cấu hình Vite
- 500 do Vite manifest: chạy `npm run build` hoặc `npm run dev` để tạo `public/build`
- Lỗi DB: kiểm tra `.env` và quyền kết nối
- Redis thiếu: cài Redis và bật extension PHP; chuyển tạm `CACHE_DRIVER=file` nếu cần
- Quyền thư mục: đảm bảo `storage` và `bootstrap/cache` có quyền ghi

---

Hanaya Shop — Tài liệu cài đặt môi trường phát triển (Developing)

</details>