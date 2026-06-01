<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CaseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ChallanController;
use App\Http\Controllers\Api\WalletController;


Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    // Route::post('login', [AuthController::class, 'login']);
    Route::post('getconnectionId',[AuthController::class,'getConnectionId']);
    Route::post('requestOtp',[AuthController::class,'requestOtp']);
    Route::post('verifyOtp',[AuthController::class,'verifyOtp']);
});


Route::middleware('verify.user')->prefix('app')->group(function () {
    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    // User Profile
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::put('update', [UserController::class, 'updateProfile']);
        Route::put('change-password', [UserController::class, 'changePassword']);
    });

    // Cases
    Route::group(['prefix' => 'cases'], function () {
        Route::get('/', [CaseController::class, 'index']);
        Route::post('/', [CaseController::class, 'store']);
    });

    // Challans
    Route::group(['prefix' => 'challans'], function () {
        Route::get('/', [ChallanController::class, 'index']);
    });

    // Wallet & Transactions
    Route::group(['prefix' => 'wallet'], function () {
        Route::get('/', [WalletController::class, 'index']);
        Route::post('withdraw', [WalletController::class, 'store']);
    });
});