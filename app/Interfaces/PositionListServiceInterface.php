<?php

namespace App\Interfaces;

use App\Dto\GetPositionListDto;
use Illuminate\Support\Collection;

interface PositionListServiceInterface
{
    public function list(GetPositionListDto $dto): Collection;
}
