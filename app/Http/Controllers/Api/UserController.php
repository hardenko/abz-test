<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Request\CreateUserRequest;
use App\Http\Request\UserListRequest;
use App\Dto\GetUserListDto;
use App\Interfaces\UserServiceInterface;
use App\Resources\UserCollection;
use App\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

final class UserController extends BaseApiController
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    ){}

    public function list(UserListRequest $request): UserCollection
    {
        $dto = GetUserListDto::fromArray($request->validated());
        $users = $this->userService->list($dto);

        return new UserCollection($users);
    }

    public function user(string $id): JsonResponse
    {
        if (!ctype_digit($id)) {
            return $this->failResponse(
                'The user with the requested id does not exist.',
                400,
                ['userId' => ['The user ID must be an integer.']]
            );
        }

        try {
            $user = $this->userService->user((int) $id);

            return $this->successResponse([
                'user' => new UserResource($user)
            ]);
        } catch (ModelNotFoundException) {
            return $this->failResponse('User not found', 404);
        }

//        $user = $this->userService->user($id);
//
//        return $this->successResponse(['user' => new UserResource($user)]);
    }

    public function create(CreateUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->create($request->toDto());

            return $this->successResponse([
                'user_id' => $user->id,
                'message' => 'New user successfully registered',
            ], 201);
        } catch (QueryException $e) {
            if ($e->getCode() === '23505') {
                return $this->failResponse('User with this phone or email already exist', 409);
            }

            throw $e;
        }
    }
}
