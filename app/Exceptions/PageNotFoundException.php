<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class PageNotFoundException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Page not found',
        ], 404);
    }
}
