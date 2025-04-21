<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->positions->name,
            'position_id' => $this->position_id,
            'registration_timestamp' => strtotime($this->created_at),
            'photo' => $this->photo,
        ];
    }
}
