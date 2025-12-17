<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\HierarchyController;
use App\Http\Controllers\Api\TaskController;
use App\Models\Invitation;

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('invitations/accept/{token}', function ($token) {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (!$invitation->status === 'pending') {
            abort(403);
        }

        return response()->json([
            'message' => 'Valid invitation',
            'team' => $invitation->team->name,
        ]);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logoutall', [AuthController::class, 'logout_from_all_devices']);

        Route::get('/profile', [AuthController::class, 'me']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::apiResource('users', UserController::class)
            ->except(['edit', 'create']);

        Route::apiResource('teams', TeamController::class)
            ->except(['edit', 'create']);

        Route::get('me/hierarchy', [HierarchyController::class, 'show']);

        Route::apiResource('tasks', TaskController::class)
            ->only(['index', 'store', 'update']);
    });

});