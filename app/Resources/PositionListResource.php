<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class PositionListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
