<?php

use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\ValidateApiToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PositionController;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'list']);
    Route::get('/{id}', [UserController::class, 'user']);
    Route::post('/', [UserController::class, 'create'])->middleware(ValidateApiToken::class);
});

Route::get('positions', [PositionController::class, 'list']);

Route::get('token', [TokenController::class, 'generate']);


