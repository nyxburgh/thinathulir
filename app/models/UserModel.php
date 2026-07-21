<?php
namespace App\Models;
use App\Core\Model;

class UserModel extends Model
{
    protected string $table = 'tn_users';

    public function findByEmail(string $email): array|false
    {
        return $this->fetchOne(
            "SELECT u.*, r.slug AS role_slug, r.name AS role_name
             FROM tn_users u JOIN tn_roles r ON r.id = u.role_id
             WHERE u.email = ? AND u.is_active = 1 AND u.is_blocked = 0",
            [$email]
        );
    }

    public function findWithRole(int $id): array|false
    {
        return $this->fetchOne(
            "SELECT u.*, r.slug AS role_slug, r.name AS role_name,
                    d.name AS assigned_district_name
             FROM tn_users u
             JOIN tn_roles r ON r.id = u.role_id
             LEFT JOIN tn_districts d ON d.id = u.assigned_district_id
             WHERE u.id = ?",
            [$id]
        );
    }

    public function allWithRoles(int $page = 1, int $perPage = 20, string $search = '', string $role = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $where  = []; $params = [];
        if ($search) {
            $where[] = "(u.name LIKE ? OR u.email LIKE ?)";
            $params[] = "%$search%"; $params[] = "%$search%";
        }
        if ($role) {
            $where[] = "r.slug = ?";
            $params[] = $role;
        }
        $whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";
        $data = $this->fetchAll(
            "SELECT u.*, r.name AS role_name, r.slug AS role_slug,
                    d.name AS district_name,
                    COUNT(a.id) AS article_count
             FROM tn_users u
             JOIN tn_roles r ON r.id = u.role_id
             LEFT JOIN tn_districts d ON d.id = u.assigned_district_id
             LEFT JOIN tn_articles a ON a.user_id = u.id
             $whereSQL
             GROUP BY u.id
             ORDER BY r.id ASC, u.id DESC LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );
        $countStmt = $this->db->prepare("SELECT COUNT(DISTINCT u.id) FROM tn_users u JOIN tn_roles r ON r.id = u.role_id $whereSQL");
        $countStmt->execute($params);
        return ['data' => $data, 'total' => (int)$countStmt->fetchColumn(), 'page' => $page, 'per_page' => $perPage];
    }

    public function allReporters(): array
    {
        return $this->fetchAll(
            "SELECT u.*, r.slug AS role_slug FROM tn_users u
             JOIN tn_roles r ON r.id = u.role_id
             WHERE r.slug IN ('reporter','senior_reporter','district_editor','category_editor','editor')
             AND u.is_active=1 ORDER BY u.name"
        );
    }

    public function promote(int $userId, int $newRoleId): void
    {
        $this->query("UPDATE tn_users SET role_id=? WHERE id=?", [$newRoleId, $userId]);
        \App\Core\Auth::refresh();
    }

    public function getRoles(): array
    {
        return $this->fetchAll("SELECT * FROM tn_roles ORDER BY id");
    }

    public function getBadges(): array
    {
        try {
            return $this->fetchAll("SELECT * FROM tn_user_badges ORDER BY id");
        } catch (\Exception $e) { return []; }
    }

    public function userBadges(int $userId): array
    {
        try {
            return $this->fetchAll(
                "SELECT ub.* FROM tn_user_badges ub
                 JOIN tn_user_badge_assignments uba ON uba.badge_id = ub.id
                 WHERE uba.user_id = ?", [$userId]
            );
        } catch (\Exception $e) { return []; }
    }

    public function assignBadge(int $userId, int $badgeId): void
    {
        $this->db->prepare("INSERT IGNORE INTO tn_user_badge_assignments (user_id, badge_id) VALUES (?,?)")
            ->execute([$userId, $badgeId]);
    }

    public function removeBadge(int $userId, int $badgeId): void
    {
        $this->db->prepare("DELETE FROM tn_user_badge_assignments WHERE user_id=? AND badge_id=?")
            ->execute([$userId, $badgeId]);
    }

    // ── EDITOR PERMISSIONS ────────────────────────────────────

    public function getPermissions(int $userId): array
    {
        try {
            return $this->fetchAll(
                "SELECT ep.*, d.name AS district_name, c.name AS category_name
                 FROM tn_editor_permissions ep
                 LEFT JOIN tn_districts d ON d.id = ep.ref_id AND ep.perm_type = 'district'
                 LEFT JOIN tn_categories c ON c.id = ep.ref_id AND ep.perm_type = 'category'
                 WHERE ep.user_id = ?",
                [$userId]
            );
        } catch (\Exception $e) { return []; }
    }

