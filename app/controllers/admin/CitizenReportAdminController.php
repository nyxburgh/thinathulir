<?php
namespace App\Controllers\Admin;

use App\Core\{Controller, CSRF, Auth, Database};
use App\Models\{CitizenReportModel, ArticleModel, CategoryModel, NotificationModel};
use App\Core\Helper;

class CitizenReportAdminController extends Controller
{
    protected function layout(): string
    {
        $role = Auth::role();
        if ($role === 'admin')        return 'admin';
        if (in_array($role, ['chief_editor','staff_reporter'])) return 'editor_portal';
        return 'portal';
    }

    private function base(): string
    {
        return \App\Core\Auth::role() === 'admin' ? '/admin/citizen-reports' : '/portal/citizen-reports';
    }

    public function middleware(): void { $this->requireCan('manage_articles'); }

    private CitizenReportModel $model;
    private \PDO $db;

    public function __construct()
    {
        $this->model = new CitizenReportModel();
        $this->db    = Database::getInstance();
    }

    public function approved(): void
    {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->model->allWithStatus('approved', $page, 20);
        $this->view('admin.citizen_reports.approved', [
            'pageTitle' => 'Approved Citizen Reports',
            'reports'   => $result['data'],
            'total'     => $result['total'],
            'page'      => $result['page'],
            'per_page'  => $result['per_page'],
            'crBase'    => $this->base(),
        ], $this->layout());
    }

    public function index(): void
    {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->model->paginate($page, 20);
        $this->view('admin.citizen_reports.index', [
            'pageTitle' => 'Citizen Reports',
            'reports'   => $result['data'],
            'total'     => $result['total'],
            'page'      => $result['page'],
            'per_page'  => $result['per_page'],
            'pending'   => $this->model->pendingCount(),
            'crBase'    => $this->base(),
        ], $this->layout());
    }

    public function show(string $id): void
    {
        $report = $this->model->find((int)$id);
        if (!$report) { $this->flash('danger','Not found.'); $this->redirect($this->base()); }
        $this->view('admin.citizen_reports.show', [
            'pageTitle' => 'Review Report — ' . $report['title'],
            'report'    => $report,
            'categories'=> (new CategoryModel())->all(),
        ], $this->layout());
    }

    public function approve(string $id): void
    {
        CSRF::validate();
        $report = $this->model->find((int)$id);
        if (!$report) { $this->flash('danger','Not found.'); $this->redirect($this->base()); }

        $catId   = (int)$this->post('category_id', 1);
        $title   = Helper::sanitize($this->post('title', $report['title']));
        $content = Helper::sanitize($this->post('content', $report['content']));
        $slug    = Helper::slug($title);

        $existing = $this->db->prepare("SELECT id FROM tn_articles WHERE slug=? LIMIT 1");
        $existing->execute([$slug]);
        if ($existing->fetch()) $slug .= '-' . time();

        $imgUrl = !empty($report['image_path']) ? $report['image_path'] : null;
        $reporterName = $report['name'] . ' (Citizen Reporter)';
        $stmt = $this->db->prepare(
            "INSERT INTO tn_articles
             (user_id, category_id, title, slug, content, excerpt, district_id,
              content_type, status, image_url, image_credit, published_at, created_at, updated_at)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW(),NOW())"
        );
        $stmt->execute([
            Auth::id(),
            $catId,
            $title,
            $slug,
            $content,
            mb_substr(strip_tags($content), 0, 200),
            $report['district_id'] ?: null,
            'news',
            'published',
            $imgUrl,
            $reporterName,
        ]);
        $articleId = (int)$this->db->lastInsertId();

        // Copy citizen image to article and register in media library
        if (!empty($report['image_path'])) {
            $this->db->prepare("UPDATE tn_articles SET image_url=? WHERE id=?")->execute([$report['image_path'], $articleId]);
            try {
                // Register in tn_media so media_id JOIN works on frontend
                $ms = $this->db->prepare(
                    "INSERT INTO tn_media (user_id, filepath, thumb_path, original_name, mime_type, file_size, created_at)
                     VALUES (?,?,?,?,?,?,NOW())"
                );
                $ms->execute([
                    Auth::id(),
                    $report['image_path'],
                    $report['image_path'],
                    basename($report['image_path']),
                    'image/jpeg',
                    0
                ]);
                $mediaId = (int)$this->db->lastInsertId();
                if ($mediaId) {
                    $this->db->prepare("UPDATE tn_articles SET media_id=? WHERE id=?")->execute([$mediaId, $articleId]);
                }
            } catch (\Exception $e) {}
        }

        $this->model->approve((int)$id, Auth::id(), $articleId);
        $this->flash('success', 'Citizen report approved and published as article #' . $articleId . '.');
        $this->redirect($this->base());
    }

    public function reject(string $id): void
    {
        CSRF::validate();
        $reason = Helper::sanitize($this->post('reason', ''));
        $this->model->reject((int)$id, Auth::id(), $reason);
        $this->flash('info', 'Report rejected.');
        $this->redirect($this->base());
    }
}
