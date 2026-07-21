<?php
namespace App\Core;

class Helper
{
    /**
     * Stamp a semi-transparent text watermark onto a GD image.
     * Font size scales with image size so it looks right at any resolution.
     * No-op if FreeType/the bundled font isn't available — never fatal.
     *
     * @param float  $opacity  0..1 (e.g. 0.30 = 30% visible)
     * @param string $position 'center', 'corner' (bottom-right, small — for
     *                         content like ads where a center stamp would
     *                         cover the advertiser's own text/logo), or
     *                         'random' (small, low-opacity, one of a handful
     *                         of corner/edge spots — for photo content where
     *                         the mark should be present but unobtrusive)
     */
    public static function applyWatermark($img, string $text = 'THINATHULIR', float $opacity = 0.30, string $position = 'center'): void
    {
        if (!function_exists('imagettftext')) return;
        $fontPath = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2)) . '/app/assets/fonts/VeraBd.ttf';
        if (!is_file($fontPath)) return;

        $w = imagesx($img);
        $h = imagesy($img);
        if ($w < 60 || $h < 40) return; // too small for a legible watermark

        $pad = 8;
        if ($position === 'corner' || $position === 'random') {
            $fontSize = max(8, min(22, (int)round(min($w, $h) / 8)));
        } else {
            $fontSize = max(12, min(64, (int)round($w / 14)));
        }

        $bbox  = @imagettfbbox($fontSize, 0, $fontPath, $text);
        if (!$bbox) return;
        $textW = abs($bbox[4] - $bbox[0]);
        $textH = abs($bbox[5] - $bbox[1]);

        if ($position === 'random') {
            $spots = [
                [$pad, $h - $pad],                          // bottom-left
                [$w - $textW - $pad, $h - $pad],             // bottom-right
                [$pad, $textH + $pad],                       // top-left
                [$w - $textW - $pad, $textH + $pad],         // top-right
            ];
            [$x, $y] = $spots[array_rand($spots)];
            if ($x < $pad) return; // text wider than the image has room for — skip rather than overflow
        } elseif ($position === 'corner') {
            $x = $w - $textW - $pad;
            $y = $h - $pad;
            if ($x < $pad) return; // text wider than the image has room for — skip rather than overflow
        } else {
            $x = (int)round(($w - $textW) / 2);
            $y = (int)round(($h + $textH) / 2);
        }

        imagealphablending($img, true);
        // GD alpha runs 0 (opaque) .. 127 (fully transparent)
        $alpha = (int)round(127 * (1 - $opacity));
        $white = imagecolorallocatealpha($img, 255, 255, 255, $alpha);
        $black = imagecolorallocatealpha($img, 0, 0, 0, $alpha);

        // Dark outline so the text stays legible over both light and dark photos
        foreach ([[-1,-1],[-1,1],[1,-1],[1,1]] as [$ox, $oy]) {
            @imagettftext($img, $fontSize, 0, $x + $ox, $y + $oy, $black, $fontPath, $text);
        }
        @imagettftext($img, $fontSize, 0, $x, $y, $white, $fontPath, $text);
    }

    public static function slug(string $text): string
    {
        // Unicode-aware slug: keeps Tamil script + Latin letters/numbers.
        // (Previous version ran an ASCII-only iconv transliteration afterward,
        // which silently dropped every Tamil character, leaving a random
        // fallback hash as the "slug" for any Tamil-only title.)
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[\s\-]+/', '-', $text);
        $text = preg_replace('/[^\p{L}\p{N}\-]/u', '', $text);
        $text = trim($text, '-');
        return $text !== '' ? mb_substr($text, 0, 120) : substr(md5(uniqid()), 0, 8);
    }

    public static function uniqueSlug(string $table, string $slug, int $excludeId = 0): string
    {
        $db   = Database::getInstance();
        $base = $slug;
        $i    = 1;
        do {
            $sql  = "SELECT COUNT(*) FROM `{$table}` WHERE `slug` = ?";
            $params = [$slug];
            if ($excludeId) { $sql .= ' AND `id` != ?'; $params[] = $excludeId; }
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $count = (int)$stmt->fetchColumn();
            if ($count === 0) break;
            $slug = $base . '-' . $i++;
        } while (true);
        return $slug;
    }

    public static function readTime(string $content): int
    {
        $words = str_word_count(strip_tags($content));
        return max(1, (int)ceil($words / 200));
    }

    public static function excerpt(string $text, int $length = 160): string
    {
        $text = strip_tags($text);
        if (mb_strlen($text) <= $length) return $text;
        return mb_substr($text, 0, $length) . '…';
    }

    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function formatDate(string $date, string $format = 'd M Y, h:i A'): string
    {
        return date($format, strtotime($date));
    }

    /** All-numeric date/time — avoids auto-translate mangling word-based labels (e.g. "2 hours ago"). */
    public static function numericDate(string $date): string
    {
        return date('d/m/Y H:i', strtotime($date));
    }

    /**
     * Normalize a stored filepath to a full URL, handling old/new path formats.
     */
    public static function assetUrl(?string $path): string
    {
        if (!$path) return '';
        if (str_starts_with($path, 'http')) return $path;
        // Strip any legacy /public prefix variants
        $path = preg_replace('#^/[^/]+/public/#', '/uploads/', $path);
        $path = preg_replace('#^/public/#', '/uploads/', $path);
        return rtrim(ASSET_URL, '/') . '/' . ltrim($path, '/');
    }

    public static function siteUrl(): string
    {
        return rtrim(defined('BASE_URL') ? BASE_URL . '/public' : '', '/');
    }

    public static function publicUrl(string $path = ''): string
    {
        $base = self::siteUrl(); // already ends in "/public"
        if ($path === '') return $base;
        if (preg_match('~^https?://~i', $path) || str_starts_with($path, '//')) {
            return $path;
        }

        $path = str_replace('\\', '/', trim($path));
        if ($path === '') return $base;
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        // $base already supplies "/public" — strip a leading one from $path
        // so callers can pass either "/uploads/x.jpg" or "/public/uploads/x.jpg".
        if ($path === '/public') {
            $path = '';
        } elseif (str_starts_with($path, '/public/')) {
            $path = substr($path, 7);
        }
        return $base . $path;
    }

    public static function shareImageUrl(?string $path = null, string $fallback = '/assets/img/thinathulir.png'): string
    {
        $candidate = trim((string)($path ?? ''));
        if ($candidate === '') {
            $candidate = $fallback;
        }
        return self::publicUrl($candidate);
    }

    public static function assetVersioned(string $path): string
    {
        if ($path === '') return '';
        if (preg_match('~^https?://~i', $path) || str_starts_with($path, '//')) {
            return $path;
        }

        $path = str_replace('\\', '/', $path);
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        if (str_starts_with($path, '/public/')) {
            $publicPath = $path;
        } elseif (str_starts_with($path, '/assets/') || str_starts_with($path, '/uploads/') || str_starts_with($path, '/favicon')) {
            $publicPath = '/public' . $path;
        } else {
            $publicPath = '/public/' . ltrim($path, '/');
        }

        $absolute = rtrim(defined('BASE_URL') ? BASE_URL : '', '/') . $publicPath;
        $filePath = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2)) . $publicPath;
        $version  = is_file($filePath) ? (string)filemtime($filePath) : (string)time();
        return $absolute . '?v=' . $version;
    }

    public static function timeAgo(string $date): string
    {
        try {
            // DB stores time in IST (Asia/Kolkata = UTC+5:30)
            $tz   = new \DateTimeZone('Asia/Kolkata');
            $then = new \DateTime($date, $tz);
            $now  = new \DateTime('now', $tz);
            $diff = $now->getTimestamp() - $then->getTimestamp();
        } catch (\Exception $e) {
            $diff = time() - strtotime($date);
        }
        if ($diff < 60)     return 'இப்போது';
        if ($diff < 3600)   return (int)($diff/60) . ' நிமிடம் முன்';
        if ($diff < 86400)  return (int)($diff/3600) . ' மணி முன்';
        if ($diff < 604800) return (int)($diff/86400) . ' நாள் முன்';
        return self::formatDate($date, 'd M Y');
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes/1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes/1024, 1) . ' KB';
        return $bytes . ' B';
    }

    public static function sanitize(string $input): string
    {
        // Strip tags only — do NOT htmlspecialchars here
        // Encoding happens at display time via e() / htmlspecialchars in views
        return strip_tags(trim($input));
    }

    public static function redirect(string $url): void
    {
        // For relative URLs (e.g. /admin/login), prepend base + /public
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            static $base = null;
            if ($base === null) {
                $cfg    = require CONFIG_PATH . '/app.php';
                $parsed = parse_url(rtrim($cfg['url'] ?? '', '/'), PHP_URL_PATH) ?? '';
                $base   = rtrim($parsed, '/') . '/public';
            }
            if ($base && !str_starts_with($url, $base)) {
                $url = $base . $url;
            }
        }
        header("Location: {$url}");
        exit;
    }

    public static function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    public static function youtubeId(string $url): ?string
    {
        preg_match('/(?:v=|\/embed\/|\/shorts\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m);
        return $m[1] ?? null;
    }

    public static function youtubeThumbnail(string $videoId): string
    {
        return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
    }

    public static function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    public static function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    public static function generateHash(string $content): string
    {
        return hash('sha256', $content);
    }

    /** Human-readable reason the last fetchUrlContent() call failed, if any. */
    public static ?string $lastFetchError = null;

    /**
     * Fetch a third-party article URL and pull out just the title + body
     * paragraphs (no images — reporters attach their own). Best-effort HTML
     * scrape: prefers og:title / <article> markup, falls back to <title> /
     * <body>. Returns false on any network or parse failure — check
     * self::$lastFetchError for why.
     */
    public static function fetchUrlContent(string $url): array|false
    {
        self::$lastFetchError = null;
        $url = trim($url);
        if (!preg_match('#^https?://#i', $url)) {
            self::$lastFetchError = 'Invalid URL — must start with http:// or https://';
            return false;
        }
        if (!function_exists('curl_init')) {
            self::$lastFetchError = 'Server is missing the PHP curl extension.';
            error_log('fetchUrlContent: curl extension not available');
            return false;
        }

        $doFetch = function (bool $verifySsl) use ($url) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 5,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_SSL_VERIFYPEER => $verifySsl,
                CURLOPT_SSL_VERIFYHOST => $verifySsl ? 2 : 0,
                CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; ThinathulirBot/1.0)',
                CURLOPT_HTTPHEADER     => ['Accept: text/html,application/xhtml+xml'],
            ]);
            $html = curl_exec($ch);
            $result = [$html, curl_errno($ch), curl_error($ch), curl_getinfo($ch, CURLINFO_HTTP_CODE)];
            curl_close($ch);
            return $result;
        };

        [$html, $err, $errMsg, $code] = $doFetch(true);

        // Shared hosting often ships without a valid CA bundle, which makes every
        // HTTPS fetch fail with a cert-verification error (curl 60/77/51). Retry
        // once without peer verification rather than breaking the feature outright —
        // this is a read-only scrape of public article content, not a secrets exchange.
        if ($err && in_array($err, [51, 60, 77, 35], true)) {
            [$html, $err, $errMsg, $code] = $doFetch(false);
        }

        if ($err || !$html || $code >= 400) {
            self::$lastFetchError = $err
                ? "Network error: {$errMsg} (curl #{$err})"
                : "The site returned HTTP {$code}.";
            error_log("fetchUrlContent failed for {$url}: curl_errno={$err} msg={$errMsg} http_code={$code}");
            return false;
        }

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($doc);

        $title = '';
        $ogTitle = $xpath->query('//meta[@property="og:title"]/@content');
        if ($ogTitle->length) $title = trim($ogTitle->item(0)->nodeValue);
        if (!$title) {
            $titleNode = $doc->getElementsByTagName('title');
            if ($titleNode->length) $title = trim($titleNode->item(0)->textContent);
        }

        foreach (['script', 'style', 'nav', 'header', 'footer', 'aside', 'form', 'iframe', 'noscript'] as $tag) {
            $nodes = $doc->getElementsByTagName($tag);
            for ($i = $nodes->length - 1; $i >= 0; $i--) {
                $node = $nodes->item($i);
                $node->parentNode?->removeChild($node);
            }
        }

        $articleNodes = $doc->getElementsByTagName('article');
        $root = $articleNodes->length ? $articleNodes->item(0) : $doc->getElementsByTagName('body')->item(0);

        $content = '';
        if ($root) {
            foreach ($root->getElementsByTagName('p') as $p) {
                $text = trim(preg_replace('/\s+/', ' ', $p->textContent));
                if (mb_strlen($text) < 30) continue;
                $content .= '<p>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</p>' . "\n";
            }
        }

        if (!$title && !$content) {
            self::$lastFetchError = 'Fetched the page but could not find a title or any paragraphs to extract.';
            return false;
        }

        return [
            'title'   => mb_substr($title, 0, 250),
            'content' => $content,
        ];
    }
}