    public function addPermission(int $userId, string $type, int $refId, bool $canPublish = false): void
    {
        try {
            $this->db->prepare(
                "INSERT INTO tn_editor_permissions (user_id, perm_type, ref_id, can_approve, can_publish)
                 VALUES (?,?,?,1,?) ON DUPLICATE KEY UPDATE can_publish=VALUES(can_publish)"
            )->execute([$userId, $type, $refId, $canPublish ? 1 : 0]);
        } catch (\Exception $e) {}
    }

    public function removePermission(int $id): void
    {
        try {
            $this->db->prepare("DELETE FROM tn_editor_permissions WHERE id=?")->execute([$id]);
        } catch (\Exception $e) {}
    }

    // Returns district_ids or category_ids this editor can approve
    public function getEditorScope(int $userId): array
    {
        try {
            $perms = $this->fetchAll(
                "SELECT * FROM tn_editor_permissions WHERE user_id = ?", [$userId]
            );
            $districtIds  = [];
            $categoryIds  = [];
            $canPublish   = false;
            foreach ($perms as $p) {
                if ($p['perm_type'] === 'district') $districtIds[] = (int)$p['ref_id'];
                if ($p['perm_type'] === 'category') $categoryIds[] = (int)$p['ref_id'];
                if ($p['can_publish']) $canPublish = true;
            }
            return compact('districtIds', 'categoryIds', 'canPublish');
        } catch (\Exception $e) {
            return ['districtIds' => [], 'categoryIds' => [], 'canPublish' => false];
        }
    }

    public function block(int $id): void   { $this->query("UPDATE tn_users SET is_blocked = 1 WHERE id = ?", [$id]); }
    public function unblock(int $id): void { $this->query("UPDATE tn_users SET is_blocked = 0 WHERE id = ?", [$id]); }
    public function updateLastLogin(int $id): void { $this->query("UPDATE tn_users SET last_login = NOW() WHERE id = ?", [$id]); }

    public function emailExists(string $email, int $excludeId = 0): bool
    {
        $sql    = "SELECT COUNT(*) FROM tn_users WHERE email = ?";
        $params = [$email];
        if ($excludeId) { $sql .= ' AND id != ?'; $params[] = $excludeId; }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    /* ── PIN + remember-device login ── */

    public function findByIdWithRole(int $id): array|false
    {
        return $this->fetchOne(
            "SELECT u.*, r.slug AS role_slug, r.name AS role_name
             FROM tn_users u JOIN tn_roles r ON r.id = u.role_id
             WHERE u.id = ? AND u.is_active = 1 AND u.is_blocked = 0",
            [$id]
        );
    }

    public function setPin(int $id, string $pin): void
    {
        $this->query(
            "UPDATE tn_users SET pin = ? WHERE id = ?",
            [password_hash($pin, PASSWORD_DEFAULT), $id]
        );
    }

    public function verifyPin(array $user, string $pin): bool
    {
        return !empty($user['pin']) && password_verify($pin, $user['pin']);
    }

    public function setRememberToken(int $id, string $token, string $expiresAt): void
    {
        $this->query(
            "UPDATE tn_users SET remember_token = ?, remember_expires = ? WHERE id = ?",
            [hash('sha256', $token), $expiresAt, $id]
        );
    }

    public function clearRememberToken(int $id): void
    {
        $this->query(
            "UPDATE tn_users SET remember_token = NULL, remember_expires = NULL WHERE id = ?",
            [$id]
        );
    }

    /** Email match ignoring active/blocked status — used only to tell a
     *  wrong password apart from a blocked account at login time. */
    public function isBlockedByEmail(string $email): bool
    {
        return (bool)$this->fetchColumn(
            "SELECT is_blocked FROM tn_users WHERE email = ?", [$email]
        );
    }

    public function isBlockedById(int $id): bool
    {
        return (bool)$this->fetchColumn(
            "SELECT is_blocked FROM tn_users WHERE id = ?", [$id]
        );
    }

    // ── PER-USER PERMISSION OVERRIDES ──────────────────────────

    public function getPermissionOverrides(int $userId): array
    {
        try {
            return $this->fetchAll(
                "SELECT permission_slug, effect FROM tn_user_permission_overrides WHERE user_id = ?",
                [$userId]
            );
        } catch (\Exception $e) { return []; }
    }

    public function savePermissionOverrides(int $userId, array $grants, array $revokes): void
    {
        try {
            $this->db->prepare("DELETE FROM tn_user_permission_overrides WHERE user_id = ?")
                ->execute([$userId]);

            $stmt = $this->db->prepare(
                "INSERT INTO tn_user_permission_overrides (user_id, permission_slug, effect) VALUES (?,?,?)"
            );
            foreach ($grants as $slug) $stmt->execute([$userId, $slug, 'grant']);
            foreach ($revokes as $slug) {
                if (!in_array($slug, $grants, true)) $stmt->execute([$userId, $slug, 'revoke']);
            }
        } catch (\Exception $e) {}
    }

    // ── STAFF PHOTO (team/ID card) ─────────────────────────────

    public function uploadPhoto(int $userId, array $file): bool
    {
        $cfg     = require CONFIG_PATH . '/app.php';
        $allowed = $cfg['upload']['allowed'];
        $maxSize = $cfg['upload']['max_size'];

        if (!in_array($file['type'], $allowed) || $file['size'] > $maxSize) return false;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) return false;

        $dir = dirname(__DIR__, 2) . '/public/uploads/staff/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = 'staff_' . $userId . '_' . uniqid() . '.' . $ext;
        $path     = $dir . $filename;
        if (!move_uploaded_file($file['tmp_name'], $path)) return false;

        $this->resizeSquare($path);

        $old = $this->fetchColumn("SELECT avatar FROM tn_users WHERE id = ?", [$userId]);
        $this->query("UPDATE tn_users SET avatar = ? WHERE id = ?", ['/uploads/staff/' . $filename, $userId]);

        if ($old && str_starts_with($old, '/uploads/staff/')) {
            $oldPath = dirname(__DIR__, 2) . '/public' . $old;
            if (is_file($oldPath)) @unlink($oldPath);
        }
        return true;
    }

