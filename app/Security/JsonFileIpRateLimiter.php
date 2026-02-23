<?php

namespace App\Security;

class JsonFileIpRateLimiter implements RateLimiterInterface
{
    static string $STORAGE_PATH = __DIR__ . '/../../storage/rate_limit.json';
    static int $RATE_LIMIT_WINDOW_SECONDS = 600;
    static int $RATE_LIMIT_MAX_REQUESTS = 5;
    static int $RATE_LIMIT_MIN_INTERVAL_SECONDS = 10;

    public function __construct() {}

    public function isBlocked(string $key): bool
    {
        $handle = fopen(self::$STORAGE_PATH, 'c+');
        if ($handle === false) {
            return false;
        }

        if (flock($handle, LOCK_EX) === false) {
            fclose($handle);
            return false;
        }

        $raw = stream_get_contents($handle);
        $rateLimitData = [];

        if (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $rateLimitData = $decoded;
            }
        }

        $now = time();
        $windowStart = $now - self::$RATE_LIMIT_WINDOW_SECONDS;

        foreach ($rateLimitData as $trackedKey => $timestamps) {
            if (is_array($timestamps) === false) {
                unset($rateLimitData[$trackedKey]);
                continue;
            }

            $filtered = array_values(array_filter($timestamps, static function ($timestamp) use ($windowStart): bool {
                return is_int($timestamp) && $timestamp >= $windowStart;
            }));

            if (empty($filtered)) {
                unset($rateLimitData[$trackedKey]);
                continue;
            }

            $rateLimitData[$trackedKey] = $filtered;
        }

        $requests = $rateLimitData[$key] ?? [];
        $lastRequestAt = empty($requests) ? null : end($requests);

        $isBlocked =
            count($requests) >= self::$RATE_LIMIT_MAX_REQUESTS ||
            ($lastRequestAt !== false && is_int($lastRequestAt) && ($now - $lastRequestAt) < self::$RATE_LIMIT_MIN_INTERVAL_SECONDS);

        if ($isBlocked === false) {
            $requests[] = $now;
            $rateLimitData[$key] = $requests;
        }

        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, json_encode($rateLimitData, JSON_UNESCAPED_UNICODE));
        fflush($handle);

        flock($handle, LOCK_UN);
        fclose($handle);

        return $isBlocked;
    }
}
