<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class UserByIdResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position_id' => (string) $this->position_id,
            'position' => $this->positionRelation->name,
            'photo' => $this->photo,
        ];
    }
}
