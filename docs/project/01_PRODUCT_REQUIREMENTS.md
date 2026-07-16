# Yêu cầu sản phẩm Hanaya Shop

> Status: Canonical  
> Last verified against code: 2026-07-17  
> Primary sources: `README.md`, `routes/`, `app/Http/Controllers/`, `app/Models/`, `resources/views/`, `resources/lang/`, `database/migrations/`, `tests/`

## 1. Product vision

Hanaya Shop hướng tới một online storefront giúp một cửa hàng hoa/quà lưu niệm đưa sản phẩm đến khách hàng, quản lý tồn kho và xử lý order. Ý tưởng giảm hoa tồn/lãng phí đến từ `README.md`; source chưa có forecast, seller matching hay waste measurement nên đây là vision, không phải capability hiện tại.

## 2. Problem statement

Khách cần tìm, mua và theo dõi hoa/quà bằng nhiều ngôn ngữ. Cửa hàng cần quản lý catalog, content, customer, inventory và lifecycle order trong một back office. Hệ thống hiện giải quyết hai nhu cầu này bằng Laravel monolith; payment thật, logistics và multi-seller nằm ngoài implementation.

## 3. Target users

- Khách xem hàng chưa đăng nhập.
- Customer đã đăng ký với role `user`.
- Store administrator với role `admin`.
- `manager` tồn tại trong DB nhưng chưa có product requirement/permission được triển khai.

## 4. User roles and permissions

| Capability | Guest | `user` | `admin` | `manager` |
| --- | --- | --- | --- | --- |
| Catalog/post/about/chat/locale | Yes | Yes | Yes | Yes |
| Cart/checkout/order/review/address | No | Yes | Technically yes qua `auth`, nhưng UI hướng customer | Technically yes qua `auth` |
| Admin area | No | No | Yes | No |
| User/order/catalog/content administration | No | No | Yes | No |

Server authorization cho admin nằm ở `app/Http/Middleware/IsAdmin.php`; các route customer chủ yếu chỉ có `auth`, vì vậy ownership phải được controller enforce.

## 5. Functional requirements

| ID | Requirement and actor | Preconditions / expected behavior | Current status | Evidence / acceptance criteria |
| --- | --- | --- | --- | --- |
| FR-001 | Guest/customer xem homepage | DB khả dụng; hiển thị top seller, latest, sale, viewed, category và published posts | Implemented, verification pending | `User/DashboardController.php`; trang trả 200 và chỉ lấy post `status=true`. |
| FR-002 | Guest/customer duyệt catalog | Search `q`, category, `category_name`, sort và pagination | Implemented, verification pending | `User/ProductController@index`; query params giữ qua pagination. |
| FR-003 | Guest/customer xem product | Tăng `view_count`, show reviews và related products | Implemented, verification pending | `User/ProductController@show`; product không tồn tại trả 404. |
| FR-004 | Guest xem published posts | Search title/content, chỉ `status=true` | Implemented, verification pending | `User/PostController.php`. |
| FR-005 | Guest đăng ký qua email verification | Dữ liệu pending giữ trong session tối đa 24 giờ; chỉ tạo user khi token đúng | Partial | `Auth/RegisteredUserController.php`; test mặc định Breeze không tương thích và bị skip/exclude. |
| FR-006 | User đăng nhập/đăng xuất | Rate limit 5 attempts theo email+IP; admin redirect back office | Implemented, verification pending | `LoginRequest.php`, `AuthenticatedSessionController.php`. |
| FR-007 | User reset/update password | Reset link qua mail; update yêu cầu current password và policy mạnh | Implemented, external verification pending | Auth controllers + `app/Notifications/ResetPassword.php`; SMTP chưa kiểm tra. |
| FR-008 | User quản lý profile | Update name/email; email thay đổi reset verification timestamp; delete cần password | Implemented, verification pending | `ProfileController.php`, `ProfileUpdateRequest.php`. |
| FR-009 | User thêm/xóa cart item | Không vượt stock, chỉ thao tác item thuộc user | Partial | `CartController.php`; add lookup theo `session_id` kể cả authenticated user, DB không unique, quantity validation yếu. |
| FR-010 | User chọn item và preview checkout | Item chọn được lưu session; báo nếu client-declared quantity vượt client-declared stock | Partial | `CheckoutController@preview`; acceptance mong muốn phải revalidate server-side từ DB, hiện chưa đạt. |
| FR-011 | User tạo order | Address/payment hợp lệ; tạo order/details/payment, giảm stock, xóa cart atomically | Partial / unsafe | `CheckoutController@store`, `PaymentService.php`; price, subtotal, address ownership và stock concurrency chưa an toàn. |
| FR-012 | User xem order của mình | List/detail scope theo `user_id` | Implemented, verification pending | `OrderController@index/show`; order khác user trả 404 ở show. |
| FR-013 | User cancel order của mình | Chỉ trạng thái cho phép, restore stock một lần, payment failed | Partial / authorization defect | `OrderController@cancel` không scope owner/transition và payment collection update sai. |
| FR-014 | User xác nhận đã nhận hàng | Chỉ owner và order `shipped` chuyển `completed` | Partial / authorization defect | `OrderController@receive` hiện find theo ID toàn cục và không check prior state. |
| FR-015 | User review product đã mua | Order owner, product thuộc order, order `completed`, một review/order/product/user | Implemented, verification pending | `ReviewController.php`, DB unique `(user_id, product_id, order_id)`. |
| FR-016 | User lưu shipping address | Phone/address required và gắn authenticated user | Partial | `AddressController@store`; JSON error lộ file/line và checkout không kiểm tra address owner. |
| FR-017 | Người dùng đổi ngôn ngữ | Chỉ `en`, `vi`, `ja`; lưu session | Implemented | `LocaleController.php`, `SetLocale.php`, `config/app.php`. Dynamic DB content không translated. |
| FR-018 | Người dùng nhận hỗ trợ chatbot | Trả lời keyword, query catalog/order/news | Mock/demo only | `ChatbotController.php`; không có AI service. |
| FR-019 | Admin xem dashboard | Counts, revenue completed, recent orders, stock metrics | Implemented, partially tested | `Admin/DashboardController.php`; test tập trung behavior nhưng hiện DB test không kết nối. |
| FR-020 | Admin quản lý categories/products | CRUD, search, image, stock/filter | Implemented, partially tested | Admin controllers/routes; image tests phụ thuộc GD. |
| FR-021 | Admin quản lý users | CRUD/bulk delete/search; không self-edit/delete; block delete active-order user | Implemented, partially tested | `Admin/UsersController.php`; role input chỉ `user/admin`. |
| FR-022 | Admin quản lý posts | CRUD, publish flag, slug unique, rich-text image upload | Implemented, verification pending | `Admin/PostController.php`, `ImageUploadController.php`; feature tests là placeholder. |
| FR-023 | Admin xử lý orders | Search/filter, confirm, shipped, cancel, mark paid | Partial | `Admin/OrdersController.php`; không enforce allowed transition và không có dedicated tests. |
| FR-024 | Hệ thống thông báo order events | Mail + database, queued; customer locale lấy từ session của admin/request | Implemented, external verification pending | `app/Notifications/`, order controllers; queue/SMTP chưa E2E. |
| FR-025 | Operations kiểm tra health/version | `/up`, `/health`, `/api/version` trả runtime metadata | Implemented | `bootstrap/app.php`, `routes/web.php`; reverse proxy `/health` bypass Laravel. |

