<?php
namespace App\Models;

use App\Core\Model;

class MediaModel extends Model
{
    protected string $table = 'tn_media';

    // Common fixed sizes — plenty for on-page PC/mobile display and share
    // previews; we never need the raw, full-resolution upload.
    private const DISPLAY_MAX_WIDTH = 1000;
    private const THUMB_MAX_WIDTH   = 400;

    public function allPaginated(int $page = 1, int $perPage = 24, string $search = ''): array
    {
        $where  = '';
        $params = [];
        if ($search) {
            $where  = 'filename LIKE ? OR alt_text LIKE ?';
            $params = ["%{$search}%", "%{$search}%"];
        }
        return $this->paginate($page, $perPage, $where, $params, 'id', 'DESC');
    }

    public function upload(array $file, int $userId): int|false
    {
        $cfg      = require CONFIG_PATH . '/app.php';
        $allowed  = $cfg['upload']['allowed'];
        $maxSize  = $cfg['upload']['max_size'];

        if (!in_array($file['type'], $allowed)) return false;
        if ($file['size'] > $maxSize) return false;

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('media_', true) . '.' . $ext;
        $folder   = date('Y/m');
        $dir      = $cfg['upload']['path'] . $folder;

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $dest = $dir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) return false;

        // Get image dimensions + convert to JPEG + resize + thumbnail
        $width = $height = null;
        $finalMime = $file['type'];
        if (str_starts_with($file['type'], 'image/')) {
            // Convert to JPEG first (skip if already jpeg) — JPEG shares
            // reliably everywhere; resizing afterward means only one encode.
            $jpegResult = $this->convertToJpeg($dest, $dir, $filename);
            if ($jpegResult !== $filename) {
                $filename  = $jpegResult;
                $dest      = $dir . '/' . $filename;
                $finalMime = 'image/jpeg';
            }
            [$width, $height] = @getimagesize($dest) ?: [null, null];
            // Resize to a common display width — plenty for both PC and mobile
            // article views; we're never showing the raw upload at full size.
            if ($width) {
                $this->createThumbnail($dest, $dest, self::DISPLAY_MAX_WIDTH); // overwrite original
                [$width, $height] = @getimagesize($dest) ?: [$width, $height];
            }
            // Create small thumbnail for news cards + social share previews
            $this->createThumbnail($dest, $dir . '/thumb_' . $filename, self::THUMB_MAX_WIDTH);
        }

        return $this->insert([
            'user_id'    => $userId,
            'filename'   => $file['name'],
            'filepath'   => $cfg['upload']['url_path'] . $folder . '/' . $filename,
            'thumb_path' => $cfg['upload']['url_path'] . $folder . '/thumb_' . $filename,
            'mime_type'  => $finalMime,
            'size'       => @filesize($dest) ?: $file['size'],
            'width'      => $width,
            'height'     => $height,
            'folder'     => $folder,
        ]);
    }

    /**
     * Convert an image file to JPEG, delete original, return new filename.
     * JPEG (not WebP) — WhatsApp's link-preview crawler is unreliable with
     * WebP og:images (works sometimes, silently fails other times), while
     * JPEG is universally supported everywhere shares happen.
     */
    private function convertToJpeg(string $srcPath, string $dir, string $originalName): string
    {
        if (!function_exists('imagejpeg')) return $originalName;

        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if ($ext === 'jpg' || $ext === 'jpeg') return $originalName;

        $info = @getimagesize($srcPath);
        if (!$info) return $originalName;

        $img = match ($info[2]) {
            IMAGETYPE_PNG  => @imagecreatefrompng($srcPath),
            IMAGETYPE_GIF  => @imagecreatefromgif($srcPath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($srcPath),
            default        => null,
        };
        if (!$img) {
            error_log("MediaModel::convertToJpeg — failed to decode source image: {$srcPath}");
            return $originalName;
        }

        // JPEG has no alpha channel — flatten onto white so transparent
        // areas (e.g. PNG logos) don't turn black.
        $w = imagesx($img); $h = imagesy($img);
        $flat = imagecreatetruecolor($w, $h);
        $white = imagecolorallocate($flat, 255, 255, 255);
        imagefill($flat, 0, 0, $white);
        imagealphablending($flat, true);
        imagecopy($flat, $img, 0, 0, 0, 0, $w, $h);
        imagedestroy($img);

        $jpegName = pathinfo($originalName, PATHINFO_FILENAME) . '.jpg';
        $jpegPath = $dir . '/' . $jpegName;

        if (imagejpeg($flat, $jpegPath, 75)) {
            imagedestroy($flat);
            @unlink($srcPath);
            return $jpegName;
        }
        imagedestroy($flat);
        return $originalName;
    }

    private function createThumbnail(string $src, string $dest, int $maxW): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            error_log("MediaModel::createThumbnail — GD not available, copying original as-is: {$src}");
            copy($src, $dest);
            return;
        }

        [$w, $h, $type] = @getimagesize($src) ?: [0, 0, 0];
        if (!$w) {
            error_log("MediaModel::createThumbnail — could not read image dimensions: {$src}");
            copy($src, $dest);
            return;
        }

        // Always decode + re-encode (even when already narrower than $maxW) so
        // compression is consistently applied — a plain copy() here was the
        // cause of oversized share images (e.g. an unoptimized 1.8MB PNG
        // passing straight through untouched because it was already <400px).
        $newW = min($maxW, $w);
        $newH = (int)round($h * ($newW / $w));

        $source = match($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($src),
            IMAGETYPE_PNG  => @imagecreatefrompng($src),
            IMAGETYPE_WEBP => @imagecreatefromwebp($src),
            default        => null,
        };
        if (!$source) {
            error_log("MediaModel::createThumbnail — failed to decode source (type={$type}): {$src}");
            copy($src, $dest);
            return;
        }

        $thumb = imagecreatetruecolor($newW, $newH);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newW, $newH, $w, $h);
        \App\Core\Helper::applyWatermark($thumb, 'THINATHULIR', 0.12, 'random');

        $ok = match($type) {
            IMAGETYPE_JPEG => imagejpeg($thumb, $dest, 70),
            IMAGETYPE_PNG  => imagepng($thumb, $dest, 8),
            IMAGETYPE_WEBP => imagewebp($thumb, $dest, 82),
            default        => false,
        };
        if (!$ok) {
            error_log("MediaModel::createThumbnail — encode failed (type={$type}), copying original as-is: {$src}");
            copy($src, $dest);
        }

        imagedestroy($source);
        imagedestroy($thumb);
    }

    public function deleteFile(int $id): bool
    {
        $media = $this->find($id);
        if (!$media) return false;

        $base = dirname(__DIR__, 2) . '/public';
        @unlink($base . $media['filepath']);
        @unlink($base . $media['thumb_path']);

        return $this->delete($id);
    }

    public function allFolders(): array
    {
        try {
            return $this->fetchAll(
                "SELECT COALESCE(folder,'general') AS folder, COUNT(*) AS count
                 FROM tn_media GROUP BY folder ORDER BY folder"
            );
        } catch (\Exception $e) { return []; }
    }

    public function moveToFolder(int $id, string $folder): void
    {
        try {
            $this->query("UPDATE tn_media SET folder = ? WHERE id = ?", [$folder, $id]);
        } catch (\Exception $e) {}
    }

}