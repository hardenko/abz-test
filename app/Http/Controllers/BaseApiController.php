<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class BaseApiController extends Controller
{
    protected function successResponse(array $data, int $status = 200): JsonResponse
    {
        return response()->json(['success' => true] + $data, $status);
    }

    protected function failResponse(string $message, int $status = 400, array $fails = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (! empty($fails)) {
            $response['fails'] = $fails;
        }

        return response()->json($response, $status);
    }
}
