<?php
namespace App\Services;

use App\Core\Database;

class SocialPostService
{
    private \PDO   $db;
    private ?array $facebookConfig;
    private ?array $threadsConfig;

    private const GRAPH_API_VERSION = 'v19.0';

    public function __construct()
    {
        $this->db             = Database::getInstance();
        $this->facebookConfig = $this->loadConfig('facebook.json', ['page_id', 'page_access_token']);
        $this->threadsConfig  = $this->loadConfig('threads.json', ['user_id', 'access_token']);
    }

    private function loadConfig(string $file, array $requiredKeys): ?array
    {
        $path = ROOT_PATH . '/config/' . $file;
        if (!is_file($path)) return null;

        $decoded = json_decode(file_get_contents($path), true);
        if (!is_array($decoded)) return null;

        foreach ($requiredKeys as $key) {
            if (empty($decoded[$key])) return null;
        }
        return $decoded;
    }

    // ── Facebook Page post ────────────────────────────────────

    public function postToFacebook(array $article, ?int $byUserId = null): array
    {
        $message = $this->buildMessage($article);
        $link    = $this->articleUrl($article);

        $logId = $this->log('facebook', (int)$article['id'], $message, $link, $byUserId);

        if (!$this->facebookConfig) {
            $this->markFailed($logId, 'Facebook not configured. Add config/facebook.json (copy from facebook.json.example)');
            return ['success' => false, 'reason' => 'not_configured', 'log_id' => $logId];
        }

        $url = sprintf(
            'https://graph.facebook.com/%s/%s/feed',
            self::GRAPH_API_VERSION,
            $this->facebookConfig['page_id']
        );

        $response = $this->postJson($url, [
            'message'      => $message,
            'link'         => $link,
            'access_token' => $this->facebookConfig['page_access_token'],
        ]);

        if ($response['ok']) {
            $this->markSent($logId, $response['data']['id'] ?? null);
            return ['success' => true, 'post_id' => $response['data']['id'] ?? null, 'log_id' => $logId];
        }

        $error = $response['data']['error']['message'] ?? ('HTTP ' . $response['http_code']);
        $this->markFailed($logId, $error);
        return ['success' => false, 'reason' => $error, 'log_id' => $logId];
    }

    // ── Threads post ───────────────────────────────────────────
    // Scaffolded for when a Threads API app/token is available.
    // Flow: create a media container, then publish it (two calls), per Meta's Threads API.

    public function postToThreads(array $article, ?int $byUserId = null): array
    {
        $message = $this->buildMessage($article);
        $link    = $this->articleUrl($article);

        $logId = $this->log('threads', (int)$article['id'], $message, $link, $byUserId);

        if (!$this->threadsConfig) {
            $this->markFailed($logId, 'Threads not configured. Add config/threads.json (copy from threads.json.example)');
            return ['success' => false, 'reason' => 'not_configured', 'log_id' => $logId];
        }

        $userId = $this->threadsConfig['user_id'];
        $token  = $this->threadsConfig['access_token'];

        $create = $this->postJson("https://graph.threads.net/v1.0/{$userId}/threads", [
            'media_type'   => 'TEXT',
            'text'         => $message . "\n\n" . $link,
            'access_token' => $token,
        ]);
        if (!$create['ok'] || empty($create['data']['id'])) {
            $error = $create['data']['error']['message'] ?? ('HTTP ' . $create['http_code']);
            $this->markFailed($logId, $error);
            return ['success' => false, 'reason' => $error, 'log_id' => $logId];
        }

        $publish = $this->postJson("https://graph.threads.net/v1.0/{$userId}/threads_publish", [
            'creation_id'  => $create['data']['id'],
            'access_token' => $token,
        ]);
        if ($publish['ok']) {
            $this->markSent($logId, $publish['data']['id'] ?? null);
            return ['success' => true, 'post_id' => $publish['data']['id'] ?? null, 'log_id' => $logId];
        }

        $error = $publish['data']['error']['message'] ?? ('HTTP ' . $publish['http_code']);
        $this->markFailed($logId, $error);
        return ['success' => false, 'reason' => $error, 'log_id' => $logId];
    }

    // ── Helpers ────────────────────────────────────────────────

    private function buildMessage(array $article): string
    {
        $title   = $article['title'] ?? '';
        $excerpt = mb_substr(strip_tags($article['excerpt'] ?? $article['content'] ?? ''), 0, 200);
        return trim($title . ($excerpt ? "\n\n" . $excerpt : ''));
    }

    private function articleUrl(array $article): string
    {
        return rtrim((BASE_URL ?? ''), '/') . '/public/article/' . ($article['slug'] ?? '');
    }

    private function postJson(string $url, array $fields): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($fields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return ['ok' => false, 'http_code' => 0, 'data' => ['error' => ['message' => $err]]];
        }

        $data = json_decode((string)$response, true) ?? [];
        return ['ok' => $httpCode >= 200 && $httpCode < 300, 'http_code' => $httpCode, 'data' => $data];
    }

    private function log(string $platform, int $articleId, string $message, string $link, ?int $byUserId): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tn_social_post_logs (platform, article_id, message, link, status, posted_by)
             VALUES (?,?,?,?,?,?)"
        );
        $stmt->execute([$platform, $articleId, $message, $link, 'pending', $byUserId]);
        return (int)$this->db->lastInsertId();
    }

    private function markSent(int $logId, ?string $remotePostId): void
    {
        $this->db->prepare(
            "UPDATE tn_social_post_logs SET status='sent', remote_post_id=? WHERE id=?"
        )->execute([$remotePostId, $logId]);
    }

    private function markFailed(int $logId, string $error): void
    {
        $this->db->prepare(
            "UPDATE tn_social_post_logs SET status='failed', error=? WHERE id=?"
        )->execute([$error, $logId]);
    }
}
