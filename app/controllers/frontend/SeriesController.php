<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, Helper};
use App\Models\{SeriesModel, CategoryModel, SettingModel};

class SeriesController extends Controller
{
    public function show(string $slug): void
    {
        $model  = new SeriesModel();
        $series = $model->findBySlugPublic($slug);

        if (!$series) {
            http_response_code(404);
            require VIEW_PATH . '/errors/404.php';
            return;
        }

        $parts = $model->partsPublic((int)$series['id']);

        $settings  = new SettingModel();
        $siteUrl   = rtrim($settings->getValue('site_url', BASE_URL . '/public'), '/');
        $metaTitle = $series['title'];
        $metaDesc  = mb_strimwidth(strip_tags($series['description'] ?? ''), 0, 155, '…') ?: $series['title'];
        $canonical = $siteUrl . '/series/' . $series['slug'];

        $this->view('frontend.series.show', [
            'series'    => $series,
            'parts'     => $parts,
            'pageTitle' => $series['title'],
            'metaTitle' => $metaTitle,
            'metaDesc'  => $metaDesc,
            'canonical' => $canonical,
            'noSidebar' => true,
        ], 'frontend');
    }
}
