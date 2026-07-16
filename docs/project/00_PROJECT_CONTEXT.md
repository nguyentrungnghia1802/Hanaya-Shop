# Bối cảnh dự án Hanaya Shop

> Status: Canonical  
> Last verified against code: 2026-07-17  
> Primary sources: `README.md`, `composer.json`, `package.json`, `bootstrap/app.php`, `routes/`, `app/`, `database/migrations/`, `resources/`, `tests/`, `Dockerfile`, `docker-compose.production.yml`, `.github/workflows/`

## 1. Project summary

Hanaya Shop là ứng dụng thương mại điện tử một cửa hàng, tập trung vào hoa, hoa thủ công và quà lưu niệm. Sản phẩm hướng tới khách mua trực tuyến và nhân viên quản trị cửa hàng. Tầm nhìn trong `README.md` là giảm hoa tồn và lãng phí bằng cách tăng khả năng tiếp cận khách hàng; source hiện tại triển khai một web shop truyền thống, chưa có marketplace hay thuật toán cân bằng cung-cầu.

Ứng dụng cung cấp storefront đa ngôn ngữ, catalog, cart, checkout, order, review, bài viết, chatbot theo luật và back office quản lý. Đây là Laravel monolith server-rendered; không có public REST API độc lập.

## 2. Current project status

| Area | Status | Evidence | Notes |
| --- | --- | --- | --- |
| Catalog, category, product detail | Implemented, verification pending | `app/Http/Controllers/User/ProductController.php`, `resources/views/page/products/` | Có search/filter/sort/pagination; cache không được invalidation đầy đủ. |
| Authentication/profile | Partial | `routes/auth.php`, `app/Http/Controllers/Auth/`, `app/Http/Controllers/ProfileController.php` | Đăng ký dùng pending data trong session và email riêng; test auth bị loại khỏi suite `All`. |
| Cart | Implemented, verification pending | `app/Http/Controllers/User/CartController.php`, `carts` migration | Routes đều yêu cầu auth dù controller còn nhánh guest; không merge guest cart khi login. |
| Checkout/order | Partial | `CheckoutController.php`, `OrderController.php`, `PaymentService.php` | Tạo order trong transaction nhưng tin price/subtotal từ client, thiếu khóa tồn kho và có lỗ hổng ownership ở cancel/receive. |
| Payment | Mock only (card/PayPal); COD local record only | `app/Services/PaymentService.php`, `resources/views/components/payment/` | Không gọi gateway, không webhook, transaction ID tự sinh. |
| Reviews | Implemented, verification pending | `ReviewController.php`, `reviews` migration | Có ownership/order/status/unique check; upload xảy ra trước một số business checks. |
| Posts/content | Implemented, verification pending | `User/PostController.php`, `Admin/PostController.php` | Public chỉ thấy `status=true`; TinyMCE upload lưu trực tiếp dưới `public/images/posts`. |
| Admin product/category/user/post | Implemented, partially tested | `routes/admin.php`, `app/Http/Controllers/Admin/`, `tests/Feature/Http/Controllers/Admin/` | Product/category/user có test đáng kể; post/image-upload feature test chỉ placeholder. |
| Admin order lifecycle | Partial | `Admin/OrdersController.php` | Có confirm/shipped/cancel/paid nhưng không enforce transition graph; thiếu test trực tiếp. |
| Notifications/email | Implemented, verification pending | `app/Notifications/`, `notifications` migration | Queueable mail + database notifications; queue config có rủi ro dispatch trước commit. |
| Chatbot | Mock/demo only | `app/Http/Controllers/ChatbotController.php` | Keyword/rule-based và DB lookup; không dùng AI/LLM. |
| Localization | Partial | `resources/lang/{en,vi,ja}/`, `SetLocale.php` | Static strings đa ngôn ngữ; dữ liệu product/post không được dịch. |
| Deployment automation | Partial, externally unverified | `Dockerfile`, compose files, `.github/workflows/`, `deployment/` | Có nhiều pipeline/script chồng lấn và credential hard-code; chưa xác minh production. |
| Automated tests | Partial, currently blocked | `phpunit.xml`, `tests/` | Sau khi clear config cache, DB test từ chối credential; xem `07_DEVELOPMENT_AND_TESTING.md`. |

## 3. Main capabilities

- Khách xem homepage, catalog, product detail, bài viết và đổi `en`/`vi`/`ja`.
- User đăng nhập quản lý profile, địa chỉ, cart, checkout, order history và review order đã `completed`.
- Admin xem dashboard, CRUD category/product/user/post, upload ảnh và cập nhật order/payment.
- Hệ thống gửi notification database và email qua queued Laravel notifications.
- Các endpoint `/up`, `/health` và `/api/version` hỗ trợ health/deployment inspection.

## 4. User roles and actors

