<?php

namespace App\Http\Middleware;

use App\Services\TokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class ValidateApiToken
{
    public function __construct(private TokenService $tokenService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $this->tokenService->validateOnce($token)) {
            return response()->json([
                'success' => false,
                'message' => 'The token expired.',
            ], 401);
        }

        return $next($request);
    }
}
