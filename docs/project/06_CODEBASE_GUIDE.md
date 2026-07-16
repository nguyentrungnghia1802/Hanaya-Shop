# Hướng dẫn codebase Hanaya Shop

> Status: Canonical  
> Last verified against code: 2026-07-17  
> Primary sources: tracked repository tree, `bootstrap/app.php`, `routes/`, `app/`, `resources/`, `public/js/`, `config/`, `database/`, `tests/`, `composer.json`, `package.json`

## 1. Repository tree

```text
Hanaya-Shop/
├── app/
│   ├── Console/Commands/       # destructive/external test-mail helpers
│   ├── Http/Controllers/       # auth, customer, admin handlers + business logic
│   ├── Http/Middleware/        # locale and role checks
│   ├── Http/Requests/          # login/profile form requests
│   ├── Models/                 # Eloquent domain records
│   ├── Notifications/          # queued mail/database notifications
│   ├── Providers/              # payment create hook
│   ├── Services/               # payment simulation, cache helper
│   └── View/Components/        # Blade component classes
├── bootstrap/app.php           # Laravel bootstrap, route/health/middleware registration
├── config/                     # app, DB, queue, mail, filesystem and domain constants
├── database/
│   ├── migrations/             # canonical schema evolution
│   ├── factories/, seeders/    # test/development data
│   ├── schema/                 # MySQL snapshot
│   └── sql/                    # legacy/destructive/sensitive SQL artifacts
├── resources/
│   ├── views/                  # customer/admin/auth Blade UI
│   ├── lang/{en,vi,ja}/        # static translations
│   ├── js/                     # Vite JS entry/modules
│   └── css/                    # Tailwind entry
├── public/js/                  # legacy/page scripts loaded directly
├── routes/                     # web/auth/user/admin/console modules
├── tests/                      # PHPUnit unit/feature
├── deployment/                 # alternative compose/config/scripts
├── Dockerfile
├── docker-compose.production.yml
└── .github/workflows/          # CI/CD and policy
```

Ignored/generated/runtime paths: `vendor/`, `node_modules/`, `public/build/`, `storage/`, `bootstrap/cache/*.php`, `.phpunit.result.cache`. Không review/edit chúng như source.

## 2. Application entry points

| Entry point | Path / command | Responsibility |
| --- | --- | --- |
| HTTP | `public/index.php` → `bootstrap/app.php` | Laravel request lifecycle. |
| Routes | `routes/web.php` | Requires user/admin/auth modules and adds chat/locale/ops endpoints. |
| Frontend | `resources/js/app.js`, `resources/css/app.css` | Vite build inputs from `vite.config.js`. |
| Direct JS | `public/js/*.js` | Page/admin scripts outside bundler. |
| CLI | `artisan` | Laravel console. App commands under `app/Console/Commands/`. |
| Migration | `php artisan migrate` | Reads `database/migrations/`. |
| Tests | `php artisan test --testsuite=...` | PHPUnit config `phpunit.xml`. |
| Container | `Dockerfile` CMD Supervisor | Nginx + PHP-FPM + workers + scheduler. |
| Queue | `php artisan queue:work` | Notifications/mail. Production also has compose `queue` service. |
| Cron | Supervisor schedule loop | Runs `schedule:run`; no scheduled tasks defined. |

## 3. Module map

