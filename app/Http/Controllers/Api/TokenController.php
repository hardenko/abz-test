<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class TokenController extends BaseApiController
{
    public function generateToken(): JsonResponse
    {
        $token = base64_encode(Str::random(128));

        Cache::put("auth_token_{$token}", 'valid', now()->addMinutes(40));

        return $this->successResponse([
            'token' => $token
        ]);
    }
}
