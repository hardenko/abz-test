<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

final readonly class TokenValidatorService
{
    public function validateOnce(?string $token): bool
    {
        if (!$token || Cache::get("auth_token_{$token}") !== 'valid') {
            return false;
        }

        Cache::forget("auth_token_{$token}");
        return true;
    }
}
