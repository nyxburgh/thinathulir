# தினத்துளிர் (Thinathulir)

Tamil-language news/media web app on a custom PHP MVC framework (no Composer/external framework). Multi-role editorial CMS with articles, live blogs, e-paper archive, plus a two-tier advertising system.

## `.htaccess` files — local vs. production

There are two independent pairs of `.htaccess` files; never cross-copy them:

- **Local (XAMPP) — live, in use**: repo-root `.htaccess` + `public/.htaccess`. App is served from the `/thinathulir/` subfolder under htdocs, so `RewriteBase` is `/thinathulir/` (root) and `/thinathulir/public/` (public) accordingly.
- **Production — staged for manual upload, not deployed yet**: `server/.htaccess` (→ upload to domain root) + `server/public/.htaccess` (→ upload to the server's `public/` folder). App will sit at the domain root there, so `RewriteBase` is `/` and `/public/`. The user (nyxburgh) uploads these manually at launch — do not deploy or push them.
- Both pairs' `public/.htaccess` carry a rule that hides `/public/` from the browser address bar for **frontend pages only** (redirects based on `%{THE_REQUEST}`, skipping `admin`/`portal`/`contribute`/`login`/`logout` paths, which keep showing `/public/`).
- When editing routing/rewrite behavior, update **both** pairs in parallel and keep the local one working against `http://localhost/thinathulir/` (don't break local dev while editing the production copy, and vice versa).

## Architecture

- Entry point: `public/index.php`. Manually parses `.env` (no phpdotenv), defines `ROOT_PATH`/`APP_PATH`/`CONFIG_PATH`/`VIEW_PATH`/`STORAGE_PATH`, sets `BASE_URL`/`ASSET_URL` from `APP_URL`. Root `.htaccess` forwards to `public/`.
- Custom autoloader: `App\Controllers\Frontend\HomeController` → `app/controllers/frontend/HomeController.php` (directory segments lowercased, filename case preserved, namespaces stay PascalCase).
- Core classes in `app/core/` (namespace `App\Core`):
  - **Router.php** — routes are `[method, path, 'namespace\Controller@method']` tuples from `config/routes.php`, `{param}` → regex capture, strips base path/`/public`.
  - **Controller.php** (abstract) — `view($view, $data, $layout='admin')` renders `app/views/{dot.path}.php` inside `app/views/layouts/{layout}.php`. Helpers: `json()`, `redirect()`, `requireAuth()`, `requireAdmin()`, `requireRole(...$roles)`, `requireCan($permission)`, `validateCSRF()`, `input()/post()/get()`, `flash()`.
  - **Model.php** (abstract) — thin PDO wrapper: `query/fetchAll/fetchOne/fetchColumn/find/all/insert/update/delete/count/paginate`. Subclasses set `protected string $table`.
  - **Database.php** — singleton PDO via `config/database.php`.
  - **Auth.php** — session-based; `Auth::user()/role()/can($permission)`. DB-backed permissions (`tn_permissions`/`tn_role_permissions`/`tn_users.role_id`) with an inline fallback matrix.
  - **CSRF.php, Session.php, GoogleOAuth.php, ApprovalService.php** — CSRF tokens, sessions, Google OAuth (contributor/reader login), article approval workflow.
  - **Helper.php** — `slug()`/`uniqueSlug()` (Tamil-aware), `assetUrl()`/`publicUrl()`/`assetVersioned()` (cache-busted via `filemtime`), `timeAgo()` (Tamil relative time), `applyWatermark()` (GD), `fetchUrlContent()` (curl+DOM scraper for importing external articles), `excerpt()`, `readTime()`, `e()` (HTML escaping).

## Directory structure

- `app/controllers/` — flat root (`AuthController`, `UserAuthController`, `ReaderAuthController`, `ContributorAuthController`) + `admin/` (~33 controllers), `frontend/` (~23), `contribute/` (Article/Dashboard/Profile/Series — external contributor portal).
- `app/models/` — flat, ~33 files (e.g. `ArticleModel` vs `FrontendArticleModel` split for admin vs public queries).
- `app/views/` — mirrors controllers: `admin/`, `frontend/`, `contribute/`, `auth/`, `errors/`, `partials/`, `layouts/` (`admin.php`, `editor_portal.php`, `portal.php`, `contributor.php`, `user_auth.php`, `auth.php`, `frontend.php`).
- `app/middleware/AuthMiddleware.php`, `app/services/` (`IndexNowService`, `PushService`, `SocialPostService`), `cron/`, `database/` (schema dumps + dated migrations), `config/`, `public/assets/{css,js,img}`, `public/uploads/`, `storage/` (logs/cache).

## User roles

`admin`, `chief_editor`, `editor`, `district_editor`, `category_editor`, `reporter`, `senior_reporter`, `staff_reporter`, `ads_manager`, `ad_owner` — role-based via `tn_roles`/`tn_role_permissions`. Layout picked by role tier: `admin` → `admin.php`; `chief_editor`/`staff_reporter` → `editor_portal.php`; other editorial roles → `portal.php`; external contributors → `contributor.php`.

Auth is split across several controllers rather than one: `AuthController` (admin login, `/admin/login`), `UserAuthController` (staff/editorial login, `/login`, device "forget" + PIN), `ContributorAuthController` (Google OAuth), `ReaderAuthController` (reader/subscriber login).

## Feature areas

- **News/articles** — `admin\ArticleController`/`ArticleModel` (create/edit/publish/bulk/toggle-breaking/pending-edits/suggest) + `frontend\ArticleController`/`FrontendArticleModel` (public reads, search, trending). Categories (hierarchical), `SpecialCategoryController`, tags, districts/locations, series, scheduled publishing (cron), RSS import (`RssController`/`RssModel`, `ContentImportModel`), URL-scrape import via `Helper::fetchUrlContent()`.
- **Live blog** — `LiveBlogController`+Model, admin+frontend.
- **Photo news / staff photo news**.
- **Newspaper e-paper archive** — `NewspaperController`+Model, PDF upload/download; `PrintEditionController`.
- **Polls** — `PollController`+Model.
- **Widgets** — sidebar widgets, `WidgetController`+Model.
- **Analytics** — `AnalyticsController`, `PerformanceController`, `ReporterPerformanceModel`.
- **Media library** — `MediaController`/`MediaModel`.
- **Multi-language** — `frontend\LangController`, `app/lang/`.
- **Citizen journalism** — `CitizenReportController`/Model (public submissions) + `admin\CitizenReportAdminController`.
- **Ratings/reactions** — `RateModel`/`RatingModel` + `public/assets/js/rating.js`. No threaded comment system exists.
- **Search** — `frontend\SearchController` → `FrontendArticleModel::search()`.
- **SEO** — `frontend\SeoController` (`/sitemap.xml`), `services/IndexNowService.php` (instant indexing ping), `ShortUrlController`/Model.
- **Push notifications** — `admin\PushController`/Model, `frontend\PushApiController`, `services/PushService.php`, Firebase FCM (HTTP v1 + OAuth2). Config: `config/firebase-service-account.json` (sensitive), `public/firebase-messaging-sw.js`.
- **Social auto-posting** — `services/SocialPostService.php` posts published articles to Facebook/Threads. Config templates: `config/facebook.json.example`, `config/threads.json.example` (real files gitignored).
- **Premium content** — `PremiumController`/`PremiumModel` (subscription-gated content).
- **Cron jobs** (`cron/`, see `cron/crontab.txt`): `scheduled_publish.php` (5 min), `youtube_import.php` (hourly, via `admin\YoutubeController`/Model), `rss_intake.php` (30 min).

### Advertising — two parallel systems

**Customer/business ads** (older, larger system) — advertisers pay for placement:
- `BusinessAdModel`, `admin\BusinessAdController`, `admin\AdController`, `admin\AdSlotController`, `admin\AdOwnerController`, `admin\AdSubscriptionController`, `admin\RateController` (pricing), `admin\PackageController`/`AdPackageModel`.
- Public detail page: `frontend\AdPublicController` → `/ad/{id}` (view: `app/views/frontend/ad/show.php`).
- Tables: `tn_ad_images`, `tn_ad_subscriptions`, `tn_sponsored_news`.
- Slot types: `square` / `horizontal` / `vertical`. Supports sponsored-article linking, click-through links, manual payment confirmation workflow (`confirm_ad_payment` permission) — no payment gateway integration exists.
- Ad detail page has a photo lightbox/popup for the customer's own uploaded gallery images (`app/views/frontend/ad/show.php`, `#lb` / `lbShow()`/`lbHide()`/`lbPrev()`/`lbNext()`). Lightbox image is sized via viewport units (`width:96vw;height:90vh`) so it scales up to fill the screen even when source images are lower-resolution.

**Company ads** (new, added 2026-07-17) — தினத்துளிர்'s own house/self-promo banners, shown alongside customer ads on the ad detail page:
- Migration: `database/migration_2026_07_17_company_ads.sql` → table `tn_company_ads`.
- `admin\CompanyAdController` / `CompanyAdModel` (restricted to `admin`/`chief_editor`). View: `app/views/admin/company_ads/index.php`, grouped by slot type.
- `CompanyAdModel::upload()` validates `slot_type` enum (`square`/`horizontal`/`vertical`), stores files under `public/uploads/company-ads/`, resizes via `resizeToAdPreset()` to fixed presets (horizontal 1000×150, vertical 250×750, square 900×450, letterboxed on `#f5f5f0`).
- Rendered on `app/views/frontend/ad/show.php` as plain `<img>` in `.cad-sidebar`/`.cad-hero-banner`/`.cad-squares` — **no click-through link and no lightbox popup** (unlike customer ad gallery images). This is intentional: company ads are static self-promo, not interactive listings.

## Config (`config/`)

- **app.php** — site name/URL/env/debug, timezone `Asia/Kolkata`, locale `ta`, `admin_prefix`, session config (`tn_session`, 7200s, httponly, SameSite=Lax), upload limits (5MB, jpeg/png/webp/gif), pagination (20/page).
- **database.php** — MySQL PDO from `.env` (`DB_HOST/PORT/NAME/USER/PASS`), utf8mb4, exceptions on, emulated prepares off.
- **routes.php** — flat array of `[method, path, handler]` tuples, commented by feature section (~540 lines).
- **facebook.json.example / threads.json.example** — credential templates for social posting.
- **firebase-service-account.json** — present in repo; treat as sensitive.
- `.env` (root) — `APP_NAME/URL/ENV/DEBUG`, `DB_*`, `YOUTUBE_API_KEY`, FCM keys, `GOOGLE_CLIENT_ID/SECRET`.

## Database

Table prefix: `tn_*` (e.g. `tn_users`, `tn_roles`, `tn_permissions`, `tn_articles`, `tn_media`, `tn_ad_images`, `tn_ad_subscriptions`, `tn_sponsored_news`, `tn_company_ads`).

Base dumps: `thinathulir.sql`, `tamilnews_db.sql`, `live_db.sql`, `local_db.sql`. No migration-tracking table/runner exists — migrations under `database/migration_*.sql` are applied manually, so **live and local DB schema can drift**; code defensively wraps optional/new columns in `try/catch` (e.g. `tn_ad_images.display_type`, `tn_sponsored_news.ad_id`) — keep this pattern when adding columns that may not exist everywhere yet.

## Conventions

- PascalCase `*Controller`/`*Model` classes; namespaces `App\Controllers\{Admin|Frontend|Contribute}`; directories lowercase, namespaces capitalized (autoloader reconciles).
- Views use dot-notation paths (`admin.company_ads.index` → `app/views/admin/company_ads/index.php`), wrapped by a layout chosen per role/section.
- CSRF validated on all POST actions via `CSRF::validate()`; always HTML-escape output with `Helper::e()`; permission checks via `requireRole()`/`requireCan()` in controller `middleware()` methods.
- Bootstrap 5.3.3 + Bootstrap Icons 1.11.3 (CDN) in admin/editor layouts; per-area CSS/JS under `public/assets/{css,js}` (`admin.css`, `frontend.css`, `editor_portal.css`, `contributor.css`, `portal.css`, `masthead.css`, `responsive.css`, `ads.css`); `Helper::assetVersioned()` for cache-busting.
- Tamil-specific handling throughout: Unicode-safe slugs, `rawurldecode()` for Tamil slugs in routes, Tamil relative timestamps, IST hardcoded.
- Image resize/crop-to-preset logic is duplicated between `BusinessAdModel` and `CompanyAdModel` (`resizeToAdPreset`) — a candidate for extraction into a shared helper if touched again.
