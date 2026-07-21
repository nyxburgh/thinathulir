<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\BusinessAdModel;

class AdSlotController extends Controller
{
    public function middleware(): void
    {
        // /api/ads/* is public — no auth required
        if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/ads/')) return;
        $this->requireCan('manage_ads');
    }

    /** GET /api/ads/track-view/{id} — fires when an ad image is shown */
    public function trackView(string $id): void
    {
        header('Content-Type: application/json');
        try { (new BusinessAdModel())->trackImpression((int)$id); } catch (\Exception $e) {}
        echo json_encode(['ok' => true]);
        exit;
    }

    /** GET /api/ads/track-click/{id} — fires when an ad image is clicked */
    public function trackClick(string $id): void
    {
        header('Content-Type: application/json');
        try { (new BusinessAdModel())->trackClick((int)$id); } catch (\Exception $e) {}
        echo json_encode(['ok' => true]);
        exit;
    }

    /** GET /api/ads/{type}?category_id=X — return active ads for rotation */
    public function serve(string $type = 'square'): void
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache');
        header('Access-Control-Allow-Origin: *');
        try {
            $ads        = new BusinessAdModel();
            $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
            $data       = $ads->activeForRotation($type, $categoryId);
            echo json_encode(['success' => true, 'ads' => $data]);
        } catch (\Exception $e) {
            error_log('Ad serve error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'ads' => [], 'error' => $e->getMessage()]);
        }
        exit;
    }
}
