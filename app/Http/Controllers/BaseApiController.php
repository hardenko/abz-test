<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Contracts\Pagination\LengthAwarePaginator;
//use Illuminate\Http\JsonResponse;
//use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
//
//abstract class BaseApiController extends Controller
//{
//    public function response(
//        bool $success = true,
//        mixed $data = [],
//        int $status = 200,
//        ?string $resourceKey = null
//    ): JsonResponse {
//        if ($success && is_array($data) && isset($data['user_id'], $data['message'])) {
//            return response()->json([
//                'success' => true,
//                'user_id' => $data['user_id'],
//                'message' => $data['message'],
//            ], $status);
//        }
//
//        $response = ['success' => $success];
//
//        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator) {
//            $paginator = $data->resource;
//
//            $response['page'] = $paginator->currentPage();
//            $response['total_pages'] = $paginator->lastPage();
//            $response['total_users'] = $paginator->total();
//            $response['count'] = $paginator->count();
//            $response['links'] = [
//                'next_url' => $paginator->nextPageUrl(),
//                'prev_url' => $paginator->previousPageUrl(),
//            ];
//            $response[$resourceKey ?? 'users'] = $data->collection;
//        } else {
//            $response[$resourceKey ?? 'data'] = $data;
//        }
//
//        return response()->json($response, $status);
//    }
//}

namespace App\Http\Controllers;

use App\Support\ApiResponseBuilder;
use Illuminate\Http\JsonResponse;

abstract class BaseApiController extends Controller
{
    protected function successResponse(array $data, int $status = 200): JsonResponse
    {
        return ApiResponseBuilder::success($data, $status);
    }

    protected function failResponse(string $message, int $status = 400, array $fails = []): JsonResponse
    {
        return ApiResponseBuilder::error($message, $status, $fails);
    }
}

