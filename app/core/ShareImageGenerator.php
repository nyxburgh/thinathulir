<?php
namespace App\Core;

/**
 * Composites a social-share graphic from an article.
 *
 * Two rendering modes:
 *  - Template mode (preferred): loads a designer-made background PNG from
 *    public/assets/img/share/template-{placement}.png (or template.png as a
 *    universal fallback). The designer paints flat, unmistakable marker
 *    colors as placeholder rectangles for where dynamic content goes —
 *    magenta for the photo, cyan for the headline, yellow for the body
 *    content, orange for the date/time. This class detects those marker
 *    regions automatically, infers the surrounding background color (so text
 *    continues whatever card/banner color the designer used), and drops in
 *    the real photo/text — no manual coordinates needed. Canvas size is
 *    whatever the template PNG's own dimensions are (not forced to a square).
 *  - Fallback mode: if no template exists yet, draws a plain hand-built
 *    1080x1080 card (logo/date header, photo, headline, footer) so the
 *    feature is testable before a template is supplied.
 */
class ShareImageGenerator
{
    private const CANVAS = 1080;
    private const TOP_BAND_H = 140;
    private const FOOTER_H = 150;
    private const PAD = 40;

    // Bright, deliberately unnatural marker colors — paint these as solid
    // flat rectangles in the Canva template. Detected via nearest-color
    // matching within MARKER_TOLERANCE, then replaced/covered at render time.
    private const MARKER_PHOTO     = [255, 0, 255];   // magenta — article photo
    private const MARKER_HEADLINE  = [0, 255, 255];   // cyan    — headline
    private const MARKER_CONTENT   = [255, 255, 0];   // yellow  — full article content
    private const MARKER_DATE      = [255, 128, 0];   // orange  — date/time
    private const MARKER_TOLERANCE = 30;

