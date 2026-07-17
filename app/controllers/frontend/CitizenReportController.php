<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, CSRF};
use App\Models\{CitizenReportModel, LocationModel, NotificationModel};
use App\Core\Helper;

class CitizenReportController extends Controller
{
    protected function layout(): string { return 'frontend'; }

    public function history(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $model = new CitizenReportModel();
        $model->enforceLimits($ip);
        $reports = $model->byIp($ip, 10);
        $this->view('frontend.citizen.history', [
            'pageTitle' => 'My Submitted Reports',
            'reports'   => $reports,
        ], $this->layout());
    }

    public function create(): void
    {
        $locations = new LocationModel();
        $this->view('frontend.citizen.create', [
            'districts'  => $locations->allDistricts(),
            'categories' => (new \App\Models\CategoryModel())->allWithParent(),
            'metaTitle'  => 'குடிமக்கள் நிருபர் — Citizen Reporter',
            'noSidebar'  => true,
        ], $this->layout());
    }

    public function store(): void
    {
        CSRF::validate();

        // Rate limit: max 3 per IP per hour
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if ((new CitizenReportModel())->countRecentByIp($ip, 60) >= 3) {
            $this->flash('danger', 'Too many submissions. Please wait before submitting again.');
            $this->redirect('/citizen-reporter');
        }

        $name    = Helper::sanitize($this->post('name', ''));
        $phone   = Helper::sanitize($this->post('phone', ''));
        $title   = Helper::sanitize($this->post('title', ''));
        $content = $this->post('content', '');

        if (!$name || !$phone || !$title || !$content) {
            $this->flash('danger', 'பெயர், தொலைபேசி, தலைப்பு மற்றும் உள்ளடக்கம் அவசியம்.');
            $this->redirect('/citizen-reporter');
        }

        // Image upload (optional)
        $imagePath = null;
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed) && $_FILES['image']['size'] <= 5 * 1024 * 1024) {
                $dir  = dirname(__DIR__, 3) . '/public/uploads/citizen/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $fname = 'cr_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . $fname)) {
                    $imagePath = '/uploads/citizen/' . $fname;
                }
            }
        }

        $model = new CitizenReportModel();
        try {
            $reportId = $model->submit([
                'name'        => $name,
                'phone'       => $phone,
                'email'       => Helper::sanitize($this->post('email', '')),
                'category_id' => (int)$this->post('category_id', 0) ?: null,
                'title'       => $title,
                'content'     => $content,
                'location'    => Helper::sanitize($this->post('location', '')),
                'district_id' => (int)$this->post('district_id', 0) ?: null,
                'image_path'  => $imagePath,
                'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null,
                'status'      => 'pending',
            ]);

            $model->enforceLimits($ip);

            try {
                (new NotificationModel())->notifyChiefEditors(
                    'citizen_report',
                    "New citizen report: \"{$title}\" by {$name} ({$phone})",
                    0, 0
                );
            } catch (\Exception $e) {}

            $this->flash('success', 'உங்கள் செய்தி பெறப்பட்டது. ஆசிரியர் குழு விரைவில் ஆய்வு செய்யும். நன்றி!');
        } catch (\Exception $e) {
            error_log("Citizen Report Error: " . $e->getMessage());
            $this->flash('danger', 'பிழை: ' . $e->getMessage());
        }
        $this->redirect('/citizen-reporter');
    }
}
