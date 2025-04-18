<?php

namespace App\Interfaces;

use App\Dto\GetUserListDto;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getUserList(GetUserListDto $dto): LengthAwarePaginator;
    public function getUserById(int $id): User;

    public function emailOrPhoneExists(string $email, string $phone): bool;

    public function createUser(array $data, string $photoPath): User;
}