## 6. Non-functional requirements

| ID | Requirement | Current evidence/status |
| --- | --- | --- |
| NFR-001 | Authentication và server-side authorization | Partial: admin protected; order cancel/receive ownership defect. |
| NFR-002 | Input validation tại HTTP boundary | Partial: admin CRUD/review/auth có validation; cart/checkout item payload thiếu schema đầy đủ. |
| NFR-003 | Transactional order consistency | Partial: checkout/admin cancel/customer cancel dùng transaction; notifications dispatch before commit và stock race chưa xử lý. |
| NFR-004 | Idempotency/concurrency | Not implemented cho checkout/order transitions/payment callbacks. |
| NFR-005 | Secret/PII protection | Not met: tracked SQL dump, hard-coded deployment credentials và TinyMCE key; xem roadmap. |
| NFR-006 | Localization | Static UI có `en/vi/ja`; database content và một số literal error/message không localized. |
| NFR-007 | Performance | Product/dashboard cache và DB indexes có; invalidation drift, N+1/aggregate behavior chưa benchmark tin cậy. |
| NFR-008 | Reliability | Queue worker retry có trong compose; thiếu `failed_jobs` migration và monitoring integration. |
| NFR-009 | Maintainability | Route modules rõ; controller chứa business/data/file/integration logic và comments quá dài, coupling cao. |
| NFR-010 | Accessibility/responsive | Tailwind responsive classes có; TODO: Chưa thể xác minh accessibility bằng audit/browser từ repository hiện tại. |
| NFR-011 | Observability | Laravel logs, container logs, health endpoints có; không có metrics/APM/alert provider được cấu hình xác minh. |
| NFR-012 | Test isolation | Not met: cached config có thể khiến tests chạy vào DB demo; test guard còn chấp nhận điều này. |
| NFR-013 | Data retention/backup | Script backup có nhưng chứa credential và restore chưa verified. |
| NFR-014 | Tenant isolation | Not applicable hiện tại: application single-shop, không có `tenant_id`. |

## 7. Out of scope (current implementation)

- Marketplace/multi-seller, seller onboarding và commission.
- Payment gateway thật, webhook, refund và reconciliation.
- Courier/delivery tracking, delivery staff portal.
- AI/LLM chatbot và recommendation engine.
- Dynamic product/post translations.
- Coupon domain: files `Coupon.php`/`AppliedCoupon.php` rỗng, không có migration/route.
- Public mobile/partner REST API.

## 8. Product constraints

- Một product thuộc một category; xóa category cascade xóa products và dữ liệu phụ thuộc.
- Order currency không được lưu; UI/seed/comment dùng ký hiệu/đơn vị không nhất quán.
- Shipping fee hard-code `8` trong `config/constants.php`, không có currency/rate source.
- Uploaded images nằm trên local filesystem `public/images/*`; deployment phải mount/persist volume.
- Registration pending state phụ thuộc browser session; mở link ở session/device khác không thể xác minh.

## 9. Current gaps between requirements and implementation

1. “Reliable payments” trong README chỉ là demo record; không có external settlement.
2. “AI chatbot” là keyword matching.
3. “Guest cart” có code branch/model support nhưng routes bắt buộc auth.
4. Intended order lifecycle không được enforce, cho phép nhảy trạng thái/repeat cancellation.
5. Intended checkout integrity không đạt vì client kiểm soát item price/subtotal/quantity và address ID chưa scope owner.
6. README claims production-ready, zero downtime và số test cố định không được repository/runtime verification hỗ trợ.
7. Manager role tồn tại ở schema/factory nhưng không có permission model.
8. Static translations không bao phủ DB content và nhiều error string hard-code.

