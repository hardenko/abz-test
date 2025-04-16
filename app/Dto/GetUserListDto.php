<?php

namespace App\Dto;

final class GetUserListDto extends BaseDto
{
    public function __construct(
        public int $page,
        public int $count,
    ) {}

    public static function fromArray(array $params): self
    {
        return new self(
            page: (int) ($params['page'] ?? 1),
            count: (int) ($params['count'] ?? 5),
        );
    }
}
