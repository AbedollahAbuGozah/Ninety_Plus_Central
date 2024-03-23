<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth', 'middleware' => 'api', 'controller' => AuthController::class], function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::get('send-password-reset-code/{email}', 'sendCode');
    Route::get('verify-password-reset-code', 'verifyCode');
});
