<?php
namespace App\Core;

class Auth
{
    const EDITORIAL_ROLES = ['chief_editor','editor','district_editor','category_editor','reporter','senior_reporter','staff_reporter'];
    const EDITOR_ROLES    = ['chief_editor','editor','district_editor','category_editor'];

    /**
     * Catalog of permission slugs actually checked via requireCan()/Auth::can()
     * across the app (the live $matrix keys below), grouped for the admin
     * per-user override UI. Keep in sync with $matrix.
     */
    const PERMISSION_CATALOG = [
        'Articles' => [
            'manage_articles'   => 'Manage all articles',
            'edit_all_articles' => 'Edit any article',
            'create_articles'   => 'Create articles',
            'approve_articles'  => 'Approve articles',
            'publish_articles'  => 'Publish articles',
            'escalate_articles' => 'Escalate articles to chief editor',
            'approve_escalated' => 'Approve escalated articles',
            'view_own_articles' => 'View own articles',
            'set_auto_approve'  => 'Grant auto-approve to others',
            'assign_reporters'  => 'Assign reporters to editors',
        ],
        'Ads' => [
            'manage_ads'         => 'Manage ad slots/config',
            'create_ad'          => 'Create ads',
            'manage_own_ads'     => 'Manage own ads',
            'approve_ad'         => 'Approve/reject ads',
            'confirm_ad_payment' => 'Confirm ad payments',
            'manage_packages'    => 'Manage ad packages',
            'manage_rates'       => 'Manage live rates',
        ],
        'Content' => [
            'manage_categories'   => 'Manage categories',
            'manage_tags'         => 'Manage tags',
            'manage_locations'    => 'Manage districts/locations',
            'manage_media'        => 'Manage media library',
            'manage_widgets'      => 'Manage sidebar widgets',
            'manage_live_blog'    => 'Manage live blog',
            'manage_premium'      => 'Manage premium content',
            'manage_special_cats' => 'Manage special categories',
            'manage_polls'        => 'Manage polls',
            'manage_rss'          => 'Manage RSS import',
            'manage_contributors' => 'Manage contributors',
        ],
        'System' => [
            'manage_users'    => 'Create/edit users',
            'manage_settings' => 'Manage site settings',
            'manage_youtube'  => 'Manage YouTube import',
            'send_push'       => 'Send push notifications',
            'view_analytics'  => 'View analytics',
            'promote_user'    => 'Promote user roles',
        ],
    ];

    public static function check(): bool   { return Session::has('user_id'); }
    public static function user(): ?array  { return self::check() ? Session::get('user') : null; }
    public static function id(): ?int      { return Session::get('user_id'); }
    public static function role(): ?string { return self::user()['role_slug'] ?? null; }

    public static function isAdmin(): bool       { return self::role() === 'admin'; }
    public static function isChiefEditor(): bool { return in_array(self::role(), ['admin','chief_editor']); }
    public static function isAnyEditor(): bool   { return in_array(self::role(), self::EDITOR_ROLES); }
    public static function isReporter(): bool    { return self::check(); }

    /**
     * Permission check — uses DB tn_role_permissions if available,
     * falls back to inline matrix for compatibility.
     */
    public static function can(string $permission): bool
    {
        $role = self::role();
        if (!$role) return false;

        // Per-user overrides win over everything below (cached per request)
        static $overrides = null;
        if ($overrides === null) {
            $overrides = [];
            try {
                $uid = self::id();
                if ($uid) {
                    $stmt = Database::getInstance()->prepare(
                        "SELECT permission_slug, effect FROM tn_user_permission_overrides WHERE user_id = ?"
                    );
                    $stmt->execute([$uid]);
                    foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                        $overrides[$row['permission_slug']] = $row['effect'];
                    }
                }
            } catch (\Exception $e) {}
        }
        if (isset($overrides[$permission])) return $overrides[$permission] === 'grant';

