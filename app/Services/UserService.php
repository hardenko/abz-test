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

        if ($users->isEmpty() && $dto->page > $users->lastPage()) {
            throw new PageNotFoundException();
        }

        return $users;
    }

    public function user(int $id): User
    {
        return User::with('positions')->findOrFail($id);
    }

    public function emailOrPhoneExists(string $email, string $phone): bool
    {
        return User::where('email', $email)->orWhere('phone', $phone)->exists();
    }

    public function create(CreateUserDto $dto): User
    {
        $photoPath = $this->imageService->store($dto->photo);

        return User::create([
            ...$dto->toArray(),
            'photo' => $photoPath,
        ]);
    }
}
