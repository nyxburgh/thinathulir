<?php
namespace App\Models;

use App\Core\Model;

class CitizenReportModel extends Model
{
    protected string $table = 'tn_citizen_reports';

    public function submit(array $data): int
    {
        try {
            $cols   = implode(', ', array_map(fn($k) => "`{$k}`", array_keys($data)));
            $places = implode(', ', array_fill(0, count($data), '?'));
            $sql = "INSERT INTO `tn_citizen_reports` ({$cols}) VALUES ({$places})";
            $stmt = $this->query($sql, array_values($data));
            
            if (!$stmt->rowCount()) {
                throw new \Exception("Failed to insert citizen report");
            }
            
            return (int)$this->db->lastInsertId();
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    public function pending(): array
    {
        return $this->fetchAll(
            "SELECT r.*, d.name AS district_name
             FROM tn_citizen_reports r
             LEFT JOIN tn_districts d ON d.id = r.district_id
             WHERE r.status = 'pending'
             ORDER BY r.created_at DESC"
        );
    }

    public function find(int $id): array|false
    {
        return $this->fetchOne(
            "SELECT r.*, d.name AS district_name, c.name AS category_name, c.name_tamil AS category_tamil
             FROM tn_citizen_reports r
             LEFT JOIN tn_districts d ON d.id = r.district_id
             LEFT JOIN tn_categories c ON c.id = r.category_id
             WHERE r.id = ?",
            [$id]
        );
    }

    public function approve(int $id, int $reviewerId, int $articleId): void
    {
        $set    = "`status`='approved', `reviewed_by`=?, `article_id`=?";
        $this->query("UPDATE tn_citizen_reports SET {$set} WHERE id=?",
                     [$reviewerId, $articleId, $id]);
    }

    public function reject(int $id, int $reviewerId, string $reason): void
    {
        $this->query(
            "UPDATE tn_citizen_reports SET status='rejected', reviewed_by=?, rejection_reason=? WHERE id=?",
            [$reviewerId, $reason, $id]
        );
    }

    public function paginate(int $page, int $perPage, string $where = '', array $params = [], string $orderBy = 'created_at', string $dir = 'DESC'): array
    {
        $dir    = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';
        $offset = ($page - 1) * $perPage;
        $sql    = "SELECT r.*, d.name AS district_name, c.name AS category_name, c.name_tamil AS category_tamil FROM tn_citizen_reports r LEFT JOIN tn_districts d ON d.id = r.district_id LEFT JOIN tn_categories c ON c.id = r.category_id";
        if ($where) $sql .= " WHERE {$where}";
        $sql .= " ORDER BY r.`{$orderBy}` {$dir} LIMIT {$perPage} OFFSET {$offset}";
        
        $countSql = "SELECT COUNT(*) FROM tn_citizen_reports r";
        if ($where) $countSql .= " WHERE {$where}";
        
        return [
            'data'  => $this->fetchAll($sql, $params),
            'total' => (int)$this->fetchColumn($countSql, $params),
            'page'  => $page,
            'per_page' => $perPage,
        ];
    }

    public function allWithStatus(string $status, int $page, int $perPage): array
    {
        return $this->paginate($page, $perPage, "r.status = ?", [$status]);
    }

    public function approvedCount(): int
    {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM tn_citizen_reports WHERE status='approved'");
    }

    public function countRecentByIp(string $ip, int $minutes): int
    {
        $since = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
        $stmt  = $this->db->prepare(
            "SELECT COUNT(*) FROM tn_citizen_reports WHERE ip_address = ? AND created_at >= ?"
        );
        $stmt->execute([$ip, $since]);
        return (int)$stmt->fetchColumn();
    }

    public function byIp(string $ip, int $limit=20): array
    {
        return $this->fetchAll(
            "SELECT r.*, c.name AS category_name,
                    TIMESTAMPDIFF(DAY, r.created_at, NOW()) AS days_ago,
                    CASE WHEN a.id IS NOT NULL AND DATEDIFF(a.published_at, NOW()) > -30 THEN 'active'
                         WHEN a.id IS NOT NULL THEN 'expired'
                         ELSE r.status END AS display_status,
                    a.slug AS article_slug, a.published_at
             FROM tn_citizen_reports r
             LEFT JOIN tn_categories c ON c.id = r.category_id
             LEFT JOIN tn_articles a ON a.image_credit LIKE CONCAT('%',r.name,'%') AND a.status='published'
             WHERE r.ip_address = ?
             ORDER BY r.created_at DESC LIMIT ?",
            [$ip, $limit]
        );
    }

    public function pendingCount(): int
    {
        return (int)$this->fetchColumn(
            "SELECT COUNT(*) FROM tn_citizen_reports WHERE status='pending'"
        );
    }

    /**
     * Keeps a citizen's report history within a 30-day validity window and a
     * hard cap of 10 items — reports past the window, or beyond the cap
     * (oldest first), are removed.
     */
    public function enforceLimits(string $ip, int $maxItems = 10, int $validDays = 30): void
    {
        $this->query(
            "DELETE FROM tn_citizen_reports WHERE ip_address = ? AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
            [$ip, $validDays]
        );

        $total = (int)$this->fetchColumn(
            "SELECT COUNT(*) FROM tn_citizen_reports WHERE ip_address = ?",
            [$ip]
        );

        if ($total > $maxItems) {
            $excess = $total - $maxItems;
            $this->query(
                "DELETE FROM tn_citizen_reports WHERE ip_address = ? ORDER BY created_at ASC LIMIT ?",
                [$ip, $excess]
            );
        }
    }
}