        // Try DB-backed permissions (cached per request)
        static $dbPerms = null;
        if ($dbPerms === null) {
            try {
                $db  = Database::getInstance();
                $uid = self::id();
                if ($uid) {
                    $stmt = $db->prepare(
                        "SELECT p.slug FROM tn_permissions p
                         JOIN tn_role_permissions rp ON rp.permission_id = p.id
                         JOIN tn_users u ON u.role_id = rp.role_id
                         WHERE u.id = ?"
                    );
                    $stmt->execute([$uid]);
                    $rows    = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                    $dbPerms = array_flip($rows);
                }
            } catch (\Exception $e) {
                $dbPerms = [];
            }
        }

        if (!empty($dbPerms) && isset($dbPerms[$permission])) return true;

        // Fallback inline matrix (covers all old calls)
        $matrix = [
            'manage_users'          => ['admin'],
            'manage_settings'       => ['admin'],
            'manage_youtube'        => ['admin'],
            'manage_ads'            => ['admin','chief_editor','staff_reporter','ads_manager','editor'],
            'manage_packages'       => ['admin','chief_editor'],
            'manage_widgets'        => ['admin','chief_editor'],
            'manage_payments'       => ['admin','chief_editor'],
            'manage_rates'          => ['admin','chief_editor'],
            'manage_categories'     => ['admin','chief_editor'],
            'manage_tags'           => ['admin','chief_editor'],
            'manage_locations'      => ['admin','chief_editor'],
            'manage_contributors'   => ['admin','chief_editor'],
            'manage_articles'       => ['admin','chief_editor','editor','district_editor','category_editor','staff_reporter'],
            'manage_live_blog'      => ['admin','chief_editor','editor'],
            'manage_premium'        => ['admin','chief_editor'],
            'manage_special_cats'   => ['admin','chief_editor'],
            'manage_polls'          => ['admin','chief_editor','editor'],
            'manage_rss'            => ['admin','chief_editor','staff_reporter'],
            'send_push'             => ['admin','chief_editor'],
            'view_analytics'        => ['admin','chief_editor','editor','district_editor'],
            'manage_media'          => ['admin','chief_editor','editor','district_editor','category_editor','reporter','senior_reporter','ads_manager','staff_reporter'],
            'edit_all_articles'     => ['admin','chief_editor','editor','district_editor','category_editor','staff_reporter'],
            'approve_articles'      => ['admin','chief_editor','editor','district_editor','category_editor','staff_reporter'],
            'publish_articles'      => ['admin','chief_editor','editor','district_editor','category_editor','staff_reporter'],
            'create_articles'       => ['admin','chief_editor','editor','district_editor','category_editor','reporter','senior_reporter','staff_reporter'],
            'view_own_articles'     => ['admin','chief_editor','editor','district_editor','category_editor','reporter','senior_reporter','staff_reporter'],
            'assign_reporters'      => ['admin','chief_editor'],
            'set_auto_approve'      => ['admin','chief_editor'],
            'approve_escalated'     => ['admin','chief_editor'],
            'escalate_articles'     => ['admin','editor','district_editor','category_editor'],
            'create_ad'             => ['admin','chief_editor','editor','district_editor','category_editor','reporter','senior_reporter','ads_manager','staff_reporter'],
            'manage_own_ads'        => ['admin','chief_editor','editor','district_editor','category_editor','reporter','senior_reporter','ads_manager','ad_owner','staff_reporter'],
            'approve_ad'            => ['admin','chief_editor'],
            'confirm_ad_payment'    => ['admin','chief_editor'],
            'view_own_analytics'    => ['ad_owner'],
            'promote_user'          => ['admin'],
        ];

        return in_array($role, $matrix[$permission] ?? []);
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        Session::set('user_id', $user['id']);
        Session::set('user',    $user);
    }

    public static function logout(): void
    {
        Session::delete('user_id');
        Session::delete('user');
        Session::destroy();
    }

    /** Reload user session from DB (after role change etc.) */
    public static function refresh(): void
    {
        $id = self::id();
        if (!$id) return;
        try {
            $db   = Database::getInstance();
            $stmt = $db->prepare(
                "SELECT u.*, r.slug AS role_slug, r.name AS role_name
                 FROM tn_users u JOIN tn_roles r ON r.id = u.role_id
                 WHERE u.id = ? LIMIT 1"
            );
            $stmt->execute([$id]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($user) Session::set('user', $user);
        } catch (\Exception $e) {}
    }
}
