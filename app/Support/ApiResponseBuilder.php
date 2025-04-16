<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponseBuilder
{
    public static function success(array $data, int $status = 200): JsonResponse
    {
        return response()->json(['success' => true] + $data, $status);
    }

    public static function paginated(AnonymousResourceCollection $resource, string $resourceKey = 'data'): JsonResponse
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = $resource->resource;

        return response()->json([
            'success' => true,
            'page' => $paginator->currentPage(),
            'total_pages' => $paginator->lastPage(),
            'total_users' => $paginator->total(),
            'count' => $paginator->count(),
            'links' => [
                'next_url' => $paginator->nextPageUrl(),
                'prev_url' => $paginator->previousPageUrl(),
            ],
            $resourceKey => $resource->collection,
        ]);
    }

    public static function error(string $message, int $status = 400, array $fails = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($fails)) {
            $response['fails'] = $fails;
        }

        return response()->json($response, $status);
    }
}
