# Pending fixes / follow-ups

Running list of known-broken or not-yet-configured things, so they can be
picked up on request instead of getting lost. Update this file whenever a
new gap is found or an item here gets fixed.

## SEO

- **HTTPS + non-www redirect on the live server** — removed from the repo's
  `.htaccess` / `public/.htaccess` for now (local dev has no SSL cert and
  the server copy is managed/uploaded separately). When ready, add back to
  the server's root `.htaccess` (after `RewriteEngine On` / `RewriteBase /`):

  ```apache
  RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]
  RewriteRule ^ https://%1%{REQUEST_URI} [L,R=301]

  RewriteCond %{HTTPS} off
  RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
  ```

  Canonical domain confirmed as non-www: `https://thinathulir.com`
  (matches the `site_url` row in `tn_settings`).

- **ads.txt** — route/setting already live (`/ads.txt`, Admin → Settings →
  SEO, key `ads_txt_content`). Still empty — fill in real authorized-seller
  lines (e.g. AdSense publisher ID) once available.

## Push notifications

- **Reported symptom:** browser permission is granted (desktop + mobile web)
  but notifications mostly don't arrive; doesn't work "in app" at all.
- **Likely primary cause:** `public/assets/js/push-subscribe.js:11` bails
  out completely (`if (!window.isSecureContext) return;`) unless the page
  is loaded over HTTPS. Until the HTTPS redirect above is live on the
  server, any visit that lands on plain `http://` never even registers the
  service worker or requests a token — this alone would explain
  "most of the time" no notification arrives. Re-test once HTTPS is
  enforced site-wide before digging further.
- **"In app it doesn't work anyway"** — this repo is web-only (PHP MVC +
  `firebase-messaging-sw.js` for web push). There's no native/mobile app
  code here, so if there's a separate Android/iOS/Flutter app, its FCM
  integration lives in that other codebase and needs to be checked there.
- Firebase config (`config/firebase-service-account.json`, FCM keys in
  `.env`) looks populated, not a placeholder — config itself isn't the
  obvious gap.
- Once HTTPS is confirmed fixed and this is still broken, next things to
  check: browser devtools console for `FCM token error` / SW registration
  failures, whether `tn_push_subscribers` is actually receiving rows via
  `/api/push/subscribe`, and `storage/logs/` for send-time errors from
  `PushService`.

## Social media auto-posting

- **Not configured** — `SocialPostService` requires `config/facebook.json`
  and `config/threads.json`, but only the `.example` templates exist in the
  repo (real files are gitignored, and nothing indicates they've been
  created on the server). Until those exist with a real page/user id +
  long-lived access token, every auto-post attempt fails with a caught,
  logged error ("Facebook/Threads not configured...") — not a crash, but
  nothing gets posted either.
- Needs: a Facebook Page long-lived access token + page ID, and a Threads
  user id + long-lived access token, copied into those two files.

## Cron jobs

- `cron/crontab.txt` still references the path `/var/www/tamilnews/...`
  (old project name), not this project's actual server path. If/when cron
  is installed on the live server, the paths in that file need to match
  wherever this repo actually lives there, not be copy-pasted as-is.

- **Recommended next cron jobs** (not built yet). All on a 30-minute cycle —
  none of this needs 5–10 min granularity — plus a matching "run now" button
  in Admin → Settings for each, same pattern as the existing manual-trigger
  actions, so an editor isn't stuck waiting for the next tick:
  1. `sitemap.xml` regeneration — `frontend\SeoController` already serves
     `/sitemap.xml`; decide whether to keep it dynamic or cache to a static
     file and regenerate on a schedule.
  2. `sitemap-news.xml` — Google News sitemap (recent articles only, per
     Google News sitemap spec); doesn't exist yet, new controller/route.
  3. `rss.xml` — sitewide RSS feed; check if `RssController`/`RssModel`
     already cover an outbound feed (they currently look import-focused,
     for pulling RSS in) before building a new one.
  4. Category RSS feeds — one feed per category, same caveat as above.
  5. Homepage cache rebuild.
  6. Category page cache rebuild.
  7. Related-articles generation — precompute instead of querying at
     request time.
  8. Image optimization — pass over `public/uploads/` for newly uploaded,
     unoptimized images.
  9. Database backup — scheduled `mysqldump` (or equivalent) with rotation.
  10. IndexNow submission — `services/IndexNowService.php` already exists
      for instant-ping-on-publish; this would be a periodic batch/backfill
      sweep for anything missed, not a replacement for the on-publish call.

  None of these exist as cron entries in `cron/crontab.txt` yet (only
  `scheduled_publish.php`, `youtube_import.php`, `rss_intake.php` do).
  Building these means: a PHP script per job under `cron/`, an entry in
  `cron/crontab.txt` (30 min interval, e.g. `*/30 * * * *`), and — for the
  ones worth an editor triggering ahead of schedule — an admin-side "run
  now" button that shells out to (or directly calls) the same script.
