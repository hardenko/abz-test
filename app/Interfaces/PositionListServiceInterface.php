<?php

namespace App\Interfaces;

use App\Dto\GetPositionListDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface PositionListServiceInterface
{
    public function getPositionList(GetPositionListDto $dto): LengthAwarePaginator;
}
