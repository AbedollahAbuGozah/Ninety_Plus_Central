<?php

use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\ChapterController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\PackageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'/*, 'verified'*/], function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('countries.modules', ModuleController::class)->shallow();
    Route::apiResource('cities', CityController::class);
    Route::apiResource('chapters', ChapterController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('packages', PackageController::class);
});
