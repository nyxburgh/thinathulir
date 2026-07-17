<?php
use App\Core\{Helper, Auth, CSRF};
$writeUrl = Auth::role() === 'admin' ? $r.'/admin/articles/create' : $r.'/portal/write';
$statusBadge = [
    'pending'   => 'warning',
    'converted' => 'success',
    'discarded' => 'secondary',
];
?>
<div class="portal-page-header">
  <div>
    <h2 class="portal-page-title">Import from URL</h2>
    <p style="font-size:13px;color:var(--portal-muted);margin:2px 0 0">Fetch title &amp; content from a third-party article to start a draft</p>
  </div>
</div>

<div class="portal-card mb-4">
  <div class="portal-card-header"><span><i class="bi bi-link-45deg me-2"></i>Fetch a URL</span></div>
  <div class="portal-card-body">
    <form method="POST" action="<?= $r ?>/portal/import/fetch" class="d-flex gap-2 flex-wrap">
      <?= CSRF::field() ?>
      <input type="url" name="source_url" class="form-control" style="flex:1;min-width:260px"
             placeholder="https://example.com/news/some-article" required>
      <button type="submit" class="btn fw-600" style="background:#10b981;color:white">
        <i class="bi bi-download me-2"></i>Fetch Content
      </button>
    </form>
    <small class="text-muted d-block mt-2">Pulls the title and body paragraphs only — add your own image and edit before submitting.</small>
  </div>
</div>

<div class="portal-card">
  <div class="table-responsive">
    <table class="table tn-table mb-0">
      <thead>
        <tr><th>Title / Source</th><th>Status</th><th>Fetched</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (empty($imports)): ?>
        <tr><td colspan="4" class="text-center py-5 text-muted">No imports yet. Paste a URL above to get started.</td></tr>
        <?php endif; ?>
        <?php foreach ($imports as $imp): ?>
        <tr>
          <td>
            <div class="fw-500"><?= Helper::e($imp['title'] ?: '(no title found)') ?></div>
            <div class="text-muted small text-truncate" style="max-width:420px">
              <a href="<?= Helper::e($imp['source_url']) ?>" target="_blank" rel="noopener"><?= Helper::e($imp['source_url']) ?></a>
            </div>
          </td>
          <td><span class="badge bg-<?= $statusBadge[$imp['status']] ?? 'secondary' ?>"><?= ucfirst($imp['status']) ?></span></td>
          <td class="text-muted small"><?= date('d M, H:i', strtotime($imp['created_at'])) ?></td>
          <td>
            <?php if ($imp['status'] === 'pending'): ?>
            <a href="<?= $writeUrl ?>?import_id=<?= $imp['id'] ?>" class="btn btn-sm btn-outline-success" title="Use in article">
              <i class="bi bi-pencil-square"></i> Use
            </a>
            <?php
              $plainTitle   = html_entity_decode($imp['title'] ?? '', ENT_QUOTES, 'UTF-8');
              $plainContent = html_entity_decode(strip_tags($imp['content'] ?? ''), ENT_QUOTES, 'UTF-8');
              $gptPrompt    = "Rewrite the following news content in clear, engaging Tamil for a news portal. Keep all facts accurate, don't add new information. "
                            . "Rewrite it fully in your own words and sentence structure — do not copy phrases verbatim from the source — so the final text is original and does not raise copyright issues:\n\n"
                            . "Title: {$plainTitle}\n\n{$plainContent}";
            ?>
            <button type="button" class="btn btn-sm btn-outline-secondary send-to-chatgpt"
                    data-prompt="<?= base64_encode($gptPrompt) ?>"
                    title="Copy prompt and open ChatGPT — paste with Ctrl+V">
              <i class="bi bi-stars"></i> ChatGPT
            </button>
            <form action="<?= $r ?>/portal/import/discard/<?= $imp['id'] ?>" method="POST" class="d-inline"
                  onsubmit="return confirm('Discard this import?')">
              <?= CSRF::field() ?>
              <button class="btn btn-sm btn-outline-danger" title="Discard"><i class="bi bi-trash"></i></button>
            </form>
            <?php elseif ($imp['status'] === 'converted' && $imp['converted_article_id']): ?>
            <a href="<?= $r ?>/portal/all-articles/edit/<?= $imp['converted_article_id'] ?>" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-box-arrow-up-right"></i> View Article
            </a>
            <form action="<?= $r ?>/portal/import/discard/<?= $imp['id'] ?>" method="POST" class="d-inline"
                  onsubmit="return confirm('Discard this import record?')">
              <?= CSRF::field() ?>
              <button class="btn btn-sm btn-outline-danger" title="Discard"><i class="bi bi-trash"></i></button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function importFallbackCopy(text) {
  var ta = document.createElement('textarea');
  ta.value = text;
  ta.style.position = 'fixed';
  ta.style.opacity = '0';
  document.body.appendChild(ta);
  ta.focus(); ta.select();
  try { document.execCommand('copy'); } catch (e) {}
  document.body.removeChild(ta);
}

document.querySelectorAll('.send-to-chatgpt').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var binary = atob(btn.dataset.prompt);
    var bytes  = new Uint8Array(binary.length);
    for (var i = 0; i < binary.length; i++) bytes[i] = binary.charCodeAt(i);
    var text = new TextDecoder('utf-8').decode(bytes);

    var original = btn.innerHTML;
    var showCopied = function () {
      btn.innerHTML = '<i class="bi bi-clipboard-check"></i> Copied! Paste in ChatGPT';
      window.open('https://chatgpt.com/', '_blank', 'noopener');
      setTimeout(function () { btn.innerHTML = original; }, 3000);
    };

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(showCopied).catch(function () {
        importFallbackCopy(text); showCopied();
      });
    } else {
      importFallbackCopy(text); showCopied();
    }
  });
});
</script>