    /**
     * @throws \RuntimeException if GD or a Tamil-capable font isn't available
     */
    public static function render(array $article, string $placement): string
    {
        if (!function_exists('imagecreatetruecolor')) {
            throw new \RuntimeException('GD image library not available on this server.');
        }
        $font = self::tamilFont();
        if (!$font) {
            throw new \RuntimeException('No Tamil-capable font found in app/assets/fonts/. Add NotoSansTamil-Bold.ttf (or similar) before generating share images.');
        }
        if (!in_array($placement, ['left', 'right', 'center'], true)) {
            $placement = 'center';
        }

        $templatePath = self::templatePath($placement);
        $canvas = $templatePath
            ? self::renderFromTemplate($templatePath, $article, $font)
            : self::renderFallback($article, $placement, $font);

        $root = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2));
        $dir = $root . '/public/uploads/share-images/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $name = 'share_' . ((int)($article['id'] ?? 0)) . '_' . time() . '.jpg';

        // Flatten onto white before saving as JPEG (no alpha channel there).
        $w = imagesx($canvas); $h = imagesy($canvas);
        $flat = imagecreatetruecolor($w, $h);
        $white = imagecolorallocate($flat, 255, 255, 255);
        imagefill($flat, 0, 0, $white);
        imagealphablending($flat, true);
        imagecopy($flat, $canvas, 0, 0, 0, 0, $w, $h);
        imagedestroy($canvas);

        imagejpeg($flat, $dir . $name, 90);
        imagedestroy($flat);

        return '/uploads/share-images/' . $name;
    }

    // ---------------------------------------------------------------
    // Template mode
    // ---------------------------------------------------------------

    private static function templatePath(string $placement): ?string
    {
        $root = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2));
        foreach (["/public/assets/img/share/template-{$placement}.png", '/public/assets/img/share/template.png'] as $rel) {
            if (is_file($root . $rel)) return $root . $rel;
        }
        return null;
    }

    private static function renderFromTemplate(string $templatePath, array $article, string $font)
    {
        $img = imagecreatefrompng($templatePath);
        imagealphablending($img, true);
        imagesavealpha($img, true);

        $photoBox = self::findMarker($img, self::MARKER_PHOTO);
        if ($photoBox) {
            $srcPhoto = self::loadArticleImage($article['image_url'] ?? null);
            if ($srcPhoto) {
                self::pasteCover($img, $srcPhoto, $photoBox[0], $photoBox[1], $photoBox[2], $photoBox[3]);
                imagedestroy($srcPhoto);
            }
        }

        $dateBox = self::findMarker($img, self::MARKER_DATE);
        if ($dateBox) {
            $bg = self::inferBackground($img, $dateBox);
            self::fillRect($img, $dateBox, $bg);
            self::drawFittedText($img, $font, date('d-m-Y H:i'), $dateBox, 30, 14, self::textColorFor($bg));
        }

        $headlineBox = self::findMarker($img, self::MARKER_HEADLINE);
        if ($headlineBox) {
            $bg = self::inferBackground($img, $headlineBox);
            self::fillRect($img, $headlineBox, $bg);
            self::drawFittedText($img, $font, (string)($article['title'] ?? ''), $headlineBox, 56, 18, self::textColorFor($bg));
        }

        $contentBox = self::findMarker($img, self::MARKER_CONTENT);
        if ($contentBox) {
            $bg = self::inferBackground($img, $contentBox);
            self::fillRect($img, $contentBox, $bg);
            $body = self::plainText((string)($article['content'] ?? ''));
            self::drawFittedText($img, $font, $body, $contentBox, 34, 14, self::textColorFor($bg));
        }

        return $img;
    }

    /**
     * Locates a marker-colored rectangle by scanning a downscaled copy of the
     * image (fast — full-resolution 1080px+ scans would be needlessly slow)
     * and returns its bounding box scaled back to full resolution, or null if
     * that marker color isn't present (the slot is simply skipped).
     */
    private static function findMarker($img, array $rgb, int $scale = 4): ?array
    {
        $w = imagesx($img); $h = imagesy($img);
        $sw = max(1, (int)ceil($w / $scale));
        $sh = max(1, (int)ceil($h / $scale));
        $small = imagecreatetruecolor($sw, $sh);
        imagecopyresized($small, $img, 0, 0, 0, 0, $sw, $sh, $w, $h);

        $minX = null; $minY = null; $maxX = null; $maxY = null;
        for ($y = 0; $y < $sh; $y++) {
            for ($x = 0; $x < $sw; $x++) {
                $c = imagecolorat($small, $x, $y);
                $r = ($c >> 16) & 0xFF; $g = ($c >> 8) & 0xFF; $b = $c & 0xFF;
                if (abs($r - $rgb[0]) <= self::MARKER_TOLERANCE
                    && abs($g - $rgb[1]) <= self::MARKER_TOLERANCE
                    && abs($b - $rgb[2]) <= self::MARKER_TOLERANCE) {
                    if ($minX === null || $x < $minX) $minX = $x;
                    if ($maxX === null || $x > $maxX) $maxX = $x;
                    if ($minY === null || $y < $minY) $minY = $y;
                    if ($maxY === null || $y > $maxY) $maxY = $y;
                }
            }
        }
        imagedestroy($small);
        if ($minX === null) return null;

        // Expand by one downscaled cell on each side — the low-res scan can
        // miss a sliver of the marker right at its true edge (nearest-neighbor
        // sampling), leaving a thin unreplaced border otherwise.
        $x = max(0, ($minX - 1) * $scale);
        $y = max(0, ($minY - 1) * $scale);
        $bw = ($maxX - $minX + 3) * $scale;
        $bh = ($maxY - $minY + 3) * $scale;
        return [$x, $y, min($bw, $w - $x), min($bh, $h - $y)];
    }

    /** Samples just outside each edge of the box to infer the design's intended background color for that slot. */
    private static function inferBackground($img, array $box): array
    {
        [$x, $y, $w, $h] = $box;
        $iw = imagesx($img); $ih = imagesy($img);
        $samples = [];
        $points = [
            [$x + (int)($w / 2), max(0, $y - 3)],
            [$x + (int)($w / 2), min($ih - 1, $y + $h + 3)],
            [max(0, $x - 3), $y + (int)($h / 2)],
            [min($iw - 1, $x + $w + 3), $y + (int)($h / 2)],
        ];
        foreach ($points as [$px, $py]) {
            $c = imagecolorat($img, $px, $py);
            $samples[] = [($c >> 16) & 0xFF, ($c >> 8) & 0xFF, $c & 0xFF];
        }
        // Average the samples — good enough approximation of the surrounding fill.
        $r = (int)round(array_sum(array_column($samples, 0)) / count($samples));
        $g = (int)round(array_sum(array_column($samples, 1)) / count($samples));
        $b = (int)round(array_sum(array_column($samples, 2)) / count($samples));
        return [$r, $g, $b];
    }

    private static function fillRect($img, array $box, array $rgb): void
    {
        [$x, $y, $w, $h] = $box;
        $color = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($img, $x, $y, $x + $w - 1, $y + $h - 1, $color);
    }

    /** White text on dark backgrounds, dark text on light backgrounds — reads correctly either way without manual config. */
    private static function textColorFor(array $rgb): array
    {
        $luma = 0.299 * $rgb[0] + 0.587 * $rgb[1] + 0.114 * $rgb[2];
        return $luma < 128 ? [255, 255, 255] : [30, 30, 30];
    }

    private static function plainText(string $html): string
    {
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
        return trim($text);
    }

    // ---------------------------------------------------------------
    // Fallback mode (no template supplied yet)
    // ---------------------------------------------------------------

    private static function renderFallback(array $article, string $placement, string $font)
    {
        $canvas = imagecreatetruecolor(self::CANVAS, self::CANVAS);
        $white  = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        self::drawHeader($canvas, $font);

        $contentTop    = self::TOP_BAND_H;
        $contentBottom = self::CANVAS - self::FOOTER_H;
        $contentH      = $contentBottom - $contentTop;
        $black         = [30, 30, 30];

        $srcImg = self::loadArticleImage($article['image_url'] ?? null);

        if ($placement === 'center') {
            $imgBoxH = (int)round($contentH * 0.55);
            if ($srcImg) {
                self::pasteContain($canvas, $srcImg, self::PAD, $contentTop, self::CANVAS - self::PAD * 2, $imgBoxH);
                imagedestroy($srcImg);
            }
            $textTop = $contentTop + $imgBoxH + 20;
            self::drawFittedText($canvas, $font, (string)($article['title'] ?? ''), [self::PAD, $textTop, self::CANVAS - self::PAD * 2, $contentBottom - $textTop], 56, 18, $black);
        } else {
            $half = (int)round(self::CANVAS / 2);
            if ($placement === 'left') {
                $imgX = 0; $textX = $half + self::PAD;
            } else {
                $imgX = $half; $textX = self::PAD;
            }
            if ($srcImg) {
                self::pasteCover($canvas, $srcImg, $imgX, $contentTop, $half, $contentH);
                imagedestroy($srcImg);
            }
            self::drawFittedText($canvas, $font, (string)($article['title'] ?? ''), [$textX, $contentTop, $half - self::PAD * 2, $contentH], 56, 18, $black);
        }

        self::drawFooter($canvas);
        return $canvas;
    }

    private static function tamilFont(): ?string
    {
        $base = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2)) . '/app/assets/fonts/';
        // Hind Madurai (static, not a variable font) — variable Tamil fonts like the
        // current Noto Sans Tamil release produced corrupted glyph metrics with this
        // server's GD/FreeType build (mismeasured text width, overflowing wrapped lines).
        foreach (['HindMadurai-Bold.ttf', 'HindMadurai-Regular.ttf', 'NotoSansTamil-Bold.ttf', 'NotoSansTamil-Regular.ttf'] as $f) {
            if (is_file($base . $f)) return $base . $f;
        }
        return null;
    }

    private static function logoPath(): ?string
    {
        $root = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2));
        foreach (['/public/assets/img/share/logo-mark.png', '/public/assets/img/thinathulir.png'] as $rel) {
            if (is_file($root . $rel)) return $root . $rel;
        }
        return null;
    }

    private static function footerBarPath(): ?string
    {
        $root = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2));
        $path = $root . '/public/assets/img/share/footer-bar.png';
        return is_file($path) ? $path : null;
    }

    private static function loadArticleImage(?string $src)
    {
        if (!$src) return false;
        $data = false;
        if (preg_match('#^https?://#i', $src)) {
            $data = @file_get_contents($src);
        } else {
            $root = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__, 2));
            $path = $root . '/public' . $src;
            if (is_file($path)) $data = @file_get_contents($path);
        }
        if (!$data) return false;
        $img = @imagecreatefromstring($data);
        return $img ?: false;
    }

    private static function drawHeader($canvas, string $font): void
    {
        $logo = self::logoPath();
        $textX = self::PAD;
        if ($logo) {
            $li = @imagecreatefrompng($logo) ?: @imagecreatefromstring(file_get_contents($logo));
            if ($li) {
                self::pasteContain($canvas, $li, self::PAD, 20, 100, 100);
                imagedestroy($li);
                $textX = self::PAD + 100 + 20;
            }
        }

        $dateText = date('d-m-Y H:i');
        $fontSize = 22;
        $bbox = @imagettfbbox($fontSize, 0, $font, $dateText);
        $textH = $bbox ? abs($bbox[5] - $bbox[1]) : $fontSize;
        $y = (int)round((self::TOP_BAND_H + $textH) / 2);
        $gray = imagecolorallocate($canvas, 90, 90, 90);
        @imagettftext($canvas, $fontSize, 0, $textX, $y, $gray, $font, $dateText);

        $lineY = self::TOP_BAND_H - 1;
        $lineColor = imagecolorallocate($canvas, 230, 230, 230);
        imageline($canvas, 0, $lineY, self::CANVAS, $lineY, $lineColor);
    }

    private static function drawFooter($canvas): void
    {
        $y = self::CANVAS - self::FOOTER_H;
        $bar = self::footerBarPath();
        if ($bar) {
            $bi = @imagecreatefrompng($bar) ?: @imagecreatefromstring(file_get_contents($bar));
            if ($bi) {
                $bw = imagesx($bi); $bh = imagesy($bi);
                imagealphablending($canvas, true);
                imagecopyresampled($canvas, $bi, 0, $y, 0, 0, self::CANVAS, self::FOOTER_H, $bw, $bh);
                imagedestroy($bi);
                return;
            }
        }
        // Fallback: no footer-bar.png supplied yet — draw the site URL so the
        // image is still usable, rather than leaving a blank band silently.
        $font = self::tamilFont();
        $url  = defined('BASE_URL') ? BASE_URL : '';
        $lineColor = imagecolorallocate($canvas, 230, 230, 230);
        imageline($canvas, 0, $y, self::CANVAS, $y, $lineColor);
        if ($font && $url) {
            $fontSize = 22;
            $bbox = @imagettfbbox($fontSize, 0, $font, $url);
            $textW = $bbox ? abs($bbox[4] - $bbox[0]) : 0;
            $gray = imagecolorallocate($canvas, 120, 120, 120);
            @imagettftext($canvas, $fontSize, 0, (int)round((self::CANVAS - $textW) / 2), $y + (int)round(self::FOOTER_H / 2) + 8, $gray, $font, $url);
        }
    }

    /**
     * Draws $text inside the given [x,y,w,h] box, choosing the largest font
     * size between $minSize and $maxSize that lets the word-wrapped lines
     * fit within the box (both width and height), then vertically centers
     * the resulting text block — no truncation, no leftover gaps.
     */
    private static function drawFittedText($canvas, string $font, string $text, array $box, int $maxSize, int $minSize, array $colorRgb): void
    {
        [$x, $y, $w, $h] = $box;
        $text = trim($text);
        if ($text === '') return;
        $text = self::reorderTamilVowels($text);

        $color = imagecolorallocate($canvas, $colorRgb[0], $colorRgb[1], $colorRgb[2]);
        $best = null;

        for ($fontSize = $maxSize; $fontSize >= $minSize; $fontSize -= 2) {
            $lineH = (int)round($fontSize * 1.35);
            $lines = self::wrapText($text, $font, $fontSize, $w);
            $blockH = count($lines) * $lineH;
            $maxLineW = 0;
            foreach ($lines as $line) {
                $bbox = @imagettfbbox($fontSize, 0, $font, $line);
                if ($bbox) $maxLineW = max($maxLineW, abs($bbox[4] - $bbox[0]));
            }
            // wrapText can't break inside a single unbreakable word, so a wrapped
            // "line" can still come out wider than the box — only accept a size
            // where every line actually fits both height and width.
            if ($blockH <= $h && $maxLineW <= $w) {
                $best = ['size' => $fontSize, 'lines' => $lines, 'lineH' => $lineH, 'blockH' => $blockH];
                break;
            }
        }
        // Nothing fit even at the smallest size — use the smallest size anyway
        // and let it run to the box edge rather than silently drawing nothing.
        if (!$best) {
            $lineH = (int)round($minSize * 1.35);
            $lines = self::wrapText($text, $font, $minSize, $w);
            $best = ['size' => $minSize, 'lines' => $lines, 'lineH' => $lineH, 'blockH' => min($h, count($lines) * $lineH)];
        }

        $startY = $y + max(0, (int)round(($h - $best['blockH']) / 2)) + $best['size'];
        foreach ($best['lines'] as $i => $line) {
            $lineY = $startY + $i * $best['lineH'];
            if ($lineY > $y + $h + $best['size']) break; // ran out of vertical room — stop rather than spill far past the box
            $bbox = @imagettfbbox($best['size'], 0, $font, $line);
            $lineW = $bbox ? abs($bbox[4] - $bbox[0]) : 0;
            $lx = $x + max(0, (int)round(($w - $lineW) / 2));
            @imagettftext($canvas, $best['size'], 0, $lx, $lineY, $color, $font, $line);
        }
    }

    /**
     * GD's imagettftext has no text-shaping engine (no HarfBuzz) — it draws
     * glyphs strictly in Unicode encoding order. Tamil's pre-base vowel signs
     * (ெ/ே/ை, U+0BC6-8) are encoded AFTER their base consonant but must be
     * drawn BEFORE it, and the two-part vowels ொ/ோ/ௌ (U+0BCA-CC) split into a
     * pre-base + post-base pair around the consonant. Without this reorder
     * step, GD renders such syllables visually scrambled. This performs the
     * same visual reorder a shaping engine would, so plain imagettftext draws
     * it correctly. Only Tamil script is affected; everything else passes through.
     */
    private static function reorderTamilVowels(string $text): string
    {
        return preg_replace_callback(
            '/(.)(\x{0BCA}|\x{0BCB}|\x{0BCC}|\x{0BC6}|\x{0BC7}|\x{0BC8})/u',
            function (array $m): string {
                $base = $m[1];
                switch ($m[2]) {
                    case "\u{0BC6}": // ெ
                    case "\u{0BC7}": // ே
                    case "\u{0BC8}": // ை
                        return $m[2] . $base;
                    case "\u{0BCA}": // ொ = ெ + base + ா
                        return "\u{0BC6}" . $base . "\u{0BBE}";
                    case "\u{0BCB}": // ோ = ே + base + ா
                        return "\u{0BC7}" . $base . "\u{0BBE}";
                    case "\u{0BCC}": // ௌ = ெ + base + ௗ
                        return "\u{0BC6}" . $base . "\u{0BD7}";
                    default:
                        return $m[0];
                }
            },
            $text
        ) ?? $text;
    }

    private static function wrapText(string $text, string $font, int $fontSize, int $maxWidth): array
    {
        $words = preg_split('/\s+/u', $text) ?: [$text];
        $lines = [];
        $current = '';
        foreach ($words as $word) {
            $candidate = $current === '' ? $word : $current . ' ' . $word;
            $bbox = @imagettfbbox($fontSize, 0, $font, $candidate);
            $w = $bbox ? abs($bbox[4] - $bbox[0]) : 0;
            if ($w > $maxWidth && $current !== '') {
                $lines[] = $current;
                $current = $word;
            } else {
                $current = $candidate;
            }
        }
        if ($current !== '') $lines[] = $current;
        return $lines ?: [$text];
    }

    /** Scales the source image to fully cover the box, cropping overflow (used for left/right placement + template photo slots). */
    private static function pasteCover($canvas, $src, int $x, int $y, int $w, int $h): void
    {
        $sw = imagesx($src); $sh = imagesy($src);
        $scale = max($w / $sw, $h / $sh);
        $rw = (int)ceil($sw * $scale); $rh = (int)ceil($sh * $scale);
        $resized = imagecreatetruecolor($rw, $rh);
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $rw, $rh, $sw, $sh);
        $cropX = (int)round(($rw - $w) / 2);
        $cropY = (int)round(($rh - $h) / 2);
        imagecopy($canvas, $resized, $x, $y, $cropX, $cropY, $w, $h);
        imagedestroy($resized);
    }

    /** Scales the source image to fit within the box without cropping, centered (used for center placement + logo). */
    private static function pasteContain($canvas, $src, int $x, int $y, int $w, int $h): void
    {
        $sw = imagesx($src); $sh = imagesy($src);
        $scale = min($w / $sw, $h / $sh);
        $rw = max(1, (int)round($sw * $scale));
        $rh = max(1, (int)round($sh * $scale));
        $dx = $x + (int)round(($w - $rw) / 2);
        $dy = $y + (int)round(($h - $rh) / 2);
        imagealphablending($canvas, true);
        imagecopyresampled($canvas, $src, $dx, $dy, 0, 0, $rw, $rh, $sw, $sh);
    }
}
