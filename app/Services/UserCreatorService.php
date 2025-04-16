<?php

namespace App\Services;

use App\Models\User;

final readonly class UserCreatorService
{
    public function emailOrPhoneExists(string $email, string $phone): bool
    {
        return User::where('email', $email)->orWhere('phone', $phone)->exists();
    }

    public function create(array $data, string $photoPath): User
    {
        return User::create([
            ...$data,
            'photo' => $photoPath,
        ]);
    }
}

