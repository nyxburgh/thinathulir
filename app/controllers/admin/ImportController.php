<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Helper;
use App\Core\CSRF;
use App\Models\ContentImportModel;

class ImportController extends Controller
{
    private ContentImportModel $imports;

    protected function layout(): string
    {
        $role = Auth::role();
        if ($role === 'admin')        return 'admin';
        if (in_array($role, ['chief_editor','staff_reporter'])) return 'editor_portal';
        return 'portal';
    }

    public function middleware(): void
    {
        $this->requireAuth();
    }

    public function __construct()
    {
        $this->imports = new ContentImportModel();
    }

    public function index(): void
    {
        $this->view('admin.import.index', [
            'pageTitle' => 'Import from URL',
            'imports'   => $this->imports->byUser(Auth::id()),
        ], $this->layout());
    }

    public function fetch(): void
    {
        CSRF::validate();
        $url = trim($this->post('source_url', ''));

        if (!$url) {
            $this->flash('danger', 'Please enter a URL.');
            $this->redirect('/portal/import');
        }

        $fetched = Helper::fetchUrlContent($url);
        if (!$fetched) {
            $reason = Helper::$lastFetchError ?: 'Check the link and try again.';
            $this->flash('danger', 'Could not fetch content from that URL. ' . $reason);
            $this->redirect('/portal/import');
        }

        $this->imports->store([
            'user_id'    => Auth::id(),
            'source_url' => $url,
            'title'      => $fetched['title'],
            'content'    => $fetched['content'],
            'status'     => 'pending',
        ]);

        $this->flash('success', 'Content fetched. Review it below and use it to start an article.');
        $this->redirect('/portal/import');
    }

    public function discard(string $id): void
    {
        CSRF::validate();
        $item = $this->ownerCheck((int)$id);
        $this->imports->markDiscarded((int)$item['id']);
        $this->flash('success', 'Import discarded.');
        $this->redirect('/portal/import');
    }

    // ── Private ──────────────────────────────────────

    private function ownerCheck(int $id): array
    {
        $item = $this->imports->find($id);
        if (!$item || (int)$item['user_id'] !== Auth::id()) {
            $this->flash('danger', 'Import not found.');
            $this->redirect('/portal/import');
        }
        return $item;
    }
}
