<?php

use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('countries.modules', ModuleController::class)->shallow();
Route::apiResource('cities', CityController::class);

