<?php

use App\Http\Controllers\Api\V1\BankAccountController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\GuestController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\LectureController;
use App\Http\Controllers\Api\V1\Markables\FavoriteController;
use App\Http\Controllers\Api\V1\MarkController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RateController;
use App\Http\Controllers\Api\V1\RequestMoneyController;
use App\Http\Controllers\Api\V1\StudentController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/payment.php';
require __DIR__ . '/admin.php';

Route::group(['prefix' => 'v1/guest', 'controller' => GuestController::class], function () {
    Route::get('registration-data', 'getRegistrationData');
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'/*, 'verified'*/], function () {
    Route::apiResource('modules.courses', CourseController::class)->shallow();
    Route::apiResource('lectures', LectureController::class);
    Route::apiResource('courses.students', StudentController::class)->shallow()->only(['index', 'show']);
    Route::get('users/{user}/invoices', [InvoiceController::class, 'index']);

    Route::group(['prefix' => 'profiles', 'controller' => ProfileController::class], function () {
        Route::get('', 'show');
        Route::put('', 'update');
        Route::patch('change-password', 'changePassword');
    });

    Route::group(['prefix' => 'favorites/{favorableType}', 'controller' => FavoriteController::class], function () {
        Route::get('', 'index');

        Route::prefix('{favorableId}')
            ->controller(FavoriteController::class)
            ->group(function () {
                Route::post('', 'markAsFavorite');
                Route::delete('', 'unMarkAsFavourite');
            });
    });

    Route::post('marks/{markType}/{markableType}/{markableId}', [MarkController::class, 'mark']);

    Route::group(['prefix' => 'marks/{markType}/{markableType}/', 'controller' => MarkController::class], function () {
        Route::get('', 'index');
        Route::post('{markableId}', 'mark');
        Route::delete('{markableId}', 'unMark');

    });

    Route::group(['prefix' => 'lectures/{lecture}', 'controller' => LectureController::class], function () {
        Route::post('start-live', 'startLiveLecture');
        Route::post('join-live', 'joinLiveLecture');
        Route::post('upload-record', 'uploadRecord');
    });

    Route::group(['prefix' => 'comments/{commentableType}/{commentableId}', 'controller' => CommentController::class], function () {
        Route::post('', 'store');
        Route::get('', 'index');
    });

    Route::group(['prefix' => 'rates/{ratableType}/{ratableId}', 'controller' => RateController::class], function () {
        Route::post('', 'store');
        Route::get('', 'index');
    });

    Route::group(['prefix' => 'money-requests', 'controller' => RequestMoneyController::class], function () {
        Route::post('', 'requestMoney');
        Route::get('', 'index');
    });


    Route::group(['prefix' => 'courses', 'controller' => CourseController::class], function () {
        Route::get('', 'indexAll');
        Route::patch('{course}/change-status', 'changeStatus');
    });

    Route::get('courses/invoices', [InvoiceController::class, 'index']);
    Route::post('bank-account', [BankAccountcontroller::class, 'store']);
    Route::apiResource('comments', CommentController::class)->except(['store', 'index']);
    Route::apiResource('rates', RateController::class)->except(['store', 'index']);
});


Route::post('foo', \App\Http\Controllers\Api\V1\TestController::class);
