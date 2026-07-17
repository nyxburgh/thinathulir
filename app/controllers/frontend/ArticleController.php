<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, Session, Helper};
use App\Models\{FrontendArticleModel, CategoryModel, RatingModel, SettingModel};

class ArticleController extends Controller
{
    public function show(string $slug): void
    {
        try {
        $model    = new FrontendArticleModel();
        $article  = $model->bySlug($slug);

        if (!$article) {
            http_response_code(404);
            require VIEW_PATH . '/errors/404.php';
            return;
        }

        // Premium gate — check if reader has access
        $isPremiumLocked = false;
        if (!empty($article['is_premium'])) {
            $readerId = \App\Core\Session::get('reader_id', 0);
            // Also allow logged-in admin/editor/reporter
            $staffLoggedIn = \App\Core\Auth::check();
            if (!$staffLoggedIn && !$readerId) {
                $isPremiumLocked = true;
            } elseif (!$staffLoggedIn && $readerId) {
                $premiumModel = new \App\Models\PremiumModel();
                $isPremiumLocked = !$premiumModel->hasAccess($readerId);
            }
        }

        // Increment view count
        $model->incrementView($article['id']);

        // Related articles
        $related = $model->related($article['id'], $article['category_id'] ?? 0, 4);

        // Ratings
        $ratingModel   = new RatingModel();
        $ratingStats   = $ratingModel->forArticle($article['id']);
        $reviews       = $ratingModel->recentReviews($article['id'], 10);
        $readerId      = Session::get('reader_id', 0);
        $userRatingRow = $readerId ? $ratingModel->readerRating($article['id'], $readerId) : null;
        $userRating    = $userRatingRow['rating'] ?? 0;
        $userReview    = $userRatingRow['review'] ?? '';

        // Nav
        $categories    = (new CategoryModel())->allWithParent();
        $navCategories = $categories;
        $settings      = new SettingModel();
        $siteName      = $settings->getValue('site_name', 'தமிழ் செய்தி');

        // Districts that have published news — for the district switcher dropdown
        $districts = [];
        try {
            $districts = \App\Core\Database::getInstance()->query(
                "SELECT DISTINCT d.id, d.name FROM tn_districts d
                 INNER JOIN tn_articles a ON a.district_id = d.id
                 WHERE a.status='published'
                 ORDER BY d.name"
            )->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {}

        // Trending sidebar
        $trending = $model->trending(5);

        // Ad slots
        $db   = \App\Core\Database::getInstance();
        $rows = $db->query("SELECT * FROM tn_ad_slots WHERE is_active = 1")->fetchAll(\PDO::FETCH_ASSOC);
        $ads  = [];
        foreach ($rows as $row) { $ads[$row['type']] = $row; }

        // SEO
        $siteUrl   = rtrim($settings->getValue('site_url', BASE_URL . '/public'), '/');
        $metaTitle = $article['meta_title'] ?: $article['title'] . ' | Thinathulir';
        $rawDesc   = $article['meta_desc'] ?: strip_tags($article['excerpt'] ?? '');
        if (!$rawDesc) $rawDesc = mb_substr(strip_tags($article['content'] ?? ''), 0, 200);
        if (!$rawDesc) $rawDesc = $article['title'] ?? '';
        $metaDesc  = mb_strimwidth(strip_tags($rawDesc), 0, 155, '…') . ' | Thinathulir';
        $canonical = $siteUrl . '/article/' . $article['slug'];

        // OG image — thumbnail (small, share-friendly) preferred over the
        // full-size original, which can be too heavy for WhatsApp/FB crawlers
        $ogImage = Helper::shareImageUrl($article['thumb_url'] ?? $article['image_url'] ?? null);

        $isPremiumLocked = $isPremiumLocked ?? false;
        $categoryId  = (int)($article['category_id'] ?? 0);
        $noWidgets   = true; // article page: sidebar = vertical ad only
        $csrf = \App\Core\CSRF::token();

        $this->view('frontend.article.show', compact(
            'article', 'related', 'ratingStats', 'reviews',
            'readerId', 'userRating', 'userReview', 'categories', 'navCategories', 'siteName',
            'trending', 'ads', 'metaTitle', 'metaDesc', 'canonical', 'ogImage', 'csrf',
            'categoryId', 'noWidgets', 'districts',
        ), 'frontend');
        } catch (\Throwable $e) {
            error_log('Article 500: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            http_response_code(500);
            require VIEW_PATH . '/errors/500.php';
        }
    }

    public function trackView(string $id): void
    {
        header('Content-Type: application/json');
        $articleId = (int)$id;
        if (!$articleId) { echo '{"ok":false}'; exit; }
        $db = \App\Core\Database::getInstance();
        try {
            $db->prepare(
                "INSERT INTO tn_analytics_daily (article_id, date, views)
                 VALUES (?, CURDATE(), 1)
                 ON DUPLICATE KEY UPDATE views = views + 1"
            )->execute([$articleId]);
            $db->prepare(
                "UPDATE tn_articles SET view_count = view_count + 1 WHERE id = ?"
            )->execute([$articleId]);
        } catch (\Exception $e) {}
        echo '{"ok":true}';
        exit;
    }
}
