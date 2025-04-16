<?php

namespace App\Interfaces;

use App\Dto\GetPositionListDto;
use Illuminate\Support\Collection;

interface PositionListServiceInterface
{
    public function getPositionList(GetPositionListDto $dto): Collection;
}
