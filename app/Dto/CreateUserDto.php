<?php

namespace App\Dto;

use Illuminate\Http\UploadedFile;

final class CreateUserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public int $position_id,
        public UploadedFile $photo,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            position_id: (int) $data['position_id'],
            photo: $data['photo'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position_id' => $this->position_id,
        ];
    }
}
