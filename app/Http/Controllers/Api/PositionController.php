<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Models\Position;
use App\Resources\PositionResource;
use Illuminate\Http\JsonResponse;

final class PositionController extends BaseApiController
{
    public function list(): JsonResponse
    {
        $positions = Position::all();

        return $this->successResponse([
            'positions' => PositionResource::collection($positions),
        ]);
    }
}
