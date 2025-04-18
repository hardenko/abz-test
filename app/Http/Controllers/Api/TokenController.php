<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;

final class TokenController extends BaseApiController
{
    public function __construct(private readonly TokenService $tokenService) {}

    public function generate(): JsonResponse
    {
        $token = $this->tokenService->generate();

        return $this->successResponse([
            'token' => $token
        ]);
    }
}