| Module | Responsibility / public interface | Dependencies | Key files / tests | Common changes |
| --- | --- | --- | --- | --- |
| Catalog/home | Homepage, product search/filter/detail | Product, Category, Review, OrderDetail, Cache | `User/DashboardController.php`, `User/ProductController.php`, product views; no direct customer tests | Catalog query, sort, display/cache. |
| Cart | User/session cart row management | Cart, Product, Auth, Session | `User/CartController.php`, cart view; model test is placeholder | Quantity/ownership/cart UI. |
| Checkout/payment | Convert selected cart rows to order/payment | Order aggregate, DB transaction, notifications, `PaymentService` | `User/CheckoutController.php`, payment Blade components; no direct tests | Highest-risk business changes. |
| Orders/reviews | History, transitions, reviews | Order, Payment, Product, Review, Notification | user/admin order controllers, `ReviewController.php`; no dedicated tests | State/authorization/idempotency. |
| Identity | Register/login/reset/profile/address | User, Mail, Password broker, session | auth/profile/address controllers; Auth tests excluded from suite `All` | Password/verification/ownership. |
| Admin catalog | CRUD category/product, images/search | Eloquent, filesystem, Cache | Admin controllers; unit+feature test files | Validations, storage, cascade effects. |
| Admin users | CRUD/search/delete policies | User/order/cart | `Admin/UsersController.php`; strong unit+feature coverage intent | Roles and deletion policy. |
| Content | Public/admin posts + editor uploads | Post/User/filesystem/TinyMCE | post/image controllers; feature tests placeholder | XSS/file lifecycle/slug. |
| Notifications | Event-specific mail/database payload | Queue, SMTP, User/Order | `app/Notifications/*.php`; notification test route only | Locale, queue, templates. |
| Localization | Static translation + locale session | Session/App config | `SetLocale.php`, `LocaleController.php`, `resources/lang/` | Add/update all 3 locales. |
| Operations | Image/process/health/deploy | Nginx/PHP-FPM/Redis/MySQL/GitHub Actions | Docker/deployment/workflows | Treat multiple variants cautiously. |

## 4. Layer responsibilities (actual)

- Routes: URL/method/middleware only, ngoại trừ closure about/health/version.
- Middleware: locale and coarse role gate.
- FormRequests: only login/profile; most validation inline in controllers.
- Controllers: HTTP plus domain orchestration, Eloquent queries, transactions, file IO and integration dispatch.
- Services: `PaymentService` simulation; `CacheService` helper whose keys do not fully match active controllers.
- Models: relations/accessors/fillable; limited business behavior.
- Adapters/repositories: không có dedicated layer.
- Views/JS: Blade owns forms; Alpine inline scripts own payment/address/chat interaction.
- Workers: framework queue workers execute notification classes.

## 5. Dependency direction

Observed direction: `routes → middleware/controllers → models/services/notifications → framework/DB/mail/filesystem`. Views depend on named routes/config/models. Business services currently depend concrete Eloquent models; no dependency inversion for external systems.

Không gọi controller từ controller. Khi thêm integration, tạo adapter service ở `app/Services` (hoặc explicit `app/Integrations`) và inject abstraction; không tiếp tục nhúng SDK vào checkout/order controllers.

## 6. Naming conventions

