<?php

use App\Http\Controllers\UserFrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserFrontendController::class, 'getUserList']);

Route::post('/create-user', [UserFrontendController::class, 'createUser']);
