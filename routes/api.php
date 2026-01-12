<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;

Route::get('/translations', function () {
    return trans()->get('*');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    //wallet
    Route::get('/wallet/create-aggreement', [WalletController::class, 'createAgreement']);
    Route::post('/wallet/execute-aggreement', [WalletController::class, 'executeAgreement']);
    //payment
    Route::post('/payment/payment-with-agreement', [WalletController::class, 'createPayment']);
    Route::post('/payment/status-payment', [WalletController::class, 'statusPayment']);
    //refund
    Route::post('/refund', [WalletController::class, 'createRefund']);
});

