<?php

use App\Http\Controllers\Api\V1\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/payment', 'controller' => PaymentController::class], function () {
    Route::post('{purchasableType}/{purchasableId}/checkout', 'checkout');
    Route::get('status', 'status')->name('payment.status');
    Route::get('cancel', 'cancel')->name('payment.cancel');
    Route::post('/transfer','transferToUser');
});
