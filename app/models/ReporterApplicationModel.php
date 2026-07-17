<?php
namespace App\Models;

use App\Core\Model;

class ReporterApplicationModel extends Model
{
    protected string $table = 'tn_reporter_applications';

    public function submit(array $data): int
    {
        $cols   = implode(', ', array_map(fn($k) => "`{$k}`", array_keys($data)));
        $places = implode(', ', array_fill(0, count($data), '?'));
        $this->query(
            "INSERT INTO `tn_reporter_applications` ({$cols}) VALUES ({$places})",
            array_values($data)
        );
        return (int)$this->db->lastInsertId();
    }

    public function find(int $id): array|false
    {
        return $this->fetchOne(
            "SELECT a.*, d.name AS district_name
             FROM tn_reporter_applications a
             LEFT JOIN tn_districts d ON d.id = a.district_id
             WHERE a.id = ?",
            [$id]
        );
    }

    public function paginate(int $page, int $perPage, string $where = '', array $params = [], string $orderBy = 'created_at', string $dir = 'DESC'): array
    {
        $dir    = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT a.*, d.name AS district_name FROM tn_reporter_applications a LEFT JOIN tn_districts d ON d.id = a.district_id";
        if ($where) $sql .= " WHERE {$where}";
        $sql .= " ORDER BY a.`{$orderBy}` {$dir} LIMIT {$perPage} OFFSET {$offset}";

        $countSql = "SELECT COUNT(*) FROM tn_reporter_applications a";
        if ($where) $countSql .= " WHERE {$where}";

        return [
            'data'     => $this->fetchAll($sql, $params),
            'total'    => (int)$this->fetchColumn($countSql, $params),
            'page'     => $page,
            'per_page' => $perPage,
        ];
    }

    public function pendingCount(): int
    {
        return (int)$this->fetchColumn(
            "SELECT COUNT(*) FROM tn_reporter_applications WHERE status = 'pending'"
        );
    }

    public function countRecentByIp(string $ip, int $minutes): int
    {
        $since = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
        $stmt  = $this->db->prepare(
            "SELECT COUNT(*) FROM tn_reporter_applications WHERE ip_address = ? AND created_at >= ?"
        );
        $stmt->execute([$ip, $since]);
        return (int)$stmt->fetchColumn();
    }

    public function markStatus(int $id, string $status, int $reviewerId): void
    {
        $this->query(
            "UPDATE tn_reporter_applications SET status = ?, reviewed_by = ? WHERE id = ?",
            [$status, $reviewerId, $id]
        );
    }
}
