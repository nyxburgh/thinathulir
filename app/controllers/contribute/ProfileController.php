<?php
namespace App\Controllers\Contribute;

use App\Core\{Controller, Session, CSRF, Helper};
use App\Models\ContributorModel;

class ProfileController extends Controller
{
    public function middleware(): void
    {
        if (!Session::get('contributor_id')) Helper::redirect('/contribute/login');
    }

    public function index(): void
    {
        $id   = Session::get('contributor_id');
        $model = new ContributorModel();
        $this->view('contribute.profile', [
            'pageTitle'   => 'My Profile',
            'contributor' => $model->find($id),
        ], 'contributor');
    }

    public function update(): void
    {
        CSRF::validate();
        $id    = Session::get('contributor_id');
        $model = new ContributorModel();

        $data = ['bio' => Helper::sanitize($this->post('bio',''))];

        // Avatar upload
        $f = $_FILES['avatar'] ?? null;
        if ($f && $f['error'] === UPLOAD_ERR_OK && $f['size'] <= 3*1024*1024) {
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $dir = dirname(__DIR__,3) . '/public/uploads/avatars/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $name = 'contrib_' . $id . '_' . time() . '.' . $ext;
                if (move_uploaded_file($f['tmp_name'], $dir . $name)) {
                    $data['avatar'] = '/uploads/avatars/' . $name;
                }
            }
        }

        $model->update($id, $data);

        // Refresh session
        $contrib = $model->find($id);
        Session::set('contributor', $contrib);

        $this->flash('success', 'Profile updated.');
        $this->redirect('/contribute/profile');
    }
}
