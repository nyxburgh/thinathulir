<?php
namespace App\Controllers\Panel;

use App\Core\Controller;
use App\Models\{ArticleModel, BusinessAdModel};

class DashboardController extends Controller
{
    public function middleware(): void { $this->requireRole('sub_admin'); }

    public function index(): void
    {
        $pendingNews = (new ArticleModel())->count('status = ?', ['review']);
        $pendingAds  = (new BusinessAdModel())->count('status = ?', ['pending']);

        $this->view('panel.dashboard', [
            'pageTitle'   => 'Sub Admin Panel',
            'pendingNews' => $pendingNews,
            'pendingAds'  => $pendingAds,
        ], 'subadmin');
    }
}
