<?php
namespace App\Models;

use App\Core\Model;

class CompanyAdModel extends Model
{
    protected string $table = 'tn_company_ads';

    private const ALLOWED_TYPES = ['square', 'horizontal', 'vertical'];

    public function listAll(): array
    {
        return $this->db->query(
            "SELECT * FROM tn_company_ads ORDER BY slot_type, sort_order ASC, id DESC"
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function activeBySlot(string $slotType, int $limit = 5): array
    {
        if (!in_array($slotType, self::ALLOWED_TYPES, true)) return [];
        $stmt = $this->db->prepare(
            "SELECT * FROM tn_company_ads WHERE slot_type = ? AND is_active = 1
             ORDER BY sort_order ASC, id DESC LIMIT " . (int)$limit
        );
        $stmt->execute([$slotType]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function upload(array $file, string $slotType, string $altText = ''): bool
    {
        if (!in_array($slotType, self::ALLOWED_TYPES, true)) return false;

        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) return false;

        $fname     = 'company_' . $slotType . '_' . uniqid() . '.' . $ext;
        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/company-ads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $path = $uploadDir . $fname;

        if (!move_uploaded_file($file['tmp_name'], $path)) return false;

        $this->resizeToAdPreset($path, $slotType);

        $sort = (int)$this->fetchColumn(
            "SELECT COALESCE(MAX(sort_order),0)+1 FROM tn_company_ads WHERE slot_type = ?",
            [$slotType]
        );

        return (bool)$this->insert([
            'slot_type'  => $slotType,
            'filepath'   => '/uploads/company-ads/' . $fname,
            'alt_text'   => $altText,
            'sort_order' => $sort,
            'is_active'  => 1,
        ]);
    }

    public function toggleActive(int $id): void
    {
        $this->db->prepare("UPDATE tn_company_ads SET is_active = 1 - is_active WHERE id = ?")
            ->execute([$id]);
    }

    public function delete(int $id): bool
    {
        $row = $this->fetchOne("SELECT filepath FROM tn_company_ads WHERE id = ?", [$id]);
        $this->db->prepare("DELETE FROM tn_company_ads WHERE id = ?")->execute([$id]);
        if (!empty($row['filepath'])) {
            $full = dirname(__DIR__, 2) . '/public' . $row['filepath'];
            if (is_file($full)) @unlink($full);
        }
        return true;
    }

    /**
     * Resize to the same fixed presets used for customer ads, so company
     * banners fill the vertical/square slots identically.
     */
    private function resizeToAdPreset(string $path, string $slotType): void
    {
        if (!function_exists('imagecreatetruecolor')) return;

        $info = @getimagesize($path);
        if (!$info) return;
        [$srcW, $srcH, $type] = $info;

        [$tgtW, $tgtH] = match ($slotType) {
            'horizontal' => [1000, 150],
            'vertical'   => [250, 750],
            default      => [900, 450],
        };

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_GIF  => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            default        => null,
        };
        if (!$src) return;

        $srcRatio = $srcW / $srcH;
        $tgtRatio = $tgtW / $tgtH;

        if ($srcRatio > $tgtRatio) {
            $scaleW = $tgtW;
            $scaleH = (int)round($srcH * ($tgtW / $srcW));
        } else {
            $scaleH = $tgtH;
            $scaleW = (int)round($srcW * ($tgtH / $srcH));
        }

        $scaled = imagecreatetruecolor($scaleW, $scaleH);
        imagealphablending($scaled, false);
        imagesavealpha($scaled, true);
        imagecopyresampled($scaled, $src, 0, 0, 0, 0, $scaleW, $scaleH, $srcW, $srcH);

        $pasteX = (int)round(($tgtW - $scaleW) / 2);
        $pasteY = (int)round(($tgtH - $scaleH) / 2);

        $final = imagecreatetruecolor($tgtW, $tgtH);
        imagealphablending($final, false);
        imagesavealpha($final, true);
        $bg = imagecolorallocate($final, 245, 245, 240);
        imagefill($final, 0, 0, $bg);
        imagecopy($final, $scaled, $pasteX, $pasteY, 0, 0, $scaleW, $scaleH);

        match ($type) {
            IMAGETYPE_PNG => imagepng($final, $path, 8),
            IMAGETYPE_GIF => imagegif($final, $path),
            default       => imagejpeg($final, $path, 85),
        };

        imagedestroy($src);
        imagedestroy($scaled);
        imagedestroy($final);
    }
}
