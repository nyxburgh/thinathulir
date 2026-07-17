<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, Database};
use App\Models\{BusinessAdModel, FrontendArticleModel};
use App\Core\Helper;

class AdPublicController extends Controller
{
    public function show(string $id): void
    {
        $model = new BusinessAdModel();
        $ad    = $model->findWithDetails((int)$id);

        if (!$ad || !in_array($ad['status'], ['active','approved'])) {
            http_response_code(404);
            require VIEW_PATH . '/errors/404.php';
            return;
        }

        // Sponsored articles for this ad
        $db       = Database::getInstance();
        $articles = [];
        try {
            $stmt = $db->prepare(
                "SELECT a.id, a.title, a.slug, a.published_at, a.excerpt,
                        COALESCE(m.thumb_path, m.filepath) AS thumb_url
                 FROM tn_sponsored_news sn
                 JOIN tn_ad_subscriptions sub ON sub.id = sn.subscription_id
                 JOIN tn_articles a ON a.id = sn.article_id AND a.status = 'published'
                 LEFT JOIN tn_media m ON m.id = a.media_id
                 WHERE sub.ad_id = ?
                 ORDER BY a.published_at DESC
                 LIMIT 10"
            );
            $stmt->execute([(int)$id]);
            $articles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // tn_sponsored_news.ad_id column missing on live DB — show ad page without articles
        }

        $siteUrl  = rtrim(BASE_URL, '/') . '/public';
        $shareUrl = $siteUrl . '/ad/' . $id;
        // Load all images with display_type
        try {
            $imgStmt = $db->prepare("SELECT filepath AS src, alt_text AS alt, link_url AS link, COALESCE(display_type,'square') AS slot_type FROM tn_ad_images WHERE ad_id=? AND is_active=1 ORDER BY sort_order ASC");
            $imgStmt->execute([(int)$id]);
            $ad['images'] = $imgStmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // display_type column missing — fallback without it
            $imgStmt = $db->prepare("SELECT filepath AS src, alt_text AS alt, link_url AS link, 'square' AS slot_type FROM tn_ad_images WHERE ad_id=? AND is_active=1 ORDER BY sort_order ASC");
            $imgStmt->execute([(int)$id]);
            $ad['images'] = $imgStmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        // OG image: first ad image (full URL) or fallback to site logo
        $ogImage  = '';
        if (!empty($ad['images'])) {
            foreach ($ad['images'] as $img) {
                if (!empty($img['src'])) { $ogImage = $img['src']; break; }
            }
        }
        $ogImage = Helper::shareImageUrl($ogImage ?: null);

        // Recent news — keeps the ad page from looking like a bare, empty layout
        $recentArticles = (new FrontendArticleModel())->latest(3);

        $this->view('frontend.ad.show', [
            'pageTitle'  => $ad['business_name'] . ' | தினத்துளிர்',
            'metaTitle'  => $ad['business_name'] . (!empty($ad['district_name']) ? ' — ' . $ad['district_name'] : '') . ' | தினத்துளிர்',
            'metaDesc'   => strip_tags($ad['small_desc'] ?? $ad['notes'] ?? '') ?: ($ad['business_name'] . (!empty($ad['district_name']) ? ', '.$ad['district_name'] : '') . ' | தினத்துளிர்'),
            'canonical'  => $shareUrl,
            'ogImage'    => $ogImage,
            'ogUrl'      => $shareUrl,
            'ad'             => $ad,
            'articles'       => $articles,
            'recentArticles' => $recentArticles,
            'shareUrl'   => $shareUrl,
            'noSidebar'  => true,
            'noAds'      => true,
            'noBreaking' => true,
                    ], 'frontend');
    }
}
