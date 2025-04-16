<?php

use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PositionController;

Route::get('users', [UserController::class, 'getUserList']);
Route::get('user/{id}', [UserController::class, 'getUserById']);
Route::get('positions', [PositionController::class, 'getPositionList']);
Route::get('token', [TokenController::class, 'generateToken']);

Route::post('users', [UserController::class, 'createUser']);
