<?php
namespace App\Models;

use App\Core\Model;

class SeriesModel extends Model
{
    protected string $table = 'tn_series';

    public function byContributor(int $contributorId): array
    {
        return $this->fetchAll(
            "SELECT s.*, c.name AS category_name,
                    COUNT(a.id) AS part_count,
                    SUM(CASE WHEN a.status = 'published' THEN 1 ELSE 0 END) AS published_count
             FROM tn_series s
             LEFT JOIN tn_categories c ON c.id = s.category_id
             LEFT JOIN tn_articles a ON a.series_id = s.id
             WHERE s.contributor_id = ?
             GROUP BY s.id
             ORDER BY s.updated_at DESC",
            [$contributorId]
        );
    }

    public function store(array $data): int
    {
        return $this->insert($data);
    }

    public function updateSeries(int $id, array $data): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($id, $data);
    }

    public function nextPartNumber(int $seriesId): int
    {
        $max = $this->fetchColumn(
            "SELECT MAX(series_part) FROM tn_articles WHERE series_id = ?",
            [$seriesId]
        );
        return $max ? ((int)$max + 1) : 1;
    }

    public function deleteIfEmpty(int $id): bool
    {
        $count = (int)$this->fetchColumn(
            "SELECT COUNT(*) FROM tn_articles WHERE series_id = ?",
            [$id]
        );
        if ($count > 0) return false;
        $this->delete($id);
        return true;
    }

    public function findBySlugPublic(string $slug): array|false
    {
        return $this->fetchOne(
            "SELECT s.*, c.name AS category_name, c.slug AS category_slug, ct.name AS contributor_name
             FROM tn_series s
             LEFT JOIN tn_categories c ON c.id = s.category_id
             LEFT JOIN tn_contributors ct ON ct.id = s.contributor_id
             WHERE s.slug = ?",
            [$slug]
        );
    }

    public function partsPublic(int $seriesId): array
    {
        return $this->fetchAll(
            "SELECT a.*, c.name AS category_name,
                    COALESCE(NULLIF(m.filepath,''), NULLIF(a.image_url,'')) AS image_url,
                    COALESCE(NULLIF(m.thumb_path,''), NULLIF(m.filepath,''), NULLIF(a.image_url,'')) AS thumb_url
             FROM tn_articles a
             LEFT JOIN tn_categories c ON c.id = a.category_id
             LEFT JOIN tn_media m      ON m.id = a.media_id
             WHERE a.series_id = ? AND a.status = 'published'
             ORDER BY a.series_part ASC",
            [$seriesId]
        );
    }
}
