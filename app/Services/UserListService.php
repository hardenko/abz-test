<?php

namespace App\Services;

use App\Dto\GetUserListDto;
use App\Interfaces\UserListServiceInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UserListService implements UserListServiceInterface
{
    public function getUserList(GetUserListDto $dto): LengthAwarePaginator
    {
        return User::with('positionRelation')->paginate($dto->count, ['*'], 'page', $dto->page);
    }

    public function getUserById(int $id): User
    {
        return User::with('positionRelation')->findOrFail($id);
    }
}
