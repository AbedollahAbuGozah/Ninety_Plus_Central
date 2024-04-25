<?php

use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('countries.modules', ModuleController::class)->shallow();
    Route::apiResource('students', StudentController::class);
    Route::apiResource('modules/courses', CourseController::class)->shallow();
});
