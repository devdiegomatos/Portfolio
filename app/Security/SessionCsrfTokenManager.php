<?php

namespace App\Security;

class SessionCsrfTokenManager implements CsrfTokenManagerInterface
{
    static string $TOKEN_KEY = 'csrf';
    static string $ISSUED_AT_KEY = 'csrf_issued_at';
    static int $CSRF_TTL_SECONDS = 3600;

    public function __construct() {}

    public function issue(): string
    {
        $token = bin2hex(random_bytes(32));

        $_SESSION[self::$TOKEN_KEY] = $token;
        $_SESSION[self::$ISSUED_AT_KEY] = time();

        return $token;
    }

    public function isValidRequest(array $postData): bool
    {
        if (
            isset($postData['csrf'], $_SESSION[self::$TOKEN_KEY], $_SESSION[self::$ISSUED_AT_KEY]) === false ||
            is_string($postData['csrf']) === false ||
            is_string($_SESSION[self::$TOKEN_KEY]) === false
        ) {
            return false;
        }

        if (time() - (int) $_SESSION[self::$ISSUED_AT_KEY] > $this::$CSRF_TTL_SECONDS) {
            return false;
        }

        return hash_equals($_SESSION[self::$TOKEN_KEY], $postData['csrf']);
    }
}
