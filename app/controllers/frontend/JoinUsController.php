<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, CSRF};
use App\Models\{ReporterApplicationModel, LocationModel, NotificationModel};
use App\Core\Helper;

class JoinUsController extends Controller
{
    protected function layout(): string { return 'frontend'; }

    public function choice(): void
    {
        $this->view('frontend.joinus.choice', [
            'metaTitle' => 'எங்களுடன் இணையுங்கள் — Join Us',
            'noSidebar' => true,
        ], $this->layout());
    }

    public function reporterForm(): void
    {
        $locations = new LocationModel();
        $this->view('frontend.joinus.reporter', [
            'districts' => $locations->allDistricts(),
            'metaTitle' => 'நிருபராக விண்ணப்பிக்க — Apply as Reporter',
            'noSidebar' => true,
        ], $this->layout());
    }

    public function reporterSubmit(): void
    {
        CSRF::validate();

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        if ((new ReporterApplicationModel())->countRecentByIp($ip, 60) >= 3) {
            $this->flash('danger', 'Too many submissions. Please wait before submitting again.');
            $this->redirect('/join-us/reporter');
        }

        $name  = Helper::sanitize($this->post('name', ''));
        $phone = Helper::sanitize($this->post('phone', ''));

        if (!$name || !$phone) {
            $this->flash('danger', 'பெயர் மற்றும் தொலைபேசி எண் அவசியம்.');
            $this->redirect('/join-us/reporter');
        }

        $model = new ReporterApplicationModel();
        try {
            $model->submit([
                'name'        => $name,
                'phone'       => $phone,
                'email'       => Helper::sanitize($this->post('email', '')),
                'district_id' => (int)$this->post('district_id', 0) ?: null,
                'experience'  => Helper::sanitize($this->post('experience', '')),
                'message'     => Helper::sanitize($this->post('message', '')),
                'ip_address'  => $ip,
                'status'      => 'pending',
            ]);

            try {
                (new NotificationModel())->notifyChiefEditors(
                    'reporter_application',
                    "New reporter application from {$name} ({$phone})"
                );
            } catch (\Exception $e) {}

            $this->flash('success', 'உங்கள் விண்ணப்பம் பெறப்பட்டது. ஆசிரியர் குழு விரைவில் உங்களைத் தொடர்பு கொள்ளும். நன்றி!');
        } catch (\Exception $e) {
            error_log('Reporter Application Error: ' . $e->getMessage());
            $this->flash('danger', 'பிழை ஏற்பட்டது. மீண்டும் முயற்சிக்கவும்.');
        }
        $this->redirect('/join-us/reporter');
    }
}
