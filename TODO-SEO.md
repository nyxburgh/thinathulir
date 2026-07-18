# Pending SEO / server follow-ups

- **HTTPS + non-www redirect on the live server** — removed from the repo's
  `.htaccess` / `public/.htaccess` for now (local dev has no SSL cert and
  the server copy is managed/uploaded separately). When ready, add back to
  the server's `.htaccess` (after `RewriteEngine On` / `RewriteBase /`):

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
