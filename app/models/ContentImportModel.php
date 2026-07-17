<?php
namespace App\Models;

use App\Core\Model;

class ContentImportModel extends Model
{
    protected string $table = 'tn_content_imports';

    public function byUser(int $userId): array
    {
        return $this->fetchAll(
            "SELECT * FROM tn_content_imports WHERE user_id = ? ORDER BY created_at DESC",
            [$userId]
        );
    }

    public function store(array $data): int
    {
        return $this->insert($data);
    }

    public function markConverted(int $id, int $articleId): bool
    {
        return $this->update($id, [
            'status'               => 'converted',
            'converted_article_id' => $articleId,
        ]);
    }

    public function markDiscarded(int $id): bool
    {
        return $this->update($id, ['status' => 'discarded']);
    }
}
