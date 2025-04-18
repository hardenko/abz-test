<?php

namespace App\Services;

use App\Dto\GetUserListDto;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UserService implements UserServiceInterface
{
    public function getUserList(GetUserListDto $dto): LengthAwarePaginator
    {
        return User::with('positionRelation')->paginate($dto->count, ['*'], 'page', $dto->page);
    }

    public function getUserById(int $id): User
    {
        return User::with('positionRelation')->findOrFail($id);
    }

    public function emailOrPhoneExists(string $email, string $phone): bool
    {
        return User::where('email', $email)->orWhere('phone', $phone)->exists();
    }

    public function createUser(array $data, string $photoPath): User
    {
        return User::create([
            ...$data,
            'photo' => $photoPath,
        ]);
    }
}
