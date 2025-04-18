<?php

namespace App\Http\Controllers;

use App\Dto\GetPositionListDto;
use App\Dto\GetUserListDto;
use App\Http\Request\CreateUserRequest;
use App\Interfaces\PositionListServiceInterface;
use App\Resources\UserResource;
use App\Services\ImageService;
use App\Services\TokenService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class UserFrontendController extends Controller
{
    public function __construct(
        private readonly UserService                  $userService,
        private readonly PositionListServiceInterface $positionService,
        private readonly TokenService                 $tokenService,
        private readonly ImageService                 $photoService,
    ){}

    public function getUserList(Request $request): View
    {
        $dto = new GetUserListDto(
            page: (int)$request->input('page', 1),
            count: 6
        );

        $users = $this->userService->list($dto);
        $positions = $this->positionService->list(GetPositionListDto::fromArray([]));
        $token = $this->tokenService->generate();

        return view('users', [
            'users' => UserResource::collection($users)->resolve(),
            'pagination' => [
                'page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'next_url' => $users->nextPageUrl(),
                'prev_url' => $users->previousPageUrl(),
            ],
            'positions' => collect($positions)->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
            ]),
            'token' => $token,
        ]);
    }

    public function createUser(CreateUserRequest $request): RedirectResponse
    {
        $token = $request->input('token');

        if (!$this->tokenService->validateOnce($token)) {
            return back()->withErrors(['token' => 'The token expired.'])->withInput();
        }

        $validated = $request->validated();

        if ($this->userService->emailOrPhoneExists($validated['email'], $validated['phone'])) {
            return back()->withErrors([
                'phone' => 'User with this phone or email already exist',
            ])->withInput();
        }

        $this->userService->create($validated, $this->photoService->store($request->file('photo')));

        return redirect('/')->with('success', 'New user successfully registered');
    }
}
