<?php
namespace App\Controllers\Frontend;

use App\Core\{Controller, Helper};
use App\Models\{CategoryModel, SettingModel, UserModel};

/**
 * Public staff verification page — a physical ID card carries a QR code
 * pointing to /our-team/{id} so anyone can confirm a reporter/editor is
 * currently active staff. Blocked/inactive users vanish from both views.
 */
class TeamController extends Controller
{
    private UserModel $users;
    public function __construct() { $this->users = new UserModel(); }

    private function baseContext(string $metaTitle): array
    {
        $settings = new SettingModel();
        $siteName = $settings->getValue('site_name', 'தினத்துளிர்');
        return [
            'siteName'      => $siteName,
            'navCategories' => (new CategoryModel())->allWithParent(),
            'metaTitle'     => $metaTitle . ' | ' . $siteName,
            'metaDesc'      => $metaTitle . ' | ' . $siteName,
            'ogImage'       => Helper::shareImageUrl(null),
            'robotsContent' => 'index, follow',
            'categoryId'    => 0,
            'breaking'      => [],
        ];
    }

    public function index(): void
    {
        $this->view('frontend.team.index', array_merge($this->baseContext('எங்கள் குழு'), [
            'members'   => $this->users->activeTeamMembers(),
            'canonical' => rtrim(BASE_URL . '/public', '/') . '/our-team',
        ]), 'frontend');
    }

    public function show(string $id): void
    {
        $member = $this->users->findTeamMember((int)$id);
        $this->view('frontend.team.show', array_merge($this->baseContext('ஊழியர் சரிபார்ப்பு'), [
            'member'    => $member ?: null,
            'canonical' => rtrim(BASE_URL . '/public', '/') . '/our-team/' . (int)$id,
        ]), 'frontend');
    }
}
