<?php

namespace App\Dto;

final class GetUserListDto extends BaseDto
{
    public const DEFAULT_PAGE = 1;

    public const DEFAULT_COUNT = 5;

    public function __construct(
        public int $page,
        public int $count,
    ) {}

    public static function fromArray(array $params): self
    {
        return new self(
            page: (int) ($params['page'] ?? self::DEFAULT_PAGE),
            count: (int) ($params['count'] ?? self::DEFAULT_COUNT),
        );
    }
}
