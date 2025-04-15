<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class BaseApiController extends Controller
{
    public function response(
        string $message = 'Success!',
        mixed $data = [],
        array|string $errors = [],
        int $status = 200
    ): JsonResponse {
        return response()->json(compact('message', 'data', 'errors', 'status'), $status);
    }
}
