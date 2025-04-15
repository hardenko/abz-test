<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PositionController;

Route::get('positions', [PositionController::class, 'getPositionList']);
