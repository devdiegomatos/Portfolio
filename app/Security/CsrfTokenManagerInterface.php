<?php

namespace App\Security;

interface CsrfTokenManagerInterface
{
    public function issue(): string;

    public function isValidRequest(array $postData): bool;
}