    /** Cover-crop to a fixed square — ID-card headshot size. */
    private function resizeSquare(string $path, int $size = 500): void
    {
        if (!function_exists('imagecreatetruecolor')) return;

        $info = @getimagesize($path);
        if (!$info) return;
        [$srcW, $srcH, $type] = $info;

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_GIF  => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            default        => null,
        };
        if (!$src) return;

        $cropSize = min($srcW, $srcH);
        $srcX     = (int)round(($srcW - $cropSize) / 2);
        $srcY     = (int)round(($srcH - $cropSize) / 2);

        $final = imagecreatetruecolor($size, $size);
        imagecopyresampled($final, $src, 0, 0, $srcX, $srcY, $size, $size, $cropSize, $cropSize);

        match ($type) {
            IMAGETYPE_PNG  => imagepng($final, $path, 8),
            IMAGETYPE_GIF  => imagegif($final, $path),
            IMAGETYPE_WEBP => imagewebp($final, $path, 85),
            default        => imagejpeg($final, $path, 85),
        };

        imagedestroy($src);
        imagedestroy($final);
    }

    // ── PUBLIC "OUR TEAM" VERIFICATION PAGE ────────────────────

    public function activeTeamMembers(): array
    {
        $roles = array_merge(['admin'], \App\Core\Auth::EDITORIAL_ROLES);
        $in    = implode(',', array_fill(0, count($roles), '?'));
        return $this->fetchAll(
            "SELECT u.id, u.name, u.avatar, u.designation, u.phone, r.name AS role_name, r.slug AS role_slug
             FROM tn_users u JOIN tn_roles r ON r.id = u.role_id
             WHERE u.is_active = 1 AND u.is_blocked = 0 AND r.slug IN ($in)
             ORDER BY r.sort_order ASC, u.name ASC",
            $roles
        );
    }

    public function findTeamMember(int $id): array|false
    {
        $roles = array_merge(['admin'], \App\Core\Auth::EDITORIAL_ROLES);
        $in    = implode(',', array_fill(0, count($roles), '?'));
        return $this->fetchOne(
            "SELECT u.id, u.name, u.avatar, u.designation, u.id_no, u.phone, u.dob,
                    r.name AS role_name, r.slug AS role_slug
             FROM tn_users u JOIN tn_roles r ON r.id = u.role_id
             WHERE u.id = ? AND u.is_active = 1 AND u.is_blocked = 0 AND r.slug IN ($in)",
            array_merge([$id], $roles)
        );
    }
}
