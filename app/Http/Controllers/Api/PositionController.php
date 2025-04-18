<?php

namespace App\Http\Controllers\Api;

use App\Dto\GetPositionListDto;
use App\Http\Controllers\BaseApiController;
use App\Http\Request\PositionListRequest;
use App\Interfaces\PositionListServiceInterface;
use App\Resources\PositionListResource;
use Illuminate\Http\JsonResponse;

final class PositionController extends BaseApiController
{
    public function __construct(private readonly PositionListServiceInterface $service){}

    public function list(PositionListRequest $request): JsonResponse
    {
        $response = $this->service->list(GetPositionListDto::fromArray($request->all()));

        return $this->successResponse([
            'positions' => PositionListResource::collection($response)
        ]);
    }
}
