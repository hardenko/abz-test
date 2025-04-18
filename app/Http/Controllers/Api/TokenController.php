<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;

final class TokenController extends BaseApiController
{
    public function __construct(private readonly TokenService $tokenService) {}

    public function generateToken(): JsonResponse
    {
        $token = $this->tokenService->generateToken();

        return $this->successResponse([
            'token' => $token
        ]);
    }
}
