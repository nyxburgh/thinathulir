<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, Database, Helper};

class PhotoNewsController extends Controller
{
    public function index(): void
    {
        $db   = Database::getInstance();
        $per  = 24;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $off  = ($page - 1) * $per;

        $total = (int)$db->query(
            "SELECT COUNT(*) FROM tn_photo_news
             WHERE status='published' AND approval_status='approved'
               AND image_path IS NOT NULL AND image_path != ''"
        )->fetchColumn();

        $stmt = $db->prepare(
            "SELECT pn.id, pn.title, pn.slug, pn.image_path, pn.created_at,
                    a.slug AS article_slug
             FROM tn_photo_news pn
             LEFT JOIN tn_articles a ON a.id = pn.article_id AND a.status = 'published'
             WHERE pn.status='published' AND pn.approval_status='approved'
               AND pn.image_path IS NOT NULL AND pn.image_path != ''
             ORDER BY pn.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$per, $off]);
        $cards = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $siteUrl   = rtrim(BASE_URL . '/public', '/');
        $metaTitle = 'Photo News | ' . (defined('BASE_URL') ? 'Thinathulir' : 'Tamil News');
        $ogImage   = Helper::shareImageUrl(null);
        $canonical = $siteUrl . '/photo-news' . ($page > 1 ? '?page=' . $page : '');

        // Deep link to a single photo (?photo=ID) — used when sharing one
        // photo individually, so the preview shows that photo, not the
        // generic gallery card. Looked up directly so it works regardless
        // of which page the photo falls on.
        $photoId = (int)($_GET['photo'] ?? 0);
        if ($photoId) {
            $photoStmt = $db->prepare(
                "SELECT id, title, image_path FROM tn_photo_news
                 WHERE id = ? AND status='published' AND approval_status='approved'
                   AND image_path IS NOT NULL AND image_path != ''"
            );
            $photoStmt->execute([$photoId]);
            $photo = $photoStmt->fetch(\PDO::FETCH_ASSOC);
            if ($photo) {
                $metaTitle = $photo['title'] . ' | Photo News — Thinathulir';
                $ogImage   = Helper::shareImageUrl($photo['image_path']);
                $canonical = $siteUrl . '/photo-news?photo=' . $photoId;
            }
        }

        $this->view('frontend.photo_news.index', [
            'pageTitle' => 'பட செய்திகள் | Photo News — Thinathulir',
            'metaTitle' => $metaTitle,
            'metaDesc'  => 'Visual news stories from Thinathulir',
            'canonical' => $canonical,
            'ogImage'   => $ogImage,
            'cards'     => $cards,
            'openPhoto' => $photoId,
            'total'     => $total,
            'page'      => $page,
            'per'       => $per,
            'noSidebar' => true,
        ], 'frontend');
    }
}
