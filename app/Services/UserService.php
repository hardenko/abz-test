<?php

namespace App\Services;

use App\Dto\CreateUserDto;
use App\Dto\GetUserListDto;
use App\Exceptions\PageNotFoundException;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class UserService implements UserServiceInterface
{
    public function __construct(
        private ImageService $imageService,
    ) {}

    public function list(GetUserListDto $dto): LengthAwarePaginator
    {
        $users = User::with('positions')
            ->orderByDesc('created_at')
            ->paginate($dto->count, ['*'], 'page', $dto->page);

        if ($dto->page > $users->lastPage()) {
            throw new PageNotFoundException;
        }

        return $users;
    }

    public function getById(int $id): User
    {
        return User::with('positions')->findOrFail($id);
    }

    public function create(CreateUserDto $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'position_id' => $dto->position_id,
            'photo' => $this->imageService->store($dto->photo),
        ]);
    }
}
