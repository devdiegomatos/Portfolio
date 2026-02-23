<?php

namespace App\Security;

interface IpAddressResolverInterface
{
    public function resolve(array $server): string;
}
