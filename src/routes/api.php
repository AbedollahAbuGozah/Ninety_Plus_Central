<?php

use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\GuestController;
use App\Http\Controllers\Api\V1\Markables\FavoriteController;
use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RateController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::group(['prefix' => 'v1/guest', 'controller' => GuestController::class], function () {
    Route::get('registration-data', 'getRegistrationData');
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'/*, 'verified'*/], function () {

    Route::apiResource('users', UserController::class);
    Route::apiResource('countries.modules', ModuleController::class)->shallow();
    Route::apiResource('modules.courses', CourseController::class)->shallow();
    Route::apiResource('courses.students', StudentController::class)->shallow()->only(['index', 'show']);

    Route::group(['prefix' => 'profiles', 'controller' => ProfileController::class], function () {
        Route::get('', 'show');
        Route::put('', 'update');
        Route::patch('change-password', 'changePassword');
    });

    Route::group(['prefix' => 'favorites/{favorableType}', 'controller' => FavoriteController::class], function () {
        Route::get('', 'index');

        Route::prefix('{favorableId}')->group(function () {
            Route::post('', 'markAsFavorite');
            Route::delete('', 'unMarkAsFavourite');
        });
    });

    Route::group(['prefix' => 'comments/{commentableType}/{commentableId}', 'controller' => CommentController::class], function () {
        Route::post('', 'store');
        Route::get('', 'index');
    });

    Route::group(['prefix' => 'rates/{ratableType}/{ratableId}', 'controller' => RateController::class], function () {
        Route::post('', 'store');
        Route::get('', 'index');
    });

    Route::apiResource('comments', CommentController::class)->except(['store', 'index']);
    Route::apiResource('rates', RateController::class)->except(['store', 'index']);
});

Route::post('foo', \App\Http\Controllers\Api\V1\TestController::class);
