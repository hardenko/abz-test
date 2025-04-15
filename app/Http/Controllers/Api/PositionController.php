<?php

namespace App\Http\Controllers\Api;

use App\Dto\GetPositionListDto;
use App\Http\Controllers\BaseApiController;
use App\Http\Request\PositionListRequest;
use App\Interfaces\PositionListServiceInterface;
use App\Resources\PositionListResource;
use Illuminate\Http\JsonResponse;

class PositionController extends BaseApiController
{
    public function __construct(private readonly PositionListServiceInterface $service){}

    public function getPositionList(PositionListRequest $request): JsonResponse
    {
        $response = $this->service->getPositionList(GetPositionListDto::fromArray($request->all()));

        return $this->response('successfully_got_position_list', PositionListResource::collection($response));
    }
}
