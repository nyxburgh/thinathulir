<?php

namespace App\Core;

/**
 * Publishes due-scheduled articles opportunistically on request bootstrap.
 *
 * The "real" mechanism is cron/scheduled_publish.php via server crontab
 * (see cron/crontab.txt), but that requires an OS-level cron entry that
 * isn't present in local XAMPP dev and may not be wired up on a fresh
 * production deploy either. This runs the same publish step inline,
 * throttled via a lock file so it costs at most one extra query per
 * ~60s of traffic instead of one per request.
 */
class Scheduler
{
    private const THROTTLE_SECONDS = 60;

    public static function runIfDue(): void
    {
        $lockFile = STORAGE_PATH . '/cache/scheduled_publish.lock';

        if (is_file($lockFile) && (time() - filemtime($lockFile)) < self::THROTTLE_SECONDS) {
            return;
        }
        @touch($lockFile);

        try {
            $db = Database::getInstance();

            $due = $db->query(
                "SELECT id, title FROM tn_articles
                 WHERE status = 'scheduled'
                 AND scheduled_at IS NOT NULL
                 AND scheduled_at <= NOW()"
            )->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($due as $article) {
                $db->prepare(
                    "UPDATE tn_articles
                     SET status = 'published',
                         published_at = scheduled_at,
                         updated_at = NOW()
                     WHERE id = ?"
                )->execute([$article['id']]);

                $db->prepare(
                    "INSERT INTO tn_activity_log (action, entity, entity_id, description)
                     VALUES ('auto_publish', 'article', ?, ?)"
                )->execute([$article['id'], "Auto-published: {$article['title']}"]);
            }
        } catch (\Throwable $e) {
            error_log('[Scheduler] ' . $e->getMessage());
        }
    }
}
