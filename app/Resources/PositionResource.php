<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class PositionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
        ];
    }
}
