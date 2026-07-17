<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, Helper};
use App\Models\{FrontendArticleModel, CategoryModel, SettingModel};

class SearchController extends Controller
{
    public function index(): void
    {
        $q       = trim($_GET['q'] ?? '');
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $result  = ['data' => [], 'total' => 0, 'page' => 1, 'per_page' => 12];

        if ($q !== '') {
            $model  = new FrontendArticleModel();
            $result = $model->search($q, $page, 12);
        }

        $settings      = new SettingModel();
        $siteName      = $settings->getValue('site_name', 'தினத்துளிர்');
        $navCategories = (new CategoryModel())->allWithParent();

        try {
            $trending = (new FrontendArticleModel())->trending(6);
        } catch (\Exception $e) {
            $trending = [];
        }

        $this->view('frontend.search.index', [
            'q'             => $q,
            'articles'      => $result['data'],
            'total'         => $result['total'],
            'page'          => $result['page'],
            'per_page'      => $result['per_page'],
            'navCategories' => $navCategories,
            'siteName'      => $siteName,
            'metaTitle'     => $q !== '' ? "'{$q}' தேடல் முடிவுகள்" : 'தேடல்',
            'metaDesc'      => $q !== '' ? "Search results for {$q} on {$siteName}" : "Search Tamil news on {$siteName}",
            'canonical'     => rtrim(BASE_URL . '/public', '/') . '/search' . ($q !== '' ? '?q=' . urlencode($q) : ''),
            'ogImage'       => Helper::shareImageUrl(null),
            'trending'      => $trending,
            'robotsContent' => 'noindex, follow',
            'categoryId'    => 0,
        ], 'frontend');
    }
}
