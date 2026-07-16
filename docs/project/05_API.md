# HTTP API và route map Hanaya Shop

> Status: Canonical  
> Last verified against code: 2026-07-17  
> Primary sources: runtime output `php artisan route:list --json`, `bootstrap/app.php`, `routes/web.php`, `routes/user.php`, `routes/auth.php`, `routes/admin.php`, `app/Http/Controllers/`, `app/Http/Requests/`, consuming Blade/JavaScript

## 1. API overview

Ứng dụng có 100 runtime routes tại thời điểm xác minh: 49 admin, 17 auth, 30 customer/global và 4 framework/operations. Hầu hết là web endpoints dùng session, CSRF, redirect/Blade response. Chỉ một số trả JSON. Không có `routes/api.php`, token auth hay public REST API độc lập.

## 2. Base path and versioning

- Customer/auth/global base: `/`.
- Admin base: `/admin`, route-name prefix `admin.`.
- Chỉ `/api/version` có chữ `api`; không có API version prefix/schema.
- Product có duplicate aliases: `/product*` và `/products*` cùng handler.

## 3. Authentication mechanisms

- Cookie/session Laravel `web` guard; login ở `POST /login`.
- State-changing web requests cần CSRF middleware token.
- Guest routes dùng middleware `guest`; protected routes dùng `auth`.
- Không có bearer/API key authentication.

## 4. Authorization rules

- `/admin/*`: `auth` + `app/Http/Middleware/IsAdmin.php`, yêu cầu exact role `admin`.
- Customer order list/show scope user; customer cancel/receive không scope owner (known critical defect).
- Address create assigns current user, nhưng checkout chỉ check address exists, không check ownership.
- Review create/store kiểm tra order owner và product membership.

## 5. Common request conventions

- HTML forms dùng form-urlencoded/multipart và `_method` cho PUT/PATCH/DELETE.
- JSON/AJAX handlers dựa vào `Accept`, `X-Requested-With` hoặc `?ajax=1` ở một số admin show routes.
- Uploaded images: 2 MB cho product/category/review; 10 MB cho editor endpoints; MIME allowlist theo controller.
- IDs là unsigned bigint nhưng route params không có numeric constraint.

## 6. Common response envelope

Không có common envelope. Response variants:

- Blade view: catalog, admin pages, auth pages.
- Redirect + session flash: CRUD/order/auth.
- JSON object: chatbot, address, search HTML fragment, uploads, notification mark-read, version.
- JSON paginated Eloquent: product reviews.

## 7. Error format

- Validation: redirect error bag cho normal forms; 422 JSON nếu request expects JSON.
- `findOrFail`: 404 default Laravel.
- Admin authorization: 403.
- JSON endpoints tự định nghĩa `{error}`, `{success}`, `{status}` không đồng nhất.
- `AddressController` và notification test endpoint có thể trả exception `file`/`line`; không an toàn production.

## 8. Pagination, filtering and sorting

| Resource | Parameters | Page size |
| --- | --- | --- |
| Products | `q`, `category`, `category_name`, `sort=asc|desc|sale|views|bestseller|latest`, `page` | 10 |
| Public posts | `search`, `page` | 10 |
| Customer orders | `page` | 10 |
| Admin products | `category_id`, `stock_filter=low_stock|out_of_stock`, `page` | 20 |
| Admin categories/users | `page` | 20 |
| Admin posts/orders | `search`; orders thêm `status`; `page` | 10 |
| Product detail reviews | `page` | 5 |
| Review JSON | `page` | 10 |

Không có standardized sort direction, cursor pagination hay max page size.

## 9. Endpoint inventory

Legend: `G` guest/public, `A` authenticated, `ADM` admin; `Inline` = controller validation, `Form` = FormRequest, `—` = no explicit request validation. Status refers implementation, not production verification.

### Customer/global (30)

