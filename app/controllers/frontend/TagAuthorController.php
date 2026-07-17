<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, Helper};
use App\Models\{FrontendArticleModel, CategoryModel, SettingModel, TagModel};

class TagController extends Controller
{
    public function show(string $slug): void
    {
        $tagModel = new TagModel();
        $tag      = $tagModel->findBySlug($slug);

        if (!$tag) {
            http_response_code(404);
            require VIEW_PATH . '/errors/404.php';
            return;
        }

        $page        = max(1, (int)($_GET['page'] ?? 1));
        $articleModel = new FrontendArticleModel();
        $result      = $articleModel->byTag($slug, $page, 6);
        $settings    = new SettingModel();
        $siteName    = $settings->getValue('site_name', 'தினத்துளிர்');
        $canonical   = rtrim(BASE_URL . '/public', '/') . '/tag/' . $slug . ($result['page'] > 1 ? '?page=' . $result['page'] : '');

        try { $trending = $articleModel->trending(6); } catch (\Exception $e) { $trending = []; }

        $this->view('frontend.tag.show', [
            'tag'           => $tag,
            'articles'      => $result['data'],
            'total'         => $result['total'],
            'page'          => $result['page'],
            'per_page'      => $result['per_page'],
            'navCategories' => (new CategoryModel())->allWithParent(),
            'siteName'      => $siteName,
            'metaTitle'     => $tag['name'] . ' | ' . $siteName,
            'metaDesc'      => $tag['name'] . ' related Tamil news articles on ' . $siteName,
            'canonical'     => $canonical,
            'ogImage'       => Helper::shareImageUrl(null),
            'robotsContent' => $result['total'] < 3 ? 'noindex, follow' : 'index, follow',
            'trending'      => $trending,
            'breaking'      => [],
        ], 'frontend');
    }
}

class AuthorController extends Controller
{
    public function show(string $slug): void
    {
        $model  = new FrontendArticleModel();
        $author  = $model->authorBySlug($slug);

        if (!$author) {
            http_response_code(404);
            require VIEW_PATH . '/errors/404.php';
            return;
        }

        $page      = max(1, (int)($_GET['page'] ?? 1));
        $result    = $model->byAuthor($author['id'], $page, 12);
        $settings  = new SettingModel();
        $siteName  = $settings->getValue('site_name', 'தினத்துளிர்');
        $canonical = rtrim(BASE_URL . '/public', '/') . '/author/' . $slug . ($result['page'] > 1 ? '?page=' . $result['page'] : '');

        $this->view('frontend.author.show', [
            'author'        => $author,
            'articles'      => $result['data'],
            'total'         => $result['total'],
            'page'          => $result['page'],
            'per_page'      => $result['per_page'],
            'navCategories' => (new CategoryModel())->allWithParent(),
            'siteName'      => $siteName,
            'metaTitle'     => $author['name'] . ' — Articles | ' . $siteName,
            'metaDesc'      => $author['name'] . ' articles and news coverage on ' . $siteName,
            'canonical'     => $canonical,
            'ogImage'       => Helper::shareImageUrl(null),
            'breaking'      => [],
        ], 'frontend');
    }
}
