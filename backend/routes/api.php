<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\CommissionController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('auth/user', [AuthController::class, 'user']);

    Route::resource('sellers', SellerController::class)
		->only(['index', 'store', 'update', 'destroy']);

    Route::resource('sales', SaleController::class)
		->only(['index', 'store', 'update', 'destroy']);

    Route::get('sellers/{sellerId}/sales', [SaleController::class, 'bySeller']);
    Route::post('sellers/{sellerId}/send-commission-email', [CommissionController::class, 'sendCommissionEmail']);
});