| Method | Path | Auth | Actor | Purpose | Validation | Main handler | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/` | G | all | Homepage | — | `User\DashboardController@index` | Implemented |
| GET | `/about` | G | all | Static about | — | closure | Implemented |
| POST | `/addresses` | A | customer | Create address JSON | Inline | `AddressController@store` | Partial |
| GET | `/cart` | A | customer | Cart page | — | `User\CartController@index` | Implemented |
| POST | `/cart` | A | customer | Buy now | weak inline | `User\CartController@buyNow` | Partial |
| POST | `/cart/add/{id}` | A | customer | Add quantity | weak inline | `User\CartController@add` | Partial |
| GET | `/cart/remove/{id}` | A | customer | Remove cart row | — | `User\CartController@remove` | Implemented; unsafe method |
| POST | `/chatbot` | G | all | Rule-based chat JSON | weak inline | `ChatbotController@chat` | Mock/demo |
| GET | `/checkout` | A | customer | Checkout form | session precondition | `User\CheckoutController@index` | Partial |
| POST | `/checkout` | A | customer | Create order/payment | Inline partial | `User\CheckoutController@store` | Partial/unsafe |
| POST | `/checkout-preview` | A | customer | Save selected items in session | weak inline | `User\CheckoutController@preview` | Partial/unsafe |
| GET | `/checkout/success` | A | customer | Success page by query `order_id` | — | `User\CheckoutController@success` | Implemented |
| GET | `/dashboard` | A | customer | Homepage alias | — | `User\DashboardController@index` | Implemented |
| GET | `/locale/{locale}` | G | all | Change session locale | route regex + allowlist | `LocaleController@setLocale` | Implemented |
| GET | `/order` | A | customer | Own order list | — | `User\OrderController@index` | Implemented |
| GET | `/order/{id}` | A | customer | Own order detail | owner query | `User\OrderController@show` | Implemented |
| GET | `/order/cancel/{id}` | A | customer | Cancel order | none | `User\OrderController@cancel` | Critical defect |
| GET | `/order/receive/{id}` | A | customer | Mark completed | none | `User\OrderController@receive` | Critical defect |
| GET | `/posts` | G | all | Published posts/search | — | `User\PostController@index` | Implemented |
| GET | `/posts/{id}` | G | all | Published post detail | published query | `User\PostController@show` | Implemented |
| GET | `/product` | G | all | Product list alias | query handling | `User\ProductController@index` | Implemented |
| GET | `/product/{id}` | G | all | Product detail alias | — | `User\ProductController@show` | Implemented |
| GET | `/products` | G | all | Product list | query handling | `User\ProductController@index` | Implemented |
| GET | `/products/{id}` | G | all | Product detail | — | `User\ProductController@show` | Implemented |
| GET | `/product/{id}/reviews` | A | customer | Paginated reviews JSON | — | `User\ReviewController@getProductReviews` | Implemented |
| GET | `/profile` | A | user/admin | Profile edit | — | `ProfileController@edit` | Implemented |
| PATCH | `/profile` | A | user/admin | Update profile | Form | `ProfileController@update` | Implemented |
| DELETE | `/profile` | A | user/admin | Delete self | current password | `ProfileController@destroy` | Implemented |
| GET | `/review/create` | A | customer | Review form | business checks | `User\ReviewController@create` | Implemented |
| POST | `/review` | A | customer | Store review | Inline + business checks | `User\ReviewController@store` | Implemented |

### Authentication (17)

| Method | Path | Auth | Actor | Purpose | Validation | Handler | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| GET | `/register` | guest-only | guest | Registration form | — | `RegisteredUserController@create` | Implemented |
| POST | `/register` | guest-only | guest | Create pending registration/send email | Inline strong password | `RegisteredUserController@store` | Partial |
| GET | `/verification-notice` | guest-only | guest | Pending notice | session required | `RegisteredUserController@verificationNotice` | Partial |
| POST | `/verification-resend` | guest-only | guest | Resend custom token | session required; no throttle | `RegisteredUserController@resendVerification` | Partial |
| GET | `/verify-email/{token}` | guest-only | guest | Verify pending registration | token/session/24h | `RegisteredUserController@verifyEmail` | Partial |
| GET | `/verification-success` | A | user | Success page | — | `RegisteredUserController@verificationSuccess` | Implemented |
| POST | `/email/verification-notification` | A + throttle | user | Standard Laravel verification resend | throttle 6/min | `EmailVerificationNotificationController@store` | Legacy/mixed flow |
| GET | `/login` | guest-only | guest | Login form | — | `AuthenticatedSessionController@create` | Implemented |
| POST | `/login` | guest-only | guest | Authenticate | Form, rate limit 5 | `AuthenticatedSessionController@store` | Implemented |
| POST | `/logout` | A | user/admin | Logout/invalidate session | CSRF | `AuthenticatedSessionController@destroy` | Implemented |
| GET | `/forgot-password` | guest-only | guest | Reset request form | — | `PasswordResetLinkController@create` | Implemented |
| POST | `/forgot-password` | guest-only | guest | Send reset link | Inline email | `PasswordResetLinkController@store` | External verify pending |
| GET | `/reset-password/{token}` | guest-only | guest | Reset form | — | `NewPasswordController@create` | Implemented |
| POST | `/reset-password` | guest-only | guest | Reset password | Inline broker rules | `NewPasswordController@store` | Implemented |
| GET | `/confirm-password` | A | user/admin | Confirmation form | — | `ConfirmablePasswordController@show` | Implemented |
| POST | `/confirm-password` | A | user/admin | Confirm password | credential validation | `ConfirmablePasswordController@store` | Implemented |
| PUT | `/password` | A | user/admin | Update password | Inline strong password | `PasswordController@update` | Implemented |

### Admin (49)

All rows use `auth` + `IsAdmin`.

| Method | Path | Purpose | Validation | Handler | Status |
| --- | --- | --- | --- | --- | --- |
| ANY | `/admin` | Redirect `/admin/dashboard` | — | `RedirectController` | Implemented |
| GET | `/admin/dashboard` | Metrics dashboard | — | `Admin\DashboardController@index` | Implemented |
| GET | `/admin/product` | Product list/filter | query | `Admin\ProductsController@index` | Implemented |
| GET | `/admin/product/search` | Product search HTML JSON | query | `Admin\ProductsController@search` | Implemented |
| GET | `/admin/product/create` | Create form | — | `Admin\ProductsController@create` | Implemented |
| POST | `/admin/product` | Create product | Inline | `Admin\ProductsController@store` | Implemented |
| GET | `/admin/product/{id}` | HTML/JSON product detail | — | `Admin\ProductsController@show` | Implemented |
| GET | `/admin/product/{id}/edit` | Edit form | — | `Admin\ProductsController@edit` | Implemented |
| PUT | `/admin/product/{id}` | Update product | Inline | `Admin\ProductsController@update` | Implemented |
| DELETE | `/admin/product/{id}` | Delete product/image | — | `Admin\ProductsController@destroy` | Implemented/risky cascade |
| DELETE | `/admin/product/review/{reviewId}` | Delete review/image | — | `Admin\ProductsController@deleteReview` | Implemented |
| GET | `/admin/category` | Category list | — | `Admin\CategoriesController@index` | Implemented |
| GET | `/admin/category/search` | Search HTML JSON | query | `Admin\CategoriesController@search` | Implemented |
| GET | `/admin/category/create` | Create form | — | `Admin\CategoriesController@create` | Implemented |
| POST | `/admin/category` | Create category | Inline | `Admin\CategoriesController@store` | Implemented |
| GET | `/admin/category/{id}` | HTML/JSON detail | — | `Admin\CategoriesController@show` | Implemented |
| GET | `/admin/category/{id}/edit` | Edit form | — | `Admin\CategoriesController@edit` | Implemented |
| PUT | `/admin/category/{id}` | Update category | Inline | `Admin\CategoriesController@update` | Implemented |
| DELETE | `/admin/category/{id}` | Delete category/image | — | `Admin\CategoriesController@destroy` | Implemented/risky cascade |
| GET | `/admin/post` | Post list/search | query | `Admin\PostController@index` | Implemented |
| GET | `/admin/post/create` | Post form | — | `Admin\PostController@create` | Implemented |
| POST | `/admin/post` | Create post | Inline | `Admin\PostController@store` | Implemented |
| GET | `/admin/post/{id}` | Post detail | — | `Admin\PostController@show` | Implemented |
| GET | `/admin/post/{id}/edit` | Edit post | — | `Admin\PostController@edit` | Implemented |
| PUT | `/admin/post/{id}` | Update post | Inline | `Admin\PostController@update` | Implemented |
| DELETE | `/admin/post/{id}` | Delete post/images | — | `Admin\PostController@destroy` | Implemented |
| POST | `/admin/upload/ckeditor-image` | CKEditor-compatible upload | Inline file | `Admin\ImageUploadController@uploadCKEditorImage` | Implemented |
| POST | `/admin/upload/post-image` | Featured image upload JSON | Inline file | `Admin\ImageUploadController@uploadPostImage` | Implemented |
| POST | `/admin/posts/upload-image` | TinyMCE upload JSON | Inline file | `Admin\ImageUploadController@uploadTinyMCEImage` | Implemented |
| GET | `/admin/user` | User list | — | `Admin\UsersController@index` | Implemented |
| GET | `/admin/user/search` | Search HTML JSON | query | `Admin\UsersController@search` | Implemented |
| GET | `/admin/user/create` | Multi-user form | — | `Admin\UsersController@create` | Implemented |
| POST | `/admin/user` | Create users | Inline nested array | `Admin\UsersController@store` | Implemented |
| GET | `/admin/user/{id}` | User/order/cart detail | — | `Admin\UsersController@show` | Implemented |
| GET | `/admin/user/{id}/edit` | Edit user | self guard | `Admin\UsersController@edit` | Implemented |
| PUT | `/admin/user/{id}` | Update user/role/password | Inline + self guard | `Admin\UsersController@update` | Implemented |
| DELETE | `/admin/user/{id}` | Delete one user | active-order/self guard | `Admin\UsersController@destroySingle` | Implemented |
| DELETE | `/admin/user` | Bulk delete users | weak IDs + guards | `Admin\UsersController@destroy` | Implemented |
| GET | `/admin/order` | Order list/search/filter | query | `Admin\OrdersController@index` | Implemented |
| GET | `/admin/order/{id}` | Order detail | — | `Admin\OrdersController@show` | Implemented |
| PUT | `/admin/orders/{order}/confirm` | Set processing + notify | no transition validation | `Admin\OrdersController@confirm` | Partial |
| PUT | `/admin/orders/{order}/shipped` | Set shipped + notify | no transition validation | `Admin\OrdersController@shipped` | Partial |
| PUT | `/admin/orders/{order}/paid` | Mark first payment completed | no transition validation | `Admin\OrdersController@paid` | Partial |
| PUT | `/admin/orders/{order}/cancel` | Restore stock/cancel + notify | no transition validation | `Admin\OrdersController@cancel` | Partial |
| GET | `/admin/profile` | Admin profile form | — | `ProfileController@edit` | Implemented |
| PATCH | `/admin/profile` | Admin profile update | Form | `ProfileController@update` | Route-name redirect defect possible |
| DELETE | `/admin/profile` | Delete admin self | current password | `ProfileController@destroy` | Implemented |
| POST | `/admin/notifications/mark-read` | Mark own unread notification JSON | weak ID | `NotificationController@markAsRead` | Implemented |
| GET | `/admin/test-notifications` | Send all sample notifications | requires first test records | `Admin\NotificationTestController@test` | Test-only/dangerous |

### Framework/operations (4)

| Method | Path | Auth | Purpose | Handler | Status |
| --- | --- | --- | --- | --- | --- |
| GET | `/up` | public | Laravel health route | framework closure | Implemented |
| GET | `/health` | public | Plain `ok`; production Nginx returns its own `healthy` body | route/Nginx | Implemented, dual behavior |
| GET | `/api/version` | public | Git/build/runtime metadata JSON | closure in `routes/web.php` | Implemented; information exposure review needed |
| GET | `/storage/{path}` | public | Laravel local storage serving route | framework closure | Framework-provided |

## 10. Endpoint details by group

### Catalog/posts/chat

- Product list response is Blade; detail increments view count. Search is unescaped parameterized Eloquent `LIKE`, not raw SQL.
- Chat body: `{message?: string}`; response success `{response: string}`, error same shape with 500. No conversation state/rate limit beyond global web middleware.
- Public posts always filter `status=true`.

### Cart/checkout

- Cart add body: `quantity` (default 1). No explicit integer/min validation.
- Buy-now body: `product_id`, `quantity`.
- Preview body: `selected_items_json` array expected with `name`, `quantity`, `stock_quantity` and other fields later reused.
- Store body: `address_id`, `payment_method`, `payment_data` JSON, `selected_items_json`, optional `note`.
- Response is redirect. No idempotency; side effects include stock, order, payment, notification and cart deletion.

### Review/address JSON

- Review store multipart: `product_id`, `order_id`, `rating` 1..5, optional comment max 10000, optional 2 MB jpg/jpeg/png image.
- `GET /product/{id}/reviews` returns Laravel paginator JSON including review/user model fields; no explicit response resource.
- Address body: `phone_number`, `address`; success `{status:"success", address:{...}}`; error leaks exception metadata.

### Admin uploads/search

- Search responses return `{html, count?}`; HTML fragment is a presentation contract, not data API.
- Editor upload field names differ: `upload`, `image`, `file`; response keys differ: `url`, `{success,url,filename}`, `location`.
- Files are public and mutation is not transactional with posts/products/categories.

## 11. Webhooks

Không có webhook endpoint. Vì card/PayPal là mock nên không có signature verification, retry, provider event types hoặc idempotency. Khi tích hợp thật, webhook phải là endpoint riêng với raw-body signature verification và unique provider event ID.

## 12. OpenAPI status

Không có OpenAPI/Swagger/Postman spec trong repository. Runtime routes không sinh từ validators và response không dùng resource schema thống nhất. Vì vậy tài liệu này là route map canonical, không phải machine-verifiable API contract.

## 13. API gaps and inconsistencies

1. GET routes mutate cart/order state.
2. Customer order cancel/receive authorization defect.
3. Checkout accepts authoritative business values from client.
4. Duplicate singular/plural product routes.
5. JSON envelopes và upload field/response shapes không đồng nhất.
6. `admin.profile.update` dùng shared controller redirect tới non-admin route name, cần verify behavior.
7. Public `/api/version` exposes environment/framework/PHP versions.
8. Test notification route exists under production admin route group.
9. No rate limits for chatbot/uploads/registration resend outside login/verification defaults.
10. No API docs/tests for most customer and order endpoints.
