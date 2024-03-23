<?php

use App\Http\Controllers\Api\V1\RegisterUserController;
use App\Http\Controllers\Api\V1\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
Route::post('register', RegisterUserController::class);
Route::get('email/verify/{id}/{hash}', VerifyEmailController::class)->name('verification.verify');
