<?php

namespace App\Services;

class IndexNowService
{
    private string $host = 'https://www.thinathulir.com';
    private string $key = '228278604b7c4612bbaa1f7d12ea26ce';

    public function submit(string $url): array
    {
        $endpoint = 'https://api.indexnow.org/indexnow';

        $payload = [
            'host'        => parse_url($this->host, PHP_URL_HOST),
            'key'         => $this->key,
            'keyLocation' => $this->host . '/' . $this->key . '.txt',
            'urlList'     => [$url]
        ];

        $ch = curl_init($endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30
        ]);

        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);

        curl_close($ch);

        return [
            'success' => ($status == 200 || $status == 202),
            'status'  => $status,
            'response'=> $response,
            'error'   => $error
        ];
    }
}