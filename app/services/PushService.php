<?php
namespace App\Services;

use App\Core\Database;

class PushService
{
    private \PDO    $db;
    private ?array  $serviceAccount;
    private bool    $configured;
    private string  $tokenCacheFile;

    public function __construct()
    {
        $this->db = Database::getInstance();

        $saPath = ROOT_PATH . '/config/firebase-service-account.json';
        $this->serviceAccount = null;
        if (is_file($saPath)) {
            $decoded = json_decode(file_get_contents($saPath), true);
            if (is_array($decoded) && !empty($decoded['private_key']) && !empty($decoded['client_email'])) {
                $this->serviceAccount = $decoded;
            }
        }
        $this->configured     = $this->serviceAccount !== null;
        $this->tokenCacheFile = ROOT_PATH . '/storage/cache/fcm_access_token.json';
    }

    // ── Send article push ─────────────────────────────────────

    public function sendArticle(array $article, array $districtIds = []): array
    {
        $title    = $article['title'] ?? '';
        $excerpt  = mb_substr(strip_tags($article['excerpt'] ?? $article['content'] ?? ''), 0, 120);
        $imageUrl = !empty($article['image_url']) ? $article['image_url'] : null;
        $slug     = $article['slug'] ?? '';
        $clickUrl = (BASE_URL ?? '') . '/public/article/' . $slug;

        $isBreaking = !empty($article['is_breaking']);

        return $this->dispatch(
            type:       'article',
            refId:      (int)$article['id'],
            title:      ($isBreaking ? '⚡ Breaking: ' : '') . $title,
            body:       $excerpt ?: 'Read the full story →',
            imageUrl:   $imageUrl,
            clickUrl:   $clickUrl,
            districtIds:$districtIds
        );
    }

    // ── Send ad push ─────────────────────────────────────────

    public function sendAd(array $ad, array $districtIds = []): array
    {
        $title    = '📢 ' . ($ad['business_name'] ?? 'New Ad');
        $body     = $ad['notes'] ?? 'Check out this advertisement';
        $imageUrl = !empty($ad['images'][0]['filepath'])
                  ? (rtrim(ASSET_URL ?? '', '/') . $ad['images'][0]['filepath'])
                  : null;
        $clickUrl = (BASE_URL ?? '') . '/public/';

        // District from ad if not passed
        if (empty($districtIds) && !empty($ad['district_id'])) {
            $districtIds = [(int)$ad['district_id']];
        }

        return $this->dispatch(
            type:       'ad',
            refId:      (int)$ad['id'],
            title:      $title,
            body:       $body,
            imageUrl:   $imageUrl,
            clickUrl:   $clickUrl,
            districtIds:$districtIds
        );
    }

    // ── Manual push ──────────────────────────────────────────

    public function sendManual(string $title, string $body, ?string $clickUrl = null, array $districtIds = [], ?int $byUserId = null): array
    {
        return $this->dispatch(
            type:       'manual',
            refId:      null,
            title:      $title,
            body:       $body,
            imageUrl:   null,
            clickUrl:   $clickUrl ?? ((BASE_URL ?? '') . '/public/'),
            districtIds:$districtIds,
            byUserId:   $byUserId
        );
    }

    // ── Core dispatch ─────────────────────────────────────────

    private function dispatch(
        string  $type,
        ?int    $refId,
        string  $title,
        string  $body,
        ?string $imageUrl,
        string  $clickUrl,
        array   $districtIds = [],
        ?int    $byUserId = null
    ): array {
        // Log the push
        $logStmt = $this->db->prepare(
            "INSERT INTO tn_push_logs (type, ref_id, title, body, image_url, click_url, districts, status, sent_by)
             VALUES (?,?,?,?,?,?,?,?,?)"
        );
        $logStmt->execute([
            $type, $refId, $title, $body, $imageUrl, $clickUrl,
            !empty($districtIds) ? json_encode($districtIds) : null,
            'pending', $byUserId
        ]);
        $logId = (int)$this->db->lastInsertId();

        if (!$this->configured) {
            $this->db->prepare("UPDATE tn_push_logs SET status='failed' WHERE id=?")->execute([$logId]);
            return ['success' => false, 'reason' => 'FCM not configured. Add config/firebase-service-account.json', 'log_id' => $logId];
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            $this->db->prepare("UPDATE tn_push_logs SET status='failed' WHERE id=?")->execute([$logId]);
            return ['success' => false, 'reason' => 'Could not obtain FCM OAuth token', 'log_id' => $logId];
        }

        // Get subscriber tokens
        $tokens = $this->getTokens($districtIds);
        if (empty($tokens)) {
            $this->db->prepare("UPDATE tn_push_logs SET status='sent', sent_count=0 WHERE id=?")->execute([$logId]);
            return ['success' => true, 'sent' => 0, 'log_id' => $logId];
        }

        $result = $this->fcmSendBatch($accessToken, $title, $body, $clickUrl, $imageUrl, $tokens);

        $this->db->prepare(
            "UPDATE tn_push_logs SET status='sent', sent_count=?, fail_count=? WHERE id=?"
        )->execute([$result['success'], $result['failure'], $logId]);

        return ['success' => true, 'sent' => $result['success'], 'failed' => $result['failure'], 'log_id' => $logId];
    }

    // ── FCM HTTP v1 API (OAuth2 service account) ──────────────

