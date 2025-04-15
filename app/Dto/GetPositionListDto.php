<?php

namespace App\Dto;

final class GetPositionListDto extends BaseDto
{
    public function __construct(
        public ?string $name,
    ){}

    public static function fromArray(array $params): self
    {
        return new self(
            name: $params['name'] ?? null,
        );
    }
}
