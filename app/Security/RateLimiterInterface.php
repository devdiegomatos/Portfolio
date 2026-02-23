<?php

namespace App\Security;

interface RateLimiterInterface
{
    public function isBlocked(string $key): bool;
}