- PHP: PSR-4 `App\`, PascalCase classes, camelCase methods; Pint style.
- Routes: mixed singular names (`admin.product`) and plural URL segments; preserve existing contracts unless migration planned.
- DB: snake_case plural tables, bigint `id`, timestamps.
- Blade: `resources/views/{admin,page,auth,components}`; several filenames use nonstandard camelCase (`productDetail.blade.php`).
- Translations: file/domain key in all `en`, `vi`, `ja` directories.
- Tests: namespace sometimes differs from folder (`Tests\Unit\Admin` vs `Tests\Unit\Http...`); rely on class/path inspection.

## 7. Configuration loading

`bootstrap/app.php` loads Laravel config from `config/*.php`; env values should only be read through config outside config files. Exception: `routes/web.php` directly calls `env()` for build metadata, which breaks under config cache semantics. `config/constants.php` contains business/display constants such as shop contact, banners, shipping fee and review status.

Important drift:

- `config/cache.php` reads `CACHE_STORE`, while `.env.example` and compose use `CACHE_DRIVER`.
- Config cache can freeze local DB/env and override PHPUnit variables; always clear and preflight tests.
- `TINYMCE_API_KEY` exists in `.env.example`, but Blade currently hard-codes a key instead of reading config.

## 8. Error handling locations

- Framework exception setup: empty callback in `bootstrap/app.php`.
- Form validation: controller methods and two FormRequests.
- Transaction errors: checkout and order controllers.
- Dashboard fallback: broad catch in `Admin/DashboardController.php`.
- Payment logging: `PaymentService.php` and `PaymentCastServiceProvider.php`.
- Unsafe exception responses: `AddressController.php`, `Admin/NotificationTestController.php`.

## 9. Logging locations

- Laravel channels: `config/logging.php`, default stack/single depending env.
- Auth reset, payment, category image delete, chatbot and checkout log explicitly.
- `IsAdmin.php` logs every middleware invocation; may be noisy.
- Container logs: Nginx/PHP-FPM/Supervisor/worker paths in deployment config; volume/collection differs by compose.
- No structured correlation/request ID or PII redaction layer.

## 10. Generated code/assets

- `public/build/`: `npm run build`; do not edit.
- `bootstrap/cache/*.php`: Artisan cache artifacts; do not commit/use during tests without preflight.
- `storage/framework/`, `storage/logs/`: runtime.
- `database/schema/mysql-schema.sql`: generated-ish snapshot; update deliberately after migrations, not by hand drift.
- `public/images/*`: partly runtime uploads and may contain user content; not code.

## 11. Mock infrastructure

- Card/PayPal processing: `app/Services/PaymentService.php` + payment Blade components.
- Chatbot: `app/Http/Controllers/ChatbotController.php`.
- Test notification endpoint: `Admin/NotificationTestController.php` (can actually queue/send; not harmless mock).
- Placeholder tests: multiple model/service/post/image feature tests assert only `true`.
- Factories/seeders generate development data, not production reference data.

## 12. Common task map

| Task | Read first | Likely files | Required tests/checks |
| --- | --- | --- | --- |
| Add customer endpoint | `05_API.md`, flow doc | `routes/user.php`, controller, request/view/JS | focused feature test, route list, auth/ownership cases |
| Add admin endpoint | `05_API.md`, admin module | `routes/admin.php`, middleware/controller/view | admin + non-admin + guest tests |
| Change DB schema | `04_DATABASE.md` | new migration, model, factories/seeders, queries | disposable DB migrate/rollback + affected tests |
| Change checkout/order | `03_DOMAIN_AND_FLOWS.md` | checkout/order/payment/notifications/models | transaction, concurrency, authorization, idempotency integration tests |
| Modify auth | product req + API | `routes/auth.php`, auth controllers/requests/User | Auth suite explicitly; mail/session/device cases |
| Add UI page | product/flow/codebase docs | route/controller/Blade/lang/JS | render, states, responsive/a11y smoke |
| Change payment | architecture/domain/API | `PaymentService`, checkout, Payment model/migration | provider mock, webhook signature/idempotency, rollback |
| Add notification | architecture/ops | notification class, trigger, queue config | `Notification::fake`, payload/mail locale, worker test |
| Change uploads | security/database/codebase | controller/model/view + deployment volumes | MIME/size/path/file cleanup + storage isolation |
| Modify deployment | `08_DEPLOYMENT_AND_OPERATIONS.md` | chosen compose/workflow/script/Dockerfile | config render, image build, health in staging, rollback plan |

## 13. Files/modules requiring caution

- `app/Http/Controllers/User/CheckoutController.php`: money, stock, transaction, notifications and cart in one method.
- Both order controllers: state and inventory consistency.
- `database/migrations/*`: MySQL-specific and already applied.
- `database/sql/hanaya_shop_2025-09-30_10-42-44.sql`: sensitive; do not open/copy into output unnecessarily.
- `resources/views/layouts/admin.blade.php`: hard-coded third-party key and large inline editor setup.
- `resources/views/page/checkout/checkout.blade.php` + payment components: client-authoritative payload/demo payment.
- `Dockerfile`, two compose files, two production workflows and deployment scripts: overlapping topology/contracts.
- `bootstrap/cache/config.php`: ignored but can redirect tests to wrong DB.

