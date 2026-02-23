<?php

namespace App\Security;

class ServerIpAddressResolver implements IpAddressResolverInterface
{
    public function resolve(array $server): string
    {
        if (empty($server['HTTP_X_FORWARDED_FOR']) === false) {
            $forwardedIps = explode(',', (string) $server['HTTP_X_FORWARDED_FOR']);
            $candidateIp = trim($forwardedIps[0]);

            if (filter_var($candidateIp, FILTER_VALIDATE_IP) !== false) {
                return $candidateIp;
            }
        }

        if (empty($server['REMOTE_ADDR']) === false && filter_var($server['REMOTE_ADDR'], FILTER_VALIDATE_IP) !== false) {
            return $server['REMOTE_ADDR'];
        }

        return '0.0.0.0';
    }
}
