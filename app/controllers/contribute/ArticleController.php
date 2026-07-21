<?php
namespace App\Controllers\Contribute;

use App\Core\{Controller, Session, Helper, CSRF};
use App\Models\{ArticleModel, ContributorModel, TagModel, SeriesModel};

class ArticleController extends Controller
{
    private ArticleModel    $articles;
    private int             $contributorId;
    private array           $assignedCatIds;

    public function middleware(): void
    {
        if (!Session::get('contributor_id')) {
            Helper::redirect('/contribute/login');
        }
        $this->contributorId  = Session::get('contributor_id');
        $this->articles       = new ArticleModel();
        $this->assignedCatIds = Session::get('contributor_cats', []);
    }

    public function index(): void
    {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $status = $_GET['status'] ?? '';
        $result = $this->articles->byContributor($this->contributorId, $page, 15, $status);

        $this->view('contribute.articles.index', [
            'pageTitle' => 'My Articles',
            'articles'  => $result['data'],
            'total'     => $result['total'],
            'page'      => $result['page'],
            'per_page'  => $result['per_page'],
            'status'    => $status,
        ], 'contributor');
    }

    public function create(): void
    {
        $categories = $this->allowedCategories();
        $preselectSeriesId = (int)($_GET['series_id'] ?? 0);
        $this->view('contribute.articles.form', [
            'pageTitle'  => 'Submit Article',
            'article'    => $preselectSeriesId ? ['series_id' => $preselectSeriesId] : [],
            'categories' => $categories,
            'tags'       => [],
            'mySeries'   => (new SeriesModel())->byContributor($this->contributorId),
            'isEdit'     => false,
        ], 'contributor');
    }

    public function store(): void
    {
        CSRF::validate();
        $data = $this->buildData();
        $id   = $this->articles->store($data);

        if (!empty($_POST['tag_ids'])) {
            (new TagModel())->syncArticleTags($id, array_map('intval', $_POST['tag_ids']));
        }

        $this->flash('success', 'Article submitted for review.');
        Helper::redirect('/contribute/articles');
    }

    public function edit(string $id): void
    {
        $article = $this->ownerCheck((int)$id);
        if (in_array($article['status'], ['published', 'review'])) {
            $this->flash('danger', 'Published or under-review articles cannot be edited.');
            Helper::redirect('/contribute/articles');
        }

        $this->view('contribute.articles.form', [
            'pageTitle'  => 'Edit Article',
            'article'    => $article,
            'categories' => $this->allowedCategories(),
            'tags'       => (new TagModel())->forArticle((int)$id),
            'mySeries'   => (new SeriesModel())->byContributor($this->contributorId),
            'isEdit'     => true,
        ], 'contributor');
    }

    public function update(string $id): void
    {
        CSRF::validate();
        $article = $this->ownerCheck((int)$id);
        if (in_array($article['status'], ['published', 'review'])) {
            $this->flash('danger', 'Cannot edit at this stage.');
            Helper::redirect('/contribute/articles');
        }

        $data = $this->buildData();
        $this->articles->updateArticle((int)$id, $data);

        if (isset($_POST['tag_ids'])) {
            (new TagModel())->syncArticleTags((int)$id, array_map('intval', $_POST['tag_ids']));
        }

        $this->flash('success', 'Article updated.');
        Helper::redirect('/contribute/articles');
    }

    public function delete(string $id): void
    {
        CSRF::validate();
        $article = $this->ownerCheck((int)$id);
        if ($article['status'] === 'published') {
            $this->flash('danger', 'Published articles cannot be deleted.');
            Helper::redirect('/contribute/articles');
        }
        $this->articles->delete((int)$id);
        $this->flash('success', 'Article deleted.');
        Helper::redirect('/contribute/articles');
    }

    // ── Private ──────────────────────────────────────

    private function ownerCheck(int $id): array
    {
        $article = $this->articles->find($id);
        if (!$article || (int)$article['contributor_id'] !== $this->contributorId) {
            $this->flash('danger', 'Article not found.');
            Helper::redirect('/contribute/articles');
        }
        return $article;
    }

    private function allowedCategories(): array
    {
        if (empty($this->assignedCatIds)) return [];
        $placeholders = implode(',', array_fill(0, count($this->assignedCatIds), '?'));
        $stmt = \App\Core\Database::getInstance()
            ->prepare("SELECT * FROM tn_categories WHERE id IN ({$placeholders}) AND is_active = 1");
        $stmt->execute($this->assignedCatIds);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function buildData(): array
    {
        $title   = Helper::sanitize($_POST['title'] ?? '');
        $slug    = Helper::uniqueSlug('tn_articles', $_POST['slug'] ?? '' ?: Helper::slug($title));
        $content = $_POST['content'] ?? '';

        $allowedTypes = ['news', 'special'];
        $contentType  = in_array($_POST['content_type'] ?? '', $allowedTypes, true)
            ? $_POST['content_type'] : 'special';

        $seriesModel = new SeriesModel();
        $seriesId    = (int)($_POST['series_id'] ?? 0) ?: null;
        if ($seriesId) {
            $ownSeries = $seriesModel->find($seriesId);
            if (!$ownSeries || (int)$ownSeries['contributor_id'] !== $this->contributorId) {
                $seriesId = null;
            }
        }
        $seriesPart = $seriesId ? $seriesModel->nextPartNumber($seriesId) : null;

        $categoryId = (int)($_POST['category_id'] ?? 0);
        if (!in_array($categoryId, $this->assignedCatIds, true)) {
            $categoryId = $this->assignedCatIds[0] ?? 1;
        }

        return [
            'contributor_id' => $this->contributorId,
            'user_id'        => 1, // system user
            'series_id'      => $seriesId,
            'series_part'    => $seriesPart,
            'category_id'    => $categoryId,
            'title'          => $title,
            'slug'           => $slug,
            'excerpt'        => $_POST['excerpt'] ?? '' ?: Helper::excerpt($content),
            'content'        => $content,
            'content_type'   => $contentType,
            'youtube_url'    => $_POST['youtube_url'] ?? '' ?: null,
            'youtube_video_id' => $_POST['youtube_url'] ? Helper::youtubeId($_POST['youtube_url']) : null,
            'status'         => 'review', // always goes to review
            'read_time'      => Helper::readTime($content),
            'meta_title'     => $_POST['meta_title'] ?? '' ?: null,
            'meta_desc'      => $_POST['meta_desc']  ?? '' ?: null,
        ];
    }
}
