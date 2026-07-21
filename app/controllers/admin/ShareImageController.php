<?php
namespace App\Controllers\Admin;

use App\Core\{Controller, Auth, CSRF, Helper, ShareImageGenerator};
use App\Models\{PhotoNewsModel, ArticleModel};

class ShareImageController extends Controller
{
    private PhotoNewsModel $pnModel;
    private ArticleModel $articles;
    private string $base;
    private string $layout;

    public function __construct()
    {
        $this->requireRole('admin', 'chief_editor', 'staff_reporter');
        $this->pnModel  = new PhotoNewsModel();
        $this->articles = new ArticleModel();
        $role          = Auth::role();
        $this->layout  = $role === 'admin' ? 'admin' : 'editor_portal';
        $this->base    = $role === 'admin' ? '/admin/share-image' : '/portal/share-image';
    }

    // GET — placement picker
    public function create(string $id): void
    {
        $article = $this->articles->find((int)$id);
        if (!$article) {
            $this->flash('danger', 'Article not found.');
            $this->redirect($this->articlesBase());
        }

        $this->view('admin.share_image.create', [
            'pageTitle' => 'Create Share Image',
            'article'   => $article,
            'existing'  => $this->pnModel->findByArticleId((int)$id),
            'base'      => $this->base,
            'artBase'   => $this->articlesBase(),
        ], $this->layout);
    }

    // POST — render the graphic and upsert the linked Photo News entry
    public function generate(string $id): void
    {
        CSRF::validate();
        $article = $this->articles->find((int)$id);
        if (!$article) {
            $this->flash('danger', 'Article not found.');
            $this->redirect($this->articlesBase());
        }

        $placement = $this->post('placement', 'center');

        try {
            $path = ShareImageGenerator::render($article, $placement);
        } catch (\RuntimeException $e) {
            $this->flash('danger', $e->getMessage());
            $this->redirect($this->base . '/create/' . $id);
        }

        $existing = $this->pnModel->findByArticleId((int)$id);
        if ($existing) {
            if (!empty($existing['image_path'])) {
                @unlink(ROOT_PATH . '/public' . $existing['image_path']);
            }
            $this->pnModel->updateGenerated((int)$existing['id'], $path, $placement);
        } else {
            $slug = $this->pnModel->uniqueSlug(Helper::slug($article['title']));
            $this->pnModel->storeGenerated((int)$id, $article['title'], $slug, $path, $placement, Auth::id());
        }

        $this->flash('success', 'Share image generated and linked to Photo News.');
        $this->redirect($this->articlesBase());
    }

    private function articlesBase(): string
    {
        return Auth::role() === 'admin' ? '/admin/articles' : '/portal/all-articles';
    }
}
