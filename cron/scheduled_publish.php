<?php
/**
 * Scheduled Publishing Cron
 * Schedule: every 5 minutes — php /path/to/cron/scheduled_publish.php
 */

define('ROOT_PATH',   dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('VIEW_PATH',   APP_PATH  . '/views');
define('STORAGE_PATH',ROOT_PATH . '/storage');
define('APP_DEBUG',   false);

date_default_timezone_set('Asia/Kolkata');

$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$key, $val] = explode('=', $line, 2) + [1 => ''];
        $_ENV[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
    }
}

spl_autoload_register(function (string $class): void {
    $relative = substr($class, strlen('App\\'));
    $segments = explode('\\', $relative);
    $filename = array_pop($segments);
    $dirParts = array_map('strtolower', $segments);
    $dirParts[] = $filename;
    $file = APP_PATH . '/' . implode('/', $dirParts) . '.php';
    if (file_exists($file)) require_once $file;
});

$startTime = microtime(true);
$db        = \App\Core\Database::getInstance();

// Fetch articles due for publishing
$due = $db->query(
    "SELECT id, title FROM tn_articles
     WHERE status = 'scheduled'
     AND scheduled_at IS NOT NULL
     AND scheduled_at <= NOW()"
)->fetchAll(PDO::FETCH_ASSOC);

$published = 0;

foreach ($due as $article) {
    // Core action — must succeed, this is the whole point of the cron
    $db->prepare(
        "UPDATE tn_articles
         SET status = 'published',
             published_at = scheduled_at,
             updated_at = NOW()
         WHERE id = ?"
    )->execute([$article['id']]);

    $published++;

    // Everything below is auxiliary — a schema mismatch here should not
    // stop the actual publish above from having happened (see CLAUDE.md
    // note on live/local DB drift; production may be missing newer
    // tables/columns that local dev already has).
    try {
        $db->prepare(
            "INSERT INTO tn_activity_log (action, entity, entity_id, description)
             VALUES ('auto_publish', 'article', ?, ?)"
        )->execute([$article['id'], "Auto-published: {$article['title']}"]);
    } catch (\PDOException $e) {
        // tn_activity_log missing/different schema on this environment — skip
    }
}

// Also expire breaking news past expiry time
try {
    $db->query(
        "UPDATE tn_articles
         SET is_breaking = 0, breaking_expires_at = NULL
         WHERE is_breaking = 1
         AND breaking_expires_at IS NOT NULL
         AND breaking_expires_at < NOW()"
    );
} catch (\PDOException $e) {
    // breaking_expires_at column missing on this environment — skip
}

logCron($db, 'scheduled_publish', 'success', "Published {$published} scheduled articles", $published, $startTime);
echo "Done. Published: {$published}\n";

function logCron(\PDO $db, string $job, string $status, string $message, int $records, float $start): void {
    $ms = (int)((microtime(true) - $start) * 1000);
    try {
        $db->prepare("INSERT INTO tn_cron_logs (job, status, message, records, duration_ms) VALUES (?,?,?,?,?)")
           ->execute([$job, $status, $message, $records, $ms]);
    } catch (\PDOException $e) {
        // tn_cron_logs missing/different schema on this environment — skip
    }
}
