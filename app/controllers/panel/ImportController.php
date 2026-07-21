<?php
namespace App\Controllers\Panel;

use App\Core\{Controller, Auth, Helper, CSRF};
use App\Models\ContentImportModel;

class ImportController extends Controller
{
    private ContentImportModel $imports;

    public function middleware(): void { $this->requireRole('sub_admin'); }

    public function __construct()
    {
        $this->imports = new ContentImportModel();
    }

    public function index(): void
    {
        $this->view('panel.import.index', [
            'pageTitle' => 'Import from URL',
            'imports'   => $this->imports->byUser(Auth::id()),
        ], 'subadmin');
    }

    public function fetch(): void
    {
        CSRF::validate();
        $url = trim($this->post('source_url', ''));

        if (!$url) {
            $this->flash('danger', 'Please enter a URL.');
            $this->redirect('/panel/import');
        }

        $fetched = Helper::fetchUrlContent($url);
        if (!$fetched) {
            $reason = Helper::$lastFetchError ?: 'Check the link and try again.';
            $this->flash('danger', 'Could not fetch content from that URL. ' . $reason);
            $this->redirect('/panel/import');
        }

        $this->imports->store([
            'user_id'    => Auth::id(),
            'source_url' => $url,
            'title'      => $fetched['title'],
            'content'    => $fetched['content'],
            'status'     => 'pending',
        ]);

        $this->flash('success', 'Content fetched. Review it below.');
        $this->redirect('/panel/import');
    }

    public function discard(string $id): void
    {
        CSRF::validate();
        $item = $this->ownerCheck((int)$id);
        $this->imports->markDiscarded((int)$item['id']);
        $this->flash('success', 'Import discarded.');
        $this->redirect('/panel/import');
    }

    private function ownerCheck(int $id): array
    {
        $item = $this->imports->find($id);
        if (!$item || (int)$item['user_id'] !== Auth::id()) {
            $this->flash('danger', 'Import not found.');
            $this->redirect('/panel/import');
        }
        return $item;
    }
}
