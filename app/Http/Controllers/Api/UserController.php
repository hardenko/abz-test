<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Request\CreateUserRequest;
use App\Http\Request\UserListRequest;
use App\Dto\GetUserListDto;
use App\Resources\UserByIdResource;
use App\Resources\UserListResource;
use App\Services\PhotoStorageService;
use App\Services\TokenService;
use App\Services\UserService;
use App\Support\ApiResponseBuilder;
use Illuminate\Http\JsonResponse;

final class UserController extends BaseApiController
{
    public function __construct(
        private readonly UserService         $userService,
        private readonly PhotoStorageService $photoService,
        private readonly TokenService        $tokenService,
    ){}

    public function getUserList(UserListRequest $request): JsonResponse
    {
        $dto = GetUserListDto::fromArray($request->validated());
        $users = $this->userService->getUserList($dto);

        return ApiResponseBuilder::paginated(UserListResource::collection($users), 'users');
    }

    public function getUserById(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        return $this->successResponse(['user' => new UserByIdResource($user)]);
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$this->tokenService->validateOnce($token)) {
            return $this->failResponse('The token expired.', 401);
        }

        $validated = $request->validated();

        if ($this->userService->emailOrPhoneExists($validated['email'], $validated['phone'])) {
            return $this->failResponse('User with this phone or email already exist', 409);
        }

        $photoPath = $this->photoService->store($request->file('photo'));
        $user = $this->userService->createUser($validated, $photoPath);

        return $this->successResponse([
            'user_id' => $user->id,
            'message' => 'New user successfully registered',
        ], 201);
    }
}
