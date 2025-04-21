<?php

namespace App\Http\Controllers\Api;

use App\Dto\GetUserListDto;
use App\Http\Controllers\BaseApiController;
use App\Http\Request\User\CreateUserRequest;
use App\Http\Request\User\GetUserRequest;
use App\Http\Request\User\UserListRequest;
use App\Interfaces\UserServiceInterface;
use App\Resources\UserCollection;
use App\Resources\UserResource;
use Illuminate\Http\JsonResponse;

final class UserController extends BaseApiController
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    )
    {
    }

    public function list(UserListRequest $request): UserCollection
    {
        return new UserCollection($this->userService->list(
            GetUserListDto::fromArray($request->validated()))
        );
    }

    public function getById(GetUserRequest $request): JsonResponse
    {
        return $this->successResponse([
            'user' => new UserResource($this->userService->getById($request->validated('id'))),
        ]);
    }

    public function create(CreateUserRequest $request): JsonResponse
    {
        return $this->successResponse([
            'user_id' => $this->userService->create($request->toDto())->id,
            'message' => 'New user successfully registered',
        ], 201);
    }
}