    private function fcmSendBatch(string $accessToken, string $title, string $body, string $clickUrl, ?string $imageUrl, array $tokens): array
    {
        $projectId = $this->serviceAccount['project_id'];
        $url       = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
        $headers   = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        $success = 0; $failure = 0; $deadTokens = [];

        foreach (array_chunk($tokens, 50) as $batch) {
            $mh      = curl_multi_init();
            $handles = [];

            foreach ($batch as $token) {
                $notification = ['title' => $title, 'body' => $body];
                if ($imageUrl) $notification['image'] = $imageUrl;

                $message = [
                    'message' => [
                        'token'        => $token,
                        'notification' => $notification,
                        'data'         => [
                            'click_url' => $clickUrl,
                            'title'     => $title,
                            'body'      => $body,
                        ],
                        'webpush' => [
                            'fcm_options' => ['link' => $clickUrl],
                            'notification' => ['icon' => '/public/assets/img/logo-192.png'],
                        ],
                    ],
                ];

                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_POST           => true,
                    CURLOPT_HTTPHEADER     => $headers,
                    CURLOPT_POSTFIELDS     => json_encode($message),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT        => 10,
                ]);
                curl_multi_add_handle($mh, $ch);
                $handles[$token] = $ch;
            }

            $running = null;
            do { curl_multi_exec($mh, $running); } while ($running > 0);

            foreach ($handles as $token => $ch) {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response = curl_multi_getcontent($ch);
                if ($httpCode >= 200 && $httpCode < 300) {
                    $success++;
                } else {
                    $failure++;
                    $data   = json_decode((string)$response, true);
                    $status = $data['error']['status'] ?? '';
                    if (in_array($status, ['UNREGISTERED', 'NOT_FOUND', 'INVALID_ARGUMENT'], true)) {
                        $deadTokens[] = $token;
                    }
                }
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }
            curl_multi_close($mh);
        }

        if (!empty($deadTokens)) {
            $placeholders = implode(',', array_fill(0, count($deadTokens), '?'));
            $this->db->prepare(
                "UPDATE tn_push_subscribers SET is_active = 0 WHERE fcm_token IN ($placeholders)"
            )->execute($deadTokens);
        }

        return ['success' => $success, 'failure' => $failure];
    }

    // ── OAuth2 access token (cached, service-account JWT bearer flow) ──

    private function getAccessToken(): ?string
    {
        if (is_file($this->tokenCacheFile)) {
            $cached = json_decode(file_get_contents($this->tokenCacheFile), true);
            if (!empty($cached['access_token']) && !empty($cached['expires_at']) && $cached['expires_at'] > time() + 60) {
                return $cached['access_token'];
            }
        }

        $sa       = $this->serviceAccount;
        $now      = time();
        $header   = $this->b64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claims   = $this->b64url(json_encode([
            'iss'   => $sa['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => $sa['token_uri'] ?? 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]));
        $unsigned = $header . '.' . $claims;

        $signature = '';
        $ok = openssl_sign($unsigned, $signature, $sa['private_key'], OPENSSL_ALGO_SHA256);
        if (!$ok) return null;
        $jwt = $unsigned . '.' . $this->b64url($signature);

        $ch = curl_init($sa['token_uri'] ?? 'https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
        ]);
        $response = curl_exec($ch);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err || !$response) return null;
        $data = json_decode($response, true);
        if (empty($data['access_token'])) return null;

        @file_put_contents($this->tokenCacheFile, json_encode([
            'access_token' => $data['access_token'],
            'expires_at'   => $now + (int)($data['expires_in'] ?? 3600),
        ]));

        return $data['access_token'];
    }

    private function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // ── Token retrieval ──────────────────────────────────────

    private function getTokens(array $districtIds): array
    {
        if (!empty($districtIds)) {
            $placeholders = implode(',', array_fill(0, count($districtIds), '?'));
            $stmt = $this->db->prepare(
                "SELECT DISTINCT fcm_token FROM tn_push_subscribers
                 WHERE is_active = 1 AND (district_id IN ($placeholders) OR district_id IS NULL)"
            );
            $stmt->execute($districtIds);
        } else {
            $stmt = $this->db->query("SELECT DISTINCT fcm_token FROM tn_push_subscribers WHERE is_active = 1");
        }
        return array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'fcm_token');
    }

    // ── Subscribe (called from API) ──────────────────────────

    public function subscribe(string $token, ?int $userId = null, ?int $districtId = null, string $platform = 'web'): void
    {
        // Upsert: update existing token or insert new
        $this->db->prepare(
            "INSERT INTO tn_push_subscribers (fcm_token, user_id, district_id, platform, is_active)
             VALUES (?,?,?,?,1)
             ON DUPLICATE KEY UPDATE user_id=VALUES(user_id), district_id=VALUES(district_id), is_active=1, updated_at=NOW()"
        )->execute([$token, $userId, $districtId, $platform]);
    }

    public function unsubscribe(string $token): void
    {
        $this->db->prepare("UPDATE tn_push_subscribers SET is_active=0 WHERE fcm_token=?")->execute([$token]);
    }

    public function subscriberCount(?int $districtId = null): int
    {
        if ($districtId) {
            return (int)$this->db->query("SELECT COUNT(*) FROM tn_push_subscribers WHERE is_active=1 AND district_id=$districtId")->fetchColumn();
        }
        return (int)$this->db->query("SELECT COUNT(*) FROM tn_push_subscribers WHERE is_active=1")->fetchColumn();
    }
}
