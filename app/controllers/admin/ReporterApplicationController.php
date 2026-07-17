<?php
namespace App\Controllers\Admin;

use App\Core\{Controller, CSRF, Auth};
use App\Models\ReporterApplicationModel;

class ReporterApplicationController extends Controller
{
    protected function layout(): string
    {
        $role = Auth::role();
        if ($role === 'admin')        return 'admin';
        if (in_array($role, ['chief_editor','staff_reporter'])) return 'editor_portal';
        return 'portal';
    }

    private function base(): string
    {
        return Auth::role() === 'admin' ? '/admin/reporter-applications' : '/portal/reporter-applications';
    }

    public function middleware(): void { $this->requireRole('admin', 'chief_editor'); }

    private ReporterApplicationModel $model;

    public function __construct()
    {
        $this->model = new ReporterApplicationModel();
    }

    public function index(): void
    {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $result = $this->model->paginate($page, 20);
        $this->view('admin.reporter_applications.index', [
            'pageTitle'    => 'Reporter Applications',
            'applications' => $result['data'],
            'total'        => $result['total'],
            'page'         => $result['page'],
            'per_page'     => $result['per_page'],
            'pending'      => $this->model->pendingCount(),
            'raBase'       => $this->base(),
        ], $this->layout());
    }

    public function show(string $id): void
    {
        $app = $this->model->find((int)$id);
        if (!$app) { $this->flash('danger', 'Not found.'); $this->redirect($this->base()); }
        $this->view('admin.reporter_applications.show', [
            'pageTitle' => 'Application — ' . $app['name'],
            'app'       => $app,
            'raBase'    => $this->base(),
        ], $this->layout());
    }

    public function markContacted(string $id): void
    {
        CSRF::validate();
        $this->model->markStatus((int)$id, 'contacted', Auth::id());
        $this->flash('success', 'Marked as contacted.');
        $this->redirect($this->base());
    }

    public function reject(string $id): void
    {
        CSRF::validate();
        $this->model->markStatus((int)$id, 'rejected', Auth::id());
        $this->flash('info', 'Application rejected.');
        $this->redirect($this->base());
    }
}
