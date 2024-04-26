<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RegisterUserController;
use App\Http\Controllers\Api\V1\VerifyEmailController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'v1/auth', 'middleware' => 'api'], function () {
    Route::controller(AuthController::class)->group(function (){
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware(['auth:api']);
        Route::get('send-password-reset-code', 'sendCode');
        Route::post('verify-password-reset-code', 'verifyCode');
        Route::post('reset-password', 'resetPassword');
    });

    Route::post('register', RegisterUserController::class);
    Route::get('email/verify/{id}/{hash}', VerifyEmailController::class)->name('email-verification');
});