| Actor | Runtime meaning |
| --- | --- |
| Guest | Xem catalog, product, post, about; chat; đăng ký/đăng nhập. Không dùng cart vì route cart có `auth`. |
| `user` | Customer chuẩn; mua hàng, xem order, review, profile và address. |
| `admin` | Qua `IsAdmin`; truy cập toàn bộ `/admin/*`. |
| `manager` | Có trong enum/factory nhưng không có route permission; bị từ chối bởi `IsAdmin`. Trạng thái: unused/partial. |
| Queue worker | Xử lý notification/mail khi `QUEUE_CONNECTION` không phải `sync`. |
| SMTP provider | Gửi registration verification, password reset và order notifications. Provider cụ thể phụ thuộc env. |
| TinyMCE CDN | Rich-text editor cho admin post; API key hiện hard-code trong `resources/views/layouts/admin.blade.php`. |
| Province API | UI checkout gọi `https://provinces.open-api.vn`; không có adapter server-side. |

## 5. Technology stack

| Layer | Technology |
| --- | --- |
| Language/runtime | PHP `^8.2`, Node.js 18 trong Docker/CI |
| Backend | Laravel 12.2, Eloquent, Blade, session-based web auth |
| Frontend | Blade SSR, Alpine.js 3, Tailwind CSS 3, Axios, Font Awesome, Vite 6 |
| Database | MySQL 8; migrations là schema source chính |
| Cache/session/queue | Laravel drivers; production compose định hướng Redis, local hiện có thể dùng database/file tùy env |
| Auth | Laravel `web` guard; role string/enum và `IsAdmin` middleware |
| Testing | PHPUnit 11 via Laravel test runner, MySQL test DB |
| Infrastructure | Multi-stage Docker image, Nginx + PHP-FPM + Supervisor, MySQL, Redis |
| CI/CD | GitHub Actions; test, staging và hai production workflows |
| Integrations | SMTP, TinyMCE CDN, public province API; card/PayPal chỉ simulated |

## 6. Repository overview

| Path | Responsibility |
| --- | --- |
| `app/Http/Controllers/` | HTTP handlers; phần lớn business logic nằm trực tiếp trong controller. |
| `app/Models/` | Eloquent models/relations. |
| `app/Services/` | `PaymentService` (mock processing) và cache helpers. |
| `app/Notifications/` | Order/password notifications qua mail/database. |
| `routes/` | Route modules customer, admin, auth và console. |
| `resources/views/`, `resources/js/`, `resources/lang/` | Blade UI, JS entrypoint và translations. |
| `public/js/`, `public/images/` | Script không qua Vite và upload runtime/local assets. |
| `database/migrations/` | Canonical schema evolution. |
| `database/schema/`, `database/sql/` | Snapshot/dump; có drift và dữ liệu nhạy cảm, không phải migration source. |
| `tests/` | Unit/feature; coverage tập trung admin controllers. |
| `deployment/`, `Dockerfile`, `docker-compose.production.yml` | Container và operations artifacts. |
| `.github/workflows/` | CI, staging, production và workflow monitoring. |
| `#GUIDE/`, `README.md` | Legacy docs; có claims/credentials không còn đáng tin cậy. |

## 7. Quick start reading order

- Developer mới: file này → [`06_CODEBASE_GUIDE.md`](06_CODEBASE_GUIDE.md) → [`03_DOMAIN_AND_FLOWS.md`](03_DOMAIN_AND_FLOWS.md) → [`07_DEVELOPMENT_AND_TESTING.md`](07_DEVELOPMENT_AND_TESTING.md).
- AI coding agent: [`../agent/agent.md`](../agent/agent.md) → file này → [`09_ROADMAP_AND_DECISIONS.md`](09_ROADMAP_AND_DECISIONS.md) → tài liệu theo task.
- Database: [`04_DATABASE.md`](04_DATABASE.md) → migrations liên quan → models/repository queries.
- API/HTTP: [`05_API.md`](05_API.md) → route file → controller/validator → consumer Blade/JS.
- Deployment: [`08_DEPLOYMENT_AND_OPERATIONS.md`](08_DEPLOYMENT_AND_OPERATIONS.md) → `Dockerfile` → compose/workflow/script được chọn.

## 8. Known limitations

- Checkout sử dụng price/subtotal/item list do client gửi; chưa bảo đảm integrity và concurrency.
- Customer cancel/receive không scope order theo authenticated user; cancel còn có lỗi thao tác trên Eloquent collection payment.
- Card/PayPal và chatbot không phải tích hợp thật.
- Không có OpenAPI, API versioning hay public API contract đầy đủ.
- Cache catalog/dashboard thiếu strategy invalidation đáng tin cậy.
- Test isolation guard không an toàn khi config cached; đợt xác minh 2026-07-17 đã reset nhầm DB demo local.
- Repo chứa SQL dump có PII/password hashes, credential trong deployment scripts và TinyMCE key hard-code. Cần rotate/revoke và purge history.
- Không có multi-tenancy, seller model, delivery tracking, monitoring/alerting integration hay verified restore drill.

## 9. Terminology

| Term | Meaning in this repository |
| --- | --- |
| Product | Mặt hàng thuộc đúng một category, có price, discount, stock và view count. |
| Cart item | Một row theo product + user/session; DB không enforce uniqueness. |
| Order detail | Snapshot line item; controller ghi `price`, không ghi column `subtotal`. |
| Payment | Local record; không đồng nghĩa payment gateway đã settle. |
| Completed | Order do customer đánh dấu received; cũng là điều kiện review và revenue dashboard. |
| Published post | `posts.status = true`. |
| Canonical docs | Bộ `docs/project/*`; source/migration vẫn ưu tiên cao hơn khi conflict. |

