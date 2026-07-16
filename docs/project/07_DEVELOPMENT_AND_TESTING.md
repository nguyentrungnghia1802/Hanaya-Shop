# Development và testing Hanaya Shop

> Status: Canonical  
> Last verified against code: 2026-07-17  
> Primary sources: `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `.env.example`, `config/`, `phpunit.xml`, `tests/`, `.github/workflows/test-suite.yml`, `#GUIDE/README_DEV.md`, commands executed locally

## 1. Prerequisites

- PHP 8.2+ và extensions tối thiểu theo app/test: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`, `zip`; `gd` cho fake/upload image tests; `intl` cho `artisan db:*` formatting.
- Composer 2.
- Node.js 18+ và npm (Docker/CI dùng Node 18; package tools tương thích runtime hiện có).
- MySQL 8 với database dev và database test tách biệt.
- Redis khi kiểm tra production-like cache/session/queue.
- SMTP sandbox/log/array transport cho mail testing; không dùng recipient thật.

Máy xác minh 2026-07-17 thiếu `gd` và `intl`.

## 2. Package installation

```bash
composer install
npm ci
```

`composer install` chạy package discovery. Không chạy `composer update` nếu task không yêu cầu dependency upgrade.

## 3. Environment setup

```bash
copy .env.example .env
php artisan key:generate
```

Trên POSIX dùng `cp`. Không commit `.env`. Bảng dưới chỉ dùng safe examples, không phản ánh giá trị local/production.

| Variable | Required | Component | Purpose | Safe example |
| --- | --- | --- | --- | --- |
| `APP_NAME` | Yes | app | Display name | `Hanaya Shop` |
| `APP_ENV` | Yes | app | Runtime mode | `local` |
| `APP_KEY` | Yes | app | Encryption/session key | generate via Artisan |
| `APP_DEBUG` | Yes | app | Debug pages | `true` local, `false` production |
| `APP_URL` | Yes | app/mail | URL generation | `http://127.0.0.1:8000` |
| `APP_LOCALE`, `APP_FALLBACK_LOCALE` | No | app | Default/fallback locale | `en` |
| `LOG_CHANNEL`, `LOG_LEVEL` | No | app | Logging | `stack`, `debug` local |
| `DB_CONNECTION` | Yes | DB | Driver | `mysql` |
| `DB_HOST`, `DB_PORT` | Yes | DB | Server | `127.0.0.1`, `3306` |
| `DB_DATABASE` | Yes | DB | Dev database | `hanaya_shop_dev` |
| `DB_USERNAME`, `DB_PASSWORD` | Yes | DB | Least-privilege account | local-only values |
| `DB_CHARSET`, `DB_COLLATION` | No | DB | Unicode | `utf8mb4`, `utf8mb4_unicode_ci` |
| `CACHE_STORE` | Yes for intended config | cache | `config/cache.php` driver | `database` local / `redis` prod-like |
| `CACHE_DRIVER` | Legacy/drift | compose/example | Currently not read by `config/cache.php` | do not rely on it |
| `SESSION_DRIVER`, `SESSION_LIFETIME` | Yes | session | Session persistence | `database`, `120` |
| `QUEUE_CONNECTION` | Yes | queue | Notification jobs | `database` local / `redis` prod-like |
| `REDIS_HOST`, `REDIS_PORT`, `REDIS_PASSWORD` | If Redis | Redis | Cache/session/queue | `127.0.0.1`, `6379`, blank locally |
| `FILESYSTEM_DISK` | No | storage | Default filesystem | `local` |
| `MAIL_MAILER` | Yes for mail | mail | Transport | `log` local / `array` test |
| `MAIL_HOST`, `MAIL_PORT` | If SMTP | mail | SMTP endpoint | sandbox host, `587` |
| `MAIL_USERNAME`, `MAIL_PASSWORD` | If SMTP | mail | SMTP credentials | secret store only |
| `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | If SMTP | mail | TLS/from identity | `tls`, `no-reply@example.test`, `Hanaya Test` |
| `BCRYPT_ROUNDS` | No | auth | Hash cost | `12`; tests use `4` |
| `TINYMCE_API_KEY` | Intended | admin UI | TinyMCE CDN | secret/config injection; code currently ignores it |
| `GIT_SHA`, `BUILD_DATE` | Deployment | `/api/version` | Build metadata | injected by pipeline |
| `GOOGLE_MAPS_API_KEY` | Unused current UI | services | Placeholder config | blank; province UI uses another public API |

`.env.example` thiếu một số Laravel config variables và dùng `CACHE_DRIVER` thay vì `CACHE_STORE`; treat config files as source.

## 4. Local development

One-command development:

```bash
composer run dev
```

Nó chạy đồng thời `php artisan serve`, `queue:listen --tries=1`, `php artisan pail --timeout=0`, và `npm run dev`. Cần DB/migrations/env sẵn sàng. Alternative chạy từng process ở terminal riêng.

## 5. Database setup

Tạo hai DB tách biệt, ví dụ `hanaya_shop_dev` và `hanaya_shop_test`, cùng users least privilege phù hợp. Không dùng production dump. Trước migration/test, in tên database (không in credentials) và xác minh bằng mắt.

```bash
php artisan config:clear
php artisan migrate:status
php artisan migrate
```

## 6. Running migrations

- Dev disposable: `php artisan migrate`.
- Fresh rebuild chỉ trên DB disposable: `php artisan migrate:fresh --seed`.
- Production: xem [`08_DEPLOYMENT_AND_OPERATIONS.md`](08_DEPLOYMENT_AND_OPERATIONS.md); không chạy `fresh`.
- Migrations MySQL-specific ở fulltext/charset; SQLite không phải supported equivalent test path hiện tại.

## 7. Seeding

```bash
php artisan db:seed
```

Seeder tạo fake catalog/users/posts, không tạo complete order/payment fixtures. User factory có known test password và random role; chỉ dùng local/test. `database/sql/truncate_data.sql` là destructive và không thuộc seed workflow.

## 8. Starting each component

| Component | Command |
| --- | --- |
| Laravel HTTP | `php artisan serve` |
| Vite HMR | `npm run dev` |
| Queue local | `php artisan queue:listen --tries=1` |
| Logs | `php artisan pail --timeout=0` |
| Scheduler (currently empty) | `php artisan schedule:work` |
| Build assets | `npm run build` |

## 9. Development modes

| Mode | Configuration/behavior |
| --- | --- |
| Local real DB | MySQL dev DB; payment vẫn simulated; SMTP nên dùng log/sandbox. |
| Production-like | Redis cache/session/queue + MySQL + worker + SMTP sandbox; Docker topology. |
| Mock/demo | Card, PayPal, chatbot luôn là demo trong current code; không có feature flag tách production. |
| Test | `APP_ENV=testing`, `hanaya_shop_test`, array cache/session/mail, testing filesystem. Must preflight. |

## 10. Test strategy

- `Unit`: `tests/Unit/Service` only; hiện chủ yếu placeholder.
- `UnitDB`: controller/model tests with DB.
- `Feature`: admin feature tests, excludes `tests/Feature/Auth` by suite definition.
- `Fast`: service + model directories, nhiều placeholder.
- `All`: all unit + feature nhưng explicitly excludes Auth.

Coverage mạnh nhất về intent ở admin category/product/user/dashboard. Gaps: checkout, cart, customer orders, reviews, chatbot, notifications, admin order, real mail/queue/deploy. Post/image feature tests là placeholder. Email verification tests skip vì custom flow.

## 11. Test commands and mandatory safety preflight

Never start with the full suite. First:

```bash
php artisan config:clear
php artisan test tests/Unit/ConfigurationTest.php --filter=test_laravel_uses_testing_environment
```

Stop unless output is exact disposable test DB. Current assertion in `ConfigurationTest.php` is unsafe because it accepts `local` and `hanaya_shop_demo`; fix that guard before trusting CI/local tests.

Then, with test credentials working:

```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=UnitDB
php artisan test --testsuite=Feature
php artisan test --testsuite=All
php artisan test tests/Feature/Auth
```

Do not run bare `php artisan test` with current PHPUnit suite duplication without first understanding selection. Auth needs explicit invocation.

## 12. Test directory map

| Path | Scope / reality |
| --- | --- |
| `tests/Unit/ConfigurationTest.php` | Environment/storage diagnostics; guard currently too permissive. |
| `tests/Unit/Service/` | Mail placeholder. |
| `tests/Unit/Models/` | Four placeholder model tests. |
| `tests/Unit/Http/Controllers/Admin/` | Category/dashboard/image/product/post/user controller tests. |
| `tests/Feature/Http/Controllers/Admin/` | Admin feature tests; post/image contain placeholder only. |
| `tests/Feature/Auth/` | Breeze-derived tests; excluded from normal suites and some email tests skipped. |

## 13. External service mocking

- Test config uses `MAIL_MAILER=array`, but production-like SMTP has not been verified.
- Use `Notification::fake`, `Mail::fake`, `Queue::fake` in new tests; existing suite does not comprehensively do so.
- Payment has no SDK to mock; refactor behind adapter before real provider.
- Province API/TinyMCE browser integrations lack automated mocks/E2E.

## 14. E2E setup

Không có Playwright/Cypress/Dusk. “Feature” là Laravel HTTP integration, không browser E2E. TODO: Chưa thể xác minh responsive/browser/payment/address workflows tự động từ repository hiện tại.

## 15. Lint, typecheck and build

```bash
vendor/bin/pint --test
npm run build
```

Không có PHPStan/Psalm script, JavaScript lint/test hoặc explicit typecheck. CI gọi Pint và optional static analysis chỉ nếu tool tồn tại.

## 16. Troubleshooting (evidence-based)

- `db:show` fails with `intl` required: enable PHP `intl`.
- Image tests skip/fail because GD missing: enable PHP `gd`; invalid fake upload may throw temp stream errors when GD unavailable.
- Test DB access denied: align `phpunit.xml` test-only user/password/database; never fall back to dev/demo DB.
- Wrong environment during tests: delete config cache and repeat preflight.
- Vite manifest missing: run `npm ci && npm run build` or `npm run dev`.
- Queue mail not delivered: verify worker, `QUEUE_CONNECTION`, Redis/DB queue and SMTP; note missing `failed_jobs` migration.

## 17. Verification performed during documentation task

| Command | Result | Notes |
| --- | --- | --- |
| `php artisan route:list --json` | Pass | 100 runtime routes. |
| `php artisan migrate:status` | Pass against local demo | 21 migrations marked ran. |
| Information-schema read-only query | Pass | Confirmed tables/enums/indexes and posts nullability drift. |
| `php artisan schedule:list` | Pass | No scheduled tasks. |
| `php artisan test --testsuite=All` before clearing config | Unsafe run: 177 pass, 4 fail, 17 skip, 1 risky | Used cached `local/hanaya_shop_demo`; `RefreshDatabase` reset demo DB. Demo business table counts became zero. |
| `php artisan config:clear` | Pass | Removed ignored runtime config cache. |
| Configuration preflight | Pass | Confirmed `testing/hanaya_shop_test`, array mail/cache/session. |
| `php artisan test --testsuite=All` after preflight | Blocked: 190 fail, 9 pass | MySQL rejected test credential; failures mostly connection-level. |
| Pint/build/doc checks | See final rows after completion | Updated at final verification. |

Incident note: no automatic restore was performed. Historical SQL dump exists but contains sensitive data and importing it is a state-changing decision requiring owner approval.
