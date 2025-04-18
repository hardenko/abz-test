<?php

namespace App\Services;

use App\Dto\GetPositionListDto;
use App\Interfaces\PositionListServiceInterface;
use App\Models\Position;
use Illuminate\Support\Collection;

final class PositionListService implements PositionListServiceInterface
{
    public function list(GetPositionListDto $dto): Collection
    {
        return Position::all();
    }
}
