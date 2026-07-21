<?php
namespace App\Controllers\Panel;

use App\Core\{Controller, Auth, CSRF, ApprovalService};
use App\Models\{ArticleModel, BusinessAdModel};

class ApprovalController extends Controller
{
    public function middleware(): void { $this->requireRole('sub_admin'); }

    // ── News ─────────────────────────────────────────────────

    public function news(): void
    {
        $articles = new ArticleModel();
        $result   = $articles->listPaginated(['status' => 'review'], max(1, (int)$this->get('page', 1)), 20);

        $this->view('panel.approvals.news', [
            'pageTitle' => 'Approve News',
            'articles'  => $result['data'],
            'total'     => $result['total'],
            'page'      => $result['page'],
            'per_page'  => $result['per_page'],
        ], 'subadmin');
    }

    public function approveNews(string $id): void
    {
        CSRF::validate();
        (new ApprovalService())->editorApprove((int)$id, Auth::id());
        $this->flash('success', 'Article approved and published.');
        $this->redirect('/panel/approvals/news');
    }

    public function rejectNews(string $id): void
    {
        CSRF::validate();
        $reason = trim($this->post('reason', ''));
        (new ApprovalService())->reject((int)$id, Auth::id(), $reason);
        $this->flash('success', 'Article rejected. Reporter notified.');
        $this->redirect('/panel/approvals/news');
    }

    // ── Ads ──────────────────────────────────────────────────

    public function ads(): void
    {
        $ads    = new BusinessAdModel();
        $result = $ads->allWithPackage(max(1, (int)$this->get('page', 1)), 20, '', 'pending');

        $this->view('panel.approvals.ads', [
            'pageTitle' => 'Approve Ads',
            'ads'       => $result['data'],
            'total'     => $result['total'],
            'page'      => $result['page'],
            'per_page'  => $result['per_page'],
        ], 'subadmin');
    }

    public function approveAd(string $id): void
    {
        CSRF::validate();
        (new BusinessAdModel())->approve((int)$id, Auth::id());
        $this->flash('success', 'Ad approved.');
        $this->redirect('/panel/approvals/ads');
    }

    public function rejectAd(string $id): void
    {
        CSRF::validate();
        $reason = trim($this->post('reason', ''));
        (new BusinessAdModel())->reject((int)$id, Auth::id(), $reason);
        $this->flash('success', 'Ad rejected.');
        $this->redirect('/panel/approvals/ads');
    }
}
