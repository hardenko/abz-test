<?php

namespace App\Services;

use App\Dto\GetPositionListDto;
use App\Interfaces\PositionListServiceInterface;
use App\Models\Position;
use Illuminate\Pagination\LengthAwarePaginator;

final class PositionListService extends SearchQueryService implements PositionListServiceInterface
{
    private const ITEMS_PER_PAGE = 20;

    public function getPositionList(GetPositionListDto $dto): LengthAwarePaginator
    {
        $query = Position::query();

        $this->applyWhere(query: $query, field: 'name', value: $dto->name, operator: 'LIKE', wildcard: true);

        return $query->paginate(self::ITEMS_PER_PAGE);
    }
}
