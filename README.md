

# 🌸 Hanaya Shop

<details>
<summary><strong>🇯🇵 日本語</strong></summary>

## 目次

- [🔗 リンク](#links-jp)
- [概要](#overview-jp)
- [🎯 プロジェクト目的](#goals-jp)
- [🌟 機能（Features）](#features-jp)
  - [👤 顧客向け](#customers-jp)
  - [🛠️ 管理者向け](#admin-section)
- [🛠️ 技術スタック（Technologies Used）](#tech-jp)
  - [💡 ハイライトと実運用効果（Highlights & Impact）](#highlights-jp)
- [🗂️ ディレクトリ構成](#structure-jp)
- [🔗 インストール/セットアップのご案内](#install-jp)



## 🔗 リンク <a id="links-jp"></a>

- Webサイト: [Hanaya Shop](http://hanayashop.com)
- デモ動画: [YouTube Demo](https://youtu.be/your-demo-id)

## 概要 <a id="overview-jp"></a>

ベトナムでは、特に祝祭期に、鮮度が短い花が売れ残り、価値を生む前に廃棄されてしまう課題が存在します。販売機会の逸失と需要とのミスマッチが、事業者の損失と社会的な無駄を生み出しています。

**Hanaya Shop**は、この「もったいない」をテクノロジーで減らすために生まれたオンライン・フラワーショップです。販売者の露出を広げ、顧客との接点を増やし、最適なタイミングで最適な顧客に花を届ける——そのためのプロダクトとして設計されています。直感的なUI/UX、堅牢な在庫・注文・決済ドメイン、運用に耐える管理機能を備え、将来的には販売者と顧客のマッチングをさらに高度化して、廃棄ゼロに近づけることを目指します。

---

## 🎯 プロジェクト目的 <a id="goals-jp"></a>

- 現実の課題（廃棄）に向き合い、販売機会と需要のマッチングを最適化する
- 花屋向けにシンプルで拡張性の高いECプラットフォームを提供し、導入/運用コストを下げる
- 在庫・注文・決済の業務を安全に自動化し、人的ミスを減らす
- 管理者ダッシュボードで売上・在庫・人気商品などを可視化し、意思決定を高速化する
- 将来的な外部連携（決済、地図、通知、レコメンド）に備えた拡張性を確保する

---

## 🌟 機能（Features） <a id="features-jp"></a>

### 👤 顧客向け <a id="customers-jp"></a>
- 商品一覧・詳細、カテゴリ/用途/価格のフィルタリング
- ベストセラーや特価商品のハイライト表示
- カート、注文作成、購入履歴
- 多言語切替（例：日本語/英語/ベトナム語）
- 注文ステータスに応じたメール通知
- チャットボットによる購買サポート
- 直感的な住所選択（地図API連携）
- 多様な決済手段（代金引換、銀行カード、PayPal）

## 🛠️ 管理者向け <a id="admin-section"></a>
- 商品カテゴリ・商品CRUD（表示/非表示切替含む）
- 注文の承認/キャンセル/ステータス更新、効率的な処理UI
- 在庫監視（売り切れ/閾値接近の把握）
- 月次売上などのダッシュボード指標・統計
- 顧客管理、購入傾向の把握

---

## 🛠️ 技術スタック（Technologies Used） <a id="tech-jp"></a>

- PHP 8.2 / Laravel 12.2
- MySQL, Redis
- Blade, Tailwind CSS
- Docker Compose
- TinyMCE (Tiny Cloud), 各種Map API

### 💡 ハイライトと実運用効果（Highlights & Impact） <a id="highlights-jp"></a>

- Docker Compose: 環境差異を排除し、1コマンドで導入。本番更新はイメージ差し替えで安全・迅速。
- SSR + Tailwind: 初期表示が速くSEOに有利。離脱率を抑制し、コンバージョン改善。
- キュー（Redis）: メール通知や重い処理を非同期化し、応答速度を安定化。
- チャットボット: 購入前の疑問解消を自動化し、カゴ落ちを削減。
- TinyMCE: 記事/販促の表現力向上で集客を強化。
- Map API: 住所入力のミスを削減し、配送トラブルを減少。
- 複数決済（代金引換・カード・PayPal）: 決済ハードルを下げ、成約率を向上。

---

## 🗂️ ディレクトリ構成 <a id="structure-jp"></a>

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

## 🔗 インストール/セットアップのご案内 <a id="install-jp"></a>

- 本番環境（Production）: [DEPLOYMENT_GUIDE.md](./%23GUIDE/DEPLOYMENT_GUIDE.md)
- 開発環境（Developing）: [README_DEV.md](./%23GUIDE/README_DEV.md)

</details>


<details>
<summary><strong>🇺🇸 English</strong></summary>

## Table of Contents

- [🔗 Links](#links-en)
- [Overview](#overview-en)
- [🎯 Project Goals](#goals-en)
- [🌟 Features](#features-en)
  - [👤 For Customers](#customers-en)
  - [🛠️ For Admins](#admin-en)
- [🛠️ Technologies Used](#tech-en)
  - [💡 Highlights & Real-world Impact](#highlights-en)
- [🗂️ Project Structure](#structure-en)
- [🔗 Installation / Setup](#install-en)

## 🔗 Links <a id="links-en"></a>

- Website: [Hanaya Shop](http://hanayashop.com)
- Demo video: [YouTube Demo](https://youtu.be/your-demo-id)

## Overview <a id="overview-en"></a>

In Vietnam, especially during holidays, many fresh flowers are wasted because freshness is short and buyers are not reached in time. This mismatch between supply and demand hurts sellers and creates social waste.

**Hanaya Shop** is built to tackle this real problem. It expands exposure for sellers, increases buyer touchpoints, and helps every flower meet the right customer at the right time. With modern, intuitive UX, a reliable Laravel backend, SSR-first rendering, and a pragmatic domain model for inventory, orders, and payments, the platform is production-ready and designed to evolve toward smarter buyer–seller matching and near-zero waste.

---

## 🎯 Project Goals <a id="goals-en"></a>

- Confront the real-world waste problem by improving the match between supply and demand
- Offer a simple, extensible platform that lowers deployment and operating costs for flower shops
- Automate inventory, ordering, and payments safely to reduce human error
- Provide actionable insights via dashboards (revenue, stock, best-sellers) to speed decision-making
- Keep the architecture open for future integrations (payments, maps, notifications, recommendations)

---

## 🌟 Features <a id="features-en"></a>

### 👤 For Customers <a id="customers-en"></a>
- Product catalog and details with category/occasion/price filters
- Best-seller and special-deal highlights
- Cart, checkout, and order history
- Multi-language switching (e.g., Japanese/English/Vietnamese)
- Email notifications for order status updates
- Chatbot assistance during browsing and checkout
- Intuitive address selection with map API integration
- Multiple payment options: Cash on Delivery (COD), bank card, PayPal

## 🛠️ For Admins <a id="admin-en"></a>
- Category and product CRUD with visibility toggles
- Efficient order processing (approve/cancel/update status)
- Inventory monitoring (low-stock alerts)
- KPIs and dashboards including monthly revenue tracking
- Customer management and purchasing insights

---

## 🛠️ Technologies Used <a id="tech-en"></a>

- PHP 8.2 / Laravel 12.2
- MySQL, Redis
- Blade, Tailwind CSS
- Docker Compose
- TinyMCE (Tiny Cloud), Map API

### 💡 Highlights & Real-world Impact <a id="highlights-en"></a>

- Docker Compose: One-command installs and safe, image-based production updates; eliminates environment drift.
- SSR + Tailwind: Faster first paint and better SEO; reduces bounce and improves conversion.
- Queues (Redis): Offloads email and heavy tasks; keeps requests fast and stable.
- Chatbot: Automates pre-purchase Q&A; reduces cart abandonment.
- TinyMCE: Better, richer promotional content; improves engagement.
- Map API: Fewer address errors; fewer delivery issues and support tickets.
- Multiple payments (COD, bank card, PayPal): Lowers checkout friction; increases successful payments.

---

## 🗂️ Project Structure <a id="structure-en"></a>

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

## 🔗 Installation / Setup <a id="install-en"></a>

- Production guide: [DEPLOYMENT_GUIDE.md](./%23GUIDE/DEPLOYMENT_GUIDE.md)
- Development guide: [README_DEV.md](./%23GUIDE/README_DEV.md)

</details>


<details>
<summary><strong>🇻🇳 Tiếng Việt</strong></summary>

## Mục lục

- [🔗 Đường dẫn](#links-vi)
- [Giới thiệu](#overview-vi)
- [🎯 Mục tiêu dự án](#goals-vi)
- [🌟 Tính năng](#features-vi)
  - [👤 Trang người dùng](#customers-vi)
  - [🛠️ Trang quản trị](#admin-vi)
- [🛠️ Công nghệ sử dụng](#tech-vi)
  - [💡 Điểm nổi bật & Hiệu quả thực tế](#highlights-vi)
- [🗂️ Cấu trúc dự án](#structure-vi)
- [🔗 Hướng dẫn cài đặt / thiết lập](#install-vi)

## 🔗 Đường dẫn <a id="links-vi"></a>

- Trang web: [Hanaya Shop](http://hanayashop.com)
- Video demo: [YouTube Demo](https://youtu.be/your-demo-id)

## Giới thiệu <a id="overview-vi"></a>

Ở Việt Nam, đặc biệt vào các dịp lễ Tết, rất nhiều bông hoa bị bỏ đi do thời gian tươi ngắn và người bán không kịp tiếp cận đúng khách hàng. Sự lệch pha giữa cung và cầu gây lãng phí xã hội và thiệt hại cho người bán.

**Hanaya Shop** được xây dựng để giải quyết vấn đề thực tế đó. Nền tảng giúp mở rộng mức độ hiển thị của cửa hàng, tăng điểm chạm với khách hàng, và đưa mỗi bông hoa đến đúng người, đúng thời điểm. Ứng dụng có UI/UX hiện đại, backend Laravel tin cậy, SSR nhanh, và mô hình nghiệp vụ thực tế cho tồn kho, đơn hàng, thanh toán. Tầm nhìn dài hạn là tăng cường kết nối người bán–người mua, tiến tới giảm thiểu hoa bị lãng phí đến mức thấp nhất.

---

## 🎯 Mục tiêu dự án <a id="goals-vi"></a>

- Trực diện bài toán lãng phí bằng cách tối ưu kết nối cung–cầu và tăng chuyển đổi
- Cung cấp nền tảng đơn giản, dễ mở rộng, giảm chi phí triển khai/vận hành cho cửa hàng
- Tự động hóa an toàn các quy trình tồn kho, đặt hàng, thanh toán để giảm sai sót
- Cung cấp dashboard số liệu (doanh thu, tồn kho, bán chạy) hỗ trợ quyết định nhanh
- Mở đường cho tích hợp tương lai (thanh toán, bản đồ, thông báo, gợi ý sản phẩm)

---

## 🌟 Tính năng <a id="features-vi"></a>

### 👤 Trang người dùng <a id="customers-vi"></a>
- Danh mục/chi tiết sản phẩm, lọc theo loại/dịp/giá
- Nổi bật Best Seller, ưu đãi giảm giá mạnh
- Giỏ hàng, đặt hàng, lịch sử mua
- Đổi ngôn ngữ (Nhật/Anh/Việt)
- Thông báo qua email theo trạng thái đơn hàng
- Chatbot hỗ trợ tư vấn
- Chọn địa chỉ trực quan với bản đồ (Map API)
- Thanh toán đa dạng: Thanh toán khi nhận hàng (COD), thẻ ngân hàng, PayPal

## 🛠️ Trang quản trị <a id="admin-vi"></a>
- Quản lý danh mục, sản phẩm (CRUD, bật/tắt hiển thị)
- Xử lý đơn hàng tiện lợi (duyệt/huỷ/cập nhật trạng thái)
- Theo dõi tồn kho (cảnh báo sắp hết hàng)
- Thống kê/KPI, theo dõi doanh thu hàng tháng
- Quản lý khách hàng, phân tích hành vi mua

---

## 🛠️ Công nghệ sử dụng <a id="tech-vi"></a>

- PHP 8.2 / Laravel 12.2
- MySQL, Redis
- Blade, Tailwind CSS
- Docker Compose
- TinyMCE (Tiny Cloud), Map API

### 💡 Điểm nổi bật & Hiệu quả thực tế <a id="highlights-vi"></a>

- Docker Compose: Cài đặt 1 lệnh, cập nhật an toàn bằng cách thay image; loại bỏ sai lệch môi trường.
- SSR + Tailwind: Hiển thị đầu nhanh, tốt cho SEO; giảm bounce và tăng chuyển đổi.
- Hàng đợi (Redis): Đẩy email và tác vụ nặng sang nền; giữ request nhanh và ổn định.
- Chatbot: Tự động giải đáp trước khi mua; giảm tỷ lệ bỏ giỏ hàng.
- TinyMCE: Nội dung tiếp thị giàu hình ảnh; tăng tương tác.
- Map API: Ít sai địa chỉ; giảm lỗi giao hàng và hỗ trợ khách hàng.
- Thanh toán đa dạng (COD, thẻ ngân hàng, PayPal): Giảm ma sát khi checkout; tăng tỉ lệ thanh toán thành công.

---

## 🗂️ Cấu trúc dự án <a id="structure-vi"></a>

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

## 🔗 Hướng dẫn cài đặt / thiết lập <a id="install-vi"></a>

- Production: [DEPLOYMENT_GUIDE.md](./%23GUIDE/DEPLOYMENT_GUIDE.md)
- Developing: [README_DEV.md](./%23GUIDE/README_DEV.md)

</details>