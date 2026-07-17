<?php
namespace App\Controllers\Admin;

use App\Core\{Controller, Auth, CSRF};
use App\Models\CompanyAdModel;

class CompanyAdController extends Controller
{
    public function middleware(): void
    {
        $this->requireRole('admin', 'chief_editor');
    }

    /** GET /admin/company-ads */
    public function index(): void
    {
        $model = new CompanyAdModel();
        $ads   = $model->listAll();
        $bySlot = ['square' => [], 'horizontal' => [], 'vertical' => []];
        foreach ($ads as $ad) {
            $bySlot[$ad['slot_type']][] = $ad;
        }
        $this->view('admin.company_ads.index', [
            'pageTitle' => 'Company Ads',
            'bySlot'    => $bySlot,
        ], Auth::role() === 'admin' ? 'admin' : 'editor_portal');
    }

    /** POST /admin/company-ads/upload */
    public function upload(): void
    {
        CSRF::validate();
        $type = $this->post('slot_type', 'square');
        if (!in_array($type, ['square', 'horizontal', 'vertical'], true)) {
            $this->flash('danger', 'Invalid slot type.');
            $this->redirect('/admin/company-ads');
        }
        if (empty($_FILES['banner']['tmp_name'])) {
            $this->flash('danger', 'No file selected.');
            $this->redirect('/admin/company-ads');
        }

        $ok = (new CompanyAdModel())->upload($_FILES['banner'], $type, $this->post('alt_text', ''));
        $this->flash($ok ? 'success' : 'danger', $ok ? 'Banner uploaded.' : 'Upload failed — check file type.');
        $this->redirect('/admin/company-ads');
    }

    /** POST /admin/company-ads/toggle/{id} */
    public function toggle(string $id): void
    {
        CSRF::validate();
        (new CompanyAdModel())->toggleActive((int)$id);
        $this->redirect('/admin/company-ads');
    }

    /** POST /admin/company-ads/delete/{id} */
    public function delete(string $id): void
    {
        CSRF::validate();
        (new CompanyAdModel())->delete((int)$id);
        $this->flash('success', 'Banner deleted.');
        $this->redirect('/admin/company-ads');
    }
}
