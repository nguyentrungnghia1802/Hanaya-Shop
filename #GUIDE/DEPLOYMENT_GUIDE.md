# 🌟 Production with Docker

<details>
<summary><strong>🇯🇵 日本語</strong></summary>

# Hanaya Shop — 本番環境インストールガイド（Docker イメージ）

このドキュメントは、事前ビルド済みの Docker イメージを使って、どのマシンでも Hanaya Shop を本番環境へ導入する手順を説明します。

## 目次

- [概要](#概要)
- [最低要件](#最低要件)
- [1) Docker と Compose のインストール](#1-docker-と-compose-のインストール)
- [2) ディレクトリ作成](#2-ディレクトリ作成)
- [3) Docker Compose の準備](#3-docker-compose-の準備)
- [4) 起動](#4-起動)
- [5) ドメインと HTTPS（推奨）](#5-ドメインと-https推奨)
- [6) 運用](#6-運用)
- [7) 環境変数](#7-環境変数)
- [8) Windows/macOS 注意](#8-windowsmacos-注意)
- [9) トラブルシューティング](#9-トラブルシューティング)
- [10) セキュリティチェックリスト](#10-セキュリティチェックリスト)

## 概要
- 対応 OS: Linux（Ubuntu/Debian/CentOS/RHEL/Rocky/Alma/Amazon）、macOS、Windows（Docker Desktop）
- 利用イメージ:
  - アプリケーション: `assassincreed2k1/hanaya-shop:latest`
  - データベース: `mysql:8.0`
  - キャッシュ: `redis:7-alpine`
- 既定ポート: HTTP 80、MySQL 3306、Redis 6379

## 最低要件
- 2 vCPU、2GB RAM、20GB 空きディスク
- Docker Engine と Docker Compose v2（Windows/macOS は Docker Desktop）

## 1) Docker と Compose のインストール
Linux（Ubuntu 例）:
```bash
sudo apt update && sudo apt install -y ca-certificates curl gnupg lsb-release
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
sudo systemctl enable --now docker
sudo usermod -aG docker $USER
newgrp docker
```

macOS/Windows:
- Docker Desktop をインストールし、起動しておく

## 2) ディレクトリ作成
```bash
sudo mkdir -p /opt/hanaya-shop
sudo chown -R $USER:$USER /opt/hanaya-shop
cd /opt/hanaya-shop
```

## 3) Docker Compose の準備
方法 A（推奨）: リポジトリの compose を使用
```bash
curl -fsSL -o docker-compose.yml \
  https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deployment/docker-compose.prod.yml
```

方法 B: 最小構成の compose を作成（参考）
```yaml
services:
  app:
    image: assassincreed2k1/hanaya-shop:latest
    depends_on: [db, redis]
    ports:
      - "80:80"
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      APP_URL: http://your-domain-or-ip
      DB_HOST: db
      DB_PORT: 3306
      DB_DATABASE: hanaya_shop
      DB_USERNAME: hanaya_user
      DB_PASSWORD: "change-me"
      QUEUE_CONNECTION: redis
      REDIS_HOST: redis
      REDIS_PORT: 6379
    volumes:
      - app_storage:/var/www/html/storage
  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: hanaya_shop
      MYSQL_USER: hanaya_user
      MYSQL_PASSWORD: "change-me"
      MYSQL_ROOT_PASSWORD: "change-root"
    volumes:
      - db_data:/var/lib/mysql
  redis:
    image: redis:7-alpine
    command: ["redis-server", "--save", "60", "1000"]
    volumes:
      - redis_data:/data
volumes:
  app_storage:
  db_data:
  redis_data:
```

セキュリティ注意:
- 既定パスワードは必ず変更
- `APP_URL` は実際のドメインまたは IP に設定

## 4) 起動
```bash
docker compose pull
docker compose up -d
```

MySQL 初期化のため 30 秒ほど待ち、初回セットアップを実行:
```bash
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan optimize
```

Queue ワーカー（メール/通知に推奨）:
```bash
docker compose exec -d app php artisan queue:work --queue=default --sleep=1 --tries=3
```

## 5) ドメインと HTTPS（推奨）
1. ドメインの A レコードをサーバー IP に向ける
2. 逆プロキシで SSL を終端（Nginx Proxy Manager / Caddy / Traefik など）
3. `APP_URL=https://yourdomain.com` を設定し、再起動:
```bash
docker compose up -d
```

## 6) 運用
ステータス/ログ:
```bash
docker compose ps
docker compose logs -f app | cat
```

更新:
```bash
docker compose pull
docker compose up -d
docker compose exec app php artisan migrate --force
```

再起動/停止:
```bash
docker compose restart
docker compose down
```

シェル接続:
```bash
docker compose exec app bash
```

DB バックアップ/リストア:
```bash
# Backup
docker compose exec db mysqldump -u root -p hanaya_shop > backup.sql
# Restore
docker compose exec -T db mysql -u root -p hanaya_shop < backup.sql
```

スケール（任意）:
```bash
docker compose up -d --scale app=2
```

## 7) 環境変数
`docker-compose.yml` またはマウントした `.env` で設定:
- アプリ: `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`
- DB: `DB_HOST`, `DB_PORT=3306`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- キャッシュ/キュー: `QUEUE_CONNECTION=redis`, `REDIS_HOST=redis`, `REDIS_PORT=6379`
- メール: `MAIL_*` 一式
- 連携: `TINYMCE_API_KEY`, `MAPS_API_KEY`
- 決済: `PAYMENT_PAYPAL_ENABLED`, `PAYMENT_PAYPAL_KEY`, `PAYMENT_CARD_ENABLED`, `PAYMENT_COD_ENABLED`

変更後は再起動:
```bash
docker compose up -d
```

## 8) Windows/macOS 注意
- Docker Desktop のターミナルで `docker`/`docker compose` が使えることを確認
- ボリュームは Named Volumes を推奨（クロスプラットフォーム互換）

## 9) トラブルシューティング
- 500（Vite manifest）: 画像に `public/build` を含むこと、`php artisan optimize` を実行
- DB 接続: 資格情報と `db` サービスの起動を確認
- Migration の競合: 状態確認の上で実行
  ```bash
  docker compose exec app php artisan migrate:status
  docker compose exec app php artisan migrate --force
  ```
- 権限: 必要に応じて
  ```bash
  docker compose exec app chown -R www-data:www-data storage
  ```

## 10) セキュリティチェックリスト
1. 既定パスワードの変更（DB root/user、管理者アカウント）
2. `APP_KEY` を設定し、本番は `APP_DEBUG=false`
3. ファイアウォール（80/443 のみ）または Reverse Proxy 配下
4. DB バックアップの自動化
5. Docker/イメージ更新とログ監視

---

Hanaya Shop — Docker イメージで本番運用可能

</details>

<details>
<summary><strong>🇺🇸 English</strong></summary>

# Hanaya Shop — Production Installation Guide (Docker Images)

## Table of Contents

- [At a glance](#at-a-glance)
- [Requirements](#requirements)
- [1) Install Docker and Compose](#1-install-docker-and-compose)
- [2) Create deployment directory](#2-create-deployment-directory)
- [3) Prepare Docker Compose](#3-prepare-docker-compose)
- [4) Start services](#4-start-services)
- [5) Domain and HTTPS (optional but recommended)](#5-domain-and-https-optional-but-recommended)
- [6) Operations](#6-operations)
- [7) Environment variables](#7-environment-variables)
- [8) Windows/macOS notes](#8-windowsmacos-notes)
- [9) Troubleshooting](#9-troubleshooting)
- [10) Security checklist](#10-security-checklist)

This guide explains how to deploy Hanaya Shop to production on any machine using prebuilt Docker images.

## At a glance
- Works on Linux (Ubuntu/Debian/CentOS/RHEL/Rocky/Alma/Amazon), macOS, Windows (Docker Desktop)
- Images:
  - App: `assassincreed2k1/hanaya-shop:latest`
  - Database: `mysql:8.0`
  - Cache: `redis:7-alpine`
- Default ports: HTTP 80, MySQL 3306, Redis 6379

## Requirements
- 2 vCPU, 2GB RAM, 20GB free disk
- Docker Engine + Docker Compose v2 (or Docker Desktop)

## 1) Install Docker and Compose
Linux (Ubuntu example):
```bash
sudo apt update && sudo apt install -y ca-certificates curl gnupg lsb-release
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
sudo systemctl enable --now docker
sudo usermod -aG docker $USER
newgrp docker
```

macOS/Windows:
- Install Docker Desktop and ensure it is running

## 2) Create deployment directory
```bash
sudo mkdir -p /opt/hanaya-shop
sudo chown -R $USER:$USER /opt/hanaya-shop
cd /opt/hanaya-shop
```

## 3) Prepare Docker Compose
Option A (recommended): use the compose file from the repo
```bash
curl -fsSL -o docker-compose.yml \
  https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deployment/docker-compose.prod.yml
```

Option B: create a minimal compose (reference)
```yaml
services:
  app:
    image: assassincreed2k1/hanaya-shop:latest
    depends_on: [db, redis]
    ports:
      - "80:80"
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      APP_URL: http://your-domain-or-ip
      DB_HOST: db
      DB_PORT: 3306
      DB_DATABASE: hanaya_shop
      DB_USERNAME: hanaya_user
      DB_PASSWORD: "change-me"
      QUEUE_CONNECTION: redis
      REDIS_HOST: redis
      REDIS_PORT: 6379
    volumes:
      - app_storage:/var/www/html/storage
  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: hanaya_shop
      MYSQL_USER: hanaya_user
      MYSQL_PASSWORD: "change-me"
      MYSQL_ROOT_PASSWORD: "change-root"
    volumes:
      - db_data:/var/lib/mysql
  redis:
    image: redis:7-alpine
    command: ["redis-server", "--save", "60", "1000"]
    volumes:
      - redis_data:/data
volumes:
  app_storage:
  db_data:
  redis_data:
```

Security notes:
- Always change default passwords
- Set `APP_URL` to your real domain or server IP

## 4) Start services
```bash
docker compose pull
docker compose up -d
```

Wait ~30s for MySQL to initialize, then run first-time setup:
```bash
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan optimize
```

Run a queue worker (recommended for emails/notifications):
```bash
docker compose exec -d app php artisan queue:work --queue=default --sleep=1 --tries=3
```

## 5) Domain and HTTPS (optional but recommended)
1. Point your domain A record to the server IP
2. Terminate TLS using a reverse proxy (Nginx Proxy Manager, Caddy, Traefik)
3. Set `APP_URL=https://yourdomain.com` and restart:
```bash
docker compose up -d
```

## 6) Operations
Status/logs:
```bash
docker compose ps
docker compose logs -f app | cat
```

Update to latest:
```bash
docker compose pull
docker compose up -d
docker compose exec app php artisan migrate --force
```

Restart/stop:
```bash
docker compose restart
docker compose down
```

Shell access:
```bash
docker compose exec app bash
```

Database backup/restore:
```bash
# Backup
docker compose exec db mysqldump -u root -p hanaya_shop > backup.sql
# Restore
docker compose exec -T db mysql -u root -p hanaya_shop < backup.sql
```

Scale the app (optional):
```bash
docker compose up -d --scale app=2
```

## 7) Environment variables
Set in `docker-compose.yml` or a mounted `.env` file:
- App: `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`
- Database: `DB_HOST`, `DB_PORT=3306`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Cache/Queue: `QUEUE_CONNECTION=redis`, `REDIS_HOST=redis`, `REDIS_PORT=6379`
- Mail: `MAIL_*`
- Integrations: `TINYMCE_API_KEY`, `MAPS_API_KEY`
- Payments: `PAYMENT_PAYPAL_ENABLED`, `PAYMENT_PAYPAL_KEY`, `PAYMENT_CARD_ENABLED`, `PAYMENT_COD_ENABLED`

After changes, restart:
```bash
docker compose up -d
```

## 8) Windows/macOS notes
- Use Docker Desktop; run commands where `docker`/`docker compose` are available
- Prefer named volumes for cross-OS compatibility

## 9) Troubleshooting
- 500 (Vite manifest): ensure `public/build` is in the image; run `php artisan optimize`
- DB connection: verify credentials and that `db` service is ready
- Migration conflicts:
  ```bash
  docker compose exec app php artisan migrate:status
  docker compose exec app php artisan migrate --force
  ```
- Permissions:
  ```bash
  docker compose exec app chown -R www-data:www-data storage
  ```

## 10) Security checklist
1. Change all default passwords (DB root/user, admin)
2. Set `APP_KEY`; keep `APP_DEBUG=false` in production
3. Restrict firewall (80/443) or run behind a reverse proxy
4. Automate DB backups
5. Keep images up to date; monitor logs

---

Hanaya Shop — Production-ready with Docker Images

</details>

<details>
<summary><strong>🇻🇳 Tiếng Việt</strong></summary>

# Hanaya Shop — Hướng dẫn cài đặt Production (Docker Images)

## Mục lục

- [Tổng quan](#tổng-quan)
- [Yêu cầu tối thiểu](#yêu-cầu-tối-thiểu)
- [1) Cài đặt Docker và Docker Compose](#1-cài-đặt-docker-và-docker-compose)
- [2) Tạo thư mục triển khai](#2-tạo-thư-mục-triển-khai)
- [3) Chuẩn bị Docker Compose](#3-chuẩn-bị-docker-compose)
- [4) Khởi chạy services](#4-khởi-chạy-services)
- [5) Cấu hình domain và HTTPS (khuyến nghị)](#5-cấu-hình-domain-và-https-khuyến-nghị)
- [6) Vận hành hằng ngày](#6-vận-hành-hằng-ngày)
- [7) Biến môi trường thường dùng](#7-biến-môi-trường-thường-dùng)
- [8) Ghi chú cho Windows/macOS](#8-ghi-chú-cho-windowsmacos)
- [9) Khắc phục sự cố](#9-khắc-phục-sự-cố)
- [10) Danh sách bảo mật](#10-danh-sách-bảo-mật)

Tài liệu này hướng dẫn triển khai Hanaya Shop lên môi trường Production trên mọi hệ điều hành sử dụng Docker Images dựng sẵn.

## Tổng quan
- Hỗ trợ Linux (Ubuntu/Debian/CentOS/RHEL/Rocky/Alma/Amazon), macOS, Windows (Docker Desktop)
- Sử dụng các images:
  - Ứng dụng: `assassincreed2k1/hanaya-shop:latest`
  - Database: `mysql:8.0`
  - Cache: `redis:7-alpine`
- Cổng mặc định: HTTP 80, MySQL 3306, Redis 6379

## Yêu cầu tối thiểu
- 2 vCPU, 2GB RAM, 20GB dung lượng trống
- Docker Engine + Docker Compose v2 (hoặc Docker Desktop trên Windows/macOS)

## 1) Cài đặt Docker và Docker Compose

Linux (Ubuntu ví dụ):
```bash
sudo apt update && sudo apt install -y ca-certificates curl gnupg lsb-release
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
sudo systemctl enable --now docker
sudo usermod -aG docker $USER
newgrp docker
```

macOS/Windows:
- Cài đặt Docker Desktop và đảm bảo Docker đang chạy

## 2) Tạo thư mục triển khai
```bash
sudo mkdir -p /opt/hanaya-shop
sudo chown -R $USER:$USER /opt/hanaya-shop
cd /opt/hanaya-shop
```

## 3) Chuẩn bị Docker Compose

Tùy chọn A: Dùng file có sẵn trong repository (khuyến nghị)
```bash
curl -fsSL -o docker-compose.yml \
  https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/deployment/docker-compose.prod.yml
```

Tùy chọn B: Tạo file compose tối thiểu (tham khảo)
```yaml
services:
  app:
    image: assassincreed2k1/hanaya-shop:latest
    depends_on: [db, redis]
    ports:
      - "80:80"
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      APP_URL: http://your-domain-or-ip
      DB_HOST: db
      DB_PORT: 3306
      DB_DATABASE: hanaya_shop
      DB_USERNAME: hanaya_user
      DB_PASSWORD: "change-me"
      QUEUE_CONNECTION: redis
      REDIS_HOST: redis
      REDIS_PORT: 6379
    volumes:
      - app_storage:/var/www/html/storage
  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: hanaya_shop
      MYSQL_USER: hanaya_user
      MYSQL_PASSWORD: "change-me"
      MYSQL_ROOT_PASSWORD: "change-root"
    volumes:
      - db_data:/var/lib/mysql
  redis:
    image: redis:7-alpine
    command: ["redis-server", "--save", "60", "1000"]
    volumes:
      - redis_data:/data
volumes:
  app_storage:
  db_data:
  redis_data:
```

Lưu ý bảo mật:
- Luôn thay đổi mật khẩu mặc định (`DB_PASSWORD`, `MYSQL_ROOT_PASSWORD`...)
- Đặt `APP_URL` theo domain hoặc IP thực tế

## 4) Khởi chạy services
```bash
docker compose pull
docker compose up -d
# Nếu máy dùng binary cũ: docker-compose pull && docker-compose up -d
```

Chờ ~30 giây để MySQL khởi tạo, sau đó thực hiện thiết lập lần đầu:
```bash
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan optimize
```

Chạy queue worker (khuyến nghị cho email/thông báo):
```bash
docker compose exec -d app php artisan queue:work --queue=default --sleep=1 --tries=3
```

## 5) Cấu hình domain và HTTPS (khuyến nghị)
1. Trỏ domain (A record) về IP server
2. Sử dụng reverse proxy để cấp SSL (Nginx Proxy Manager, Caddy, Traefik...)
3. Cập nhật `APP_URL=https://yourdomain.com` trong compose rồi khởi động lại:
```bash
docker compose up -d
```

## 6) Vận hành hằng ngày
Trạng thái và log:
```bash
docker compose ps
docker compose logs -f app | cat
```

Cập nhật phiên bản mới:
```bash
docker compose pull
docker compose up -d
docker compose exec app php artisan migrate --force
```

Khởi động lại/dừng:
```bash
docker compose restart
docker compose down
```

Truy cập shell trong container:
```bash
docker compose exec app bash
```

Sao lưu/khôi phục database:
```bash
# Backup
docker compose exec db mysqldump -u root -p hanaya_shop > backup.sql
# Restore
docker compose exec -T db mysql -u root -p hanaya_shop < backup.sql
```

Scale nhiều replica ứng dụng (tùy chọn):
```bash
docker compose up -d --scale app=2
```

## 7) Biến môi trường thường dùng
Cấu hình trong `docker-compose.yml` hoặc file `.env` được mount:
- Ứng dụng: `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`
- Database: `DB_HOST`, `DB_PORT=3306`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Cache/Queue: `QUEUE_CONNECTION=redis`, `REDIS_HOST=redis`, `REDIS_PORT=6379`
- Email: `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`
- Tích hợp: `TINYMCE_API_KEY`, `MAPS_API_KEY`
- Thanh toán: `PAYMENT_PAYPAL_ENABLED`, `PAYMENT_PAYPAL_KEY`, `PAYMENT_CARD_ENABLED`, `PAYMENT_COD_ENABLED`

Sau khi thay đổi biến môi trường, khởi động lại:
```bash
docker compose up -d
```

## 8) Ghi chú cho Windows/macOS
- Dùng Docker Desktop và PowerShell/Terminal có sẵn lệnh `docker`/`docker compose`
- Giữ nguyên named volumes trong compose để tương thích đa nền tảng

## 9) Khắc phục sự cố
- 500 (Vite manifest): đảm bảo image production có `public/build`; chạy `php artisan optimize`
- Lỗi DB: kiểm tra thông số kết nối và service `db` đã sẵn sàng
- Migration “table exists”: kiểm tra và chạy thận trọng:
  ```bash
  docker compose exec app php artisan migrate:status
  docker compose exec app php artisan migrate --force
  ```
- Quyền thư mục: nếu cần
  ```bash
  docker compose exec app chown -R www-data:www-data storage
  ```

## 10) Danh sách bảo mật
1. Đổi toàn bộ mật khẩu mặc định (DB root/user, tài khoản admin)
2. Thiết lập `APP_KEY` và để `APP_DEBUG=false` ở production
3. Mở tường lửa tối thiểu (80/443) hoặc sau reverse proxy
4. Thiết lập backup database định kỳ
5. Cập nhật images thường xuyên và theo dõi logs

---

Hanaya Shop — Sẵn sàng Production với Docker Images

</details>
