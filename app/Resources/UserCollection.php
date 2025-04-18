<?php

namespace App\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserCollection extends ResourceCollection
{
    public $collects = UserResource::class;

    public function toArray(Request $request): array
    {
        /** @var LengthAwarePaginator $paginator */
        $paginator = $this->resource;

        return [
            'success' => true,
            'page' => $paginator->currentPage(),
            'total_pages' => $paginator->lastPage(),
            'total_users' => $paginator->total(),
            'count' => $paginator->count(),
            'links' => [
                'next_url' => $paginator->nextPageUrl(),
                'prev_url' => $paginator->previousPageUrl(),
            ],
            'users' => $this->collection,
        ];
    }

    public function with(Request $request): array
    {
        return [];
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json($this->toArray($request));
    }
}
