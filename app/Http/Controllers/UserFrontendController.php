<?php

namespace App\Http\Controllers;

use App\Dto\GetPositionListDto;
use App\Dto\GetUserListDto;
use App\Interfaces\PositionListServiceInterface;
use App\Interfaces\UserListServiceInterface;
use App\Resources\UserListResource;
use App\Services\PhotoStorageService;
use App\Services\TokenService;
use App\Services\TokenValidatorService;
use App\Services\UserCreatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class UserFrontendController extends Controller
{
    public function __construct(
        private readonly UserListServiceInterface     $userService,
        private readonly PositionListServiceInterface $positionService,
        private readonly TokenService                 $tokenService,
        private readonly TokenValidatorService        $tokenValidatorService,
        private readonly PhotoStorageService          $photoService,
        private readonly UserCreatorService           $creatorService,
    )
    {
    }

    public function getUserList(Request $request): View
    {
        $dto = new GetUserListDto(
            page: (int)$request->input('page', 1),
            count: 6
        );

        $users = $this->userService->getUserList($dto);
        $positions = $this->positionService->getPositionList(GetPositionListDto::fromArray([]));

        $token = $this->tokenService->generateToken();

        return view('users', [
            'users' => UserListResource::collection($users)->resolve(),
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

    public function createUser(Request $request): RedirectResponse
    {
        $token = $request->input('token');

        if (!$this->tokenValidatorService->validateOnce($token)) {
            return back()->withErrors(['token' => 'The token expired.'])->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'regex:/^\\+380\\d{9}$/'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg', 'max:5120'],
        ]);

        if ($this->creatorService->emailOrPhoneExists($validated['email'], $validated['phone'])) {
            return back()->withErrors([
                'phone' => 'User with this phone or email already exist',
            ])->withInput();
        }

        $photoPath = $this->photoService->store($request->file('photo'));
        $user = $this->creatorService->create($validated, $photoPath);

        return redirect('/')->with('success', 'New user successfully registered');
    }
}
