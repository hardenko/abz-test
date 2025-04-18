<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class TokenService
{
    public function generateToken(): string
    {
        $token = base64_encode(Str::random(128));

        Cache::put("auth_token_{$token}", 'valid', now()->addMinutes(40));

        return $token;
    }
}
