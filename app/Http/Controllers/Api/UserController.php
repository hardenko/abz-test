<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Request\CreateUserRequest;
use App\Http\Request\UserListRequest;
use App\Interfaces\UserListServiceInterface;
use App\Services\TokenValidatorService;
use App\Services\PhotoStorageService;
use App\Services\UserCreatorService;
use App\Dto\GetUserListDto;
use App\Resources\UserByIdResource;
use App\Resources\UserListResource;
use App\Support\ApiResponseBuilder;
use Illuminate\Http\JsonResponse;

class UserController extends BaseApiController
{
    public function __construct(
        private readonly UserListServiceInterface $service,
        private readonly TokenValidatorService $tokenService,
        private readonly PhotoStorageService $photoService,
        private readonly UserCreatorService $userService
    ) {}

    public function getUserList(UserListRequest $request): JsonResponse
    {
        $response = $this->service->getUserList(GetUserListDto::fromArray($request->all()));

        return ApiResponseBuilder::paginated(UserListResource::collection($response), 'users');
    }

    public function getUserById(int $id): JsonResponse
    {
        $user = $this->service->getUserById($id);

        return $this->successResponse([
            'user' => new UserByIdResource($user)
        ]);
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (! $this->tokenService->validateOnce($token)) {
            return $this->failResponse('The token expired.', 401);
        }

        $validated = $request->validated();

        if ($this->userService->emailOrPhoneExists($validated['email'], $validated['phone'])) {
            return $this->failResponse('User with this phone or email already exist', 409);
        }

        $photoPath = $this->photoService->store($request->file('photo'));

        $user = $this->userService->create($validated, $photoPath);

        return $this->successResponse([
            'user_id' => $user->id,
            'message' => 'New user successfully registered',
        ], 201);
    }
}
