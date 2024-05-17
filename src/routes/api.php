<?php

use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\GuestController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('v1/guest/registration-data', [GuestController::class, 'getRegistrationData']);

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('countries.modules', ModuleController::class)->shallow();
    Route::apiResource('modules.courses', CourseController::class)->shallow();
    Route::apiResource('courses.students', StudentController::class)->shallow()->only(['index', 'show']);
    Route::group(['prefix' => 'profiles', 'controller' => ProfileController::class], function () {
        Route::get('{user}', 'get');
        Route::put('{user}', 'update');
        Route::patch('{user}/change-password', 'changePassword');
    });
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::controller(StudentController::class)->group(function () {
        Route::get('students/{student}/bing');
    });
});


Route::get('foo', \App\Http\Controllers\Api\V1\TestController::class);
