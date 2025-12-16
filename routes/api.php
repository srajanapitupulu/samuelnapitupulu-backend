<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logoutall', [AuthController::class, 'logout_from_all_devices']);

        Route::get('/profile', [AuthController::class, 'me']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });

});