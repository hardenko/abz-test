<?php

namespace App\Interfaces;

use App\Dto\GetUserListDto;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserListServiceInterface
{
    public function getUserList(GetUserListDto $dto): LengthAwarePaginator;
    public function getUserById(int $id): User;
}
