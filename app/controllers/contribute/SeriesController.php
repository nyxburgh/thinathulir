<?php
namespace App\Controllers\Contribute;

use App\Core\{Controller, Session, Helper, CSRF};
use App\Models\SeriesModel;

class SeriesController extends Controller
{
    private SeriesModel $series;
    private int          $contributorId;
    private array         $assignedCatIds;

    public function middleware(): void
    {
        if (!Session::get('contributor_id')) {
            Helper::redirect('/contribute/login');
        }
        $this->contributorId  = Session::get('contributor_id');
        $this->series         = new SeriesModel();
        $this->assignedCatIds = Session::get('contributor_cats', []);
    }

    public function index(): void
    {
        $this->view('contribute.series.index', [
            'pageTitle' => 'My Series',
            'series'    => $this->series->byContributor($this->contributorId),
        ], 'contributor');
    }

    public function create(): void
    {
        $this->view('contribute.series.form', [
            'pageTitle'  => 'New Series',
            'seriesItem' => [],
            'categories' => $this->allowedCategories(),
            'isEdit'     => false,
        ], 'contributor');
    }

    public function store(): void
    {
        CSRF::validate();
        $this->series->store($this->buildData());
        $this->flash('success', 'Series created.');
        Helper::redirect('/contribute/series');
    }

    public function edit(string $id): void
    {
        $item = $this->ownerCheck((int)$id);
        $this->view('contribute.series.form', [
            'pageTitle'  => 'Edit Series',
            'seriesItem' => $item,
            'categories' => $this->allowedCategories(),
            'isEdit'     => true,
        ], 'contributor');
    }

    public function update(string $id): void
    {
        CSRF::validate();
        $this->ownerCheck((int)$id);
        $this->series->updateSeries((int)$id, $this->buildData());
        $this->flash('success', 'Series updated.');
        Helper::redirect('/contribute/series');
    }

    public function delete(string $id): void
    {
        CSRF::validate();
        $this->ownerCheck((int)$id);
        if (!$this->series->deleteIfEmpty((int)$id)) {
            $this->flash('danger', 'Cannot delete a series that already has articles in it.');
        } else {
            $this->flash('success', 'Series deleted.');
        }
        Helper::redirect('/contribute/series');
    }

    // ── Private ──────────────────────────────────────

    private function ownerCheck(int $id): array
    {
        $item = $this->series->find($id);
        if (!$item || (int)$item['contributor_id'] !== $this->contributorId) {
            $this->flash('danger', 'Series not found.');
            Helper::redirect('/contribute/series');
        }
        return $item;
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
        $title = Helper::sanitize($_POST['title'] ?? '');
        $slug  = Helper::uniqueSlug('tn_series', $_POST['slug'] ?? '' ?: Helper::slug($title));

        $allowedStatus = ['ongoing', 'completed'];
        $status = in_array($_POST['status'] ?? '', $allowedStatus, true) ? $_POST['status'] : 'ongoing';

        return [
            'contributor_id' => $this->contributorId,
            'category_id'    => (int)($_POST['category_id'] ?? $this->assignedCatIds[0] ?? 1),
            'title'          => $title,
            'slug'           => $slug,
            'description'    => $_POST['description'] ?? '',
            'status'         => $status,
        ];
    }
}
