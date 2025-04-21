<?php

namespace App\Http\Controllers;

use App\Dto\GetUserListDto;
use App\Http\Request\User\CreateUserRequest;
use App\Models\Position;
use App\Resources\PositionCollection;
use App\Services\TokenService;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class UserFrontendController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly TokenService $tokenService,
    ) {}

    public function list(Request $request): View
    {
        $dto = new GetUserListDto(
            page: (int) $request->input('page', 1),
            count: 6
        );

        $users = $this->userService->list($dto);
        $positions = new PositionCollection(Position::all());
        $token = $this->tokenService->generate();

        return view('users', [
            'users' => $users->items(),
            'positions' => $positions->resolve(),
            'pagination' => [
                'page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'next_url' => $users->nextPageUrl(),
                'prev_url' => $users->previousPageUrl(),
            ],
            'token' => $token,
        ]);
    }

    public function create(CreateUserRequest $request): RedirectResponse
    {
        $token = $request->input('token');

        if (! $this->tokenService->validateOnce($token)) {
            return back()->withErrors([
                'token' => 'The token expired.',
            ])->withInput();
        }

        try {
            $this->userService->create($request->toDto());
        } catch (QueryException $e) {
            if ($e->getCode() === '23505') {
                return back()->withErrors([
                    'phone' => 'User with this phone or email already exist',
                ])->withInput();
            }

            throw $e;
        }

        return redirect('/')->with('success', 'New user successfully registered');
    }
}
