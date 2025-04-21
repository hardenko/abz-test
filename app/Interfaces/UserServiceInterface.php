<?php

namespace App\Interfaces;

use App\Dto\CreateUserDto;
use App\Dto\GetUserListDto;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function list(GetUserListDto $dto): LengthAwarePaginator;

    public function getById(int $id): User;

    public function create(CreateUserDto $dto): User;
}
