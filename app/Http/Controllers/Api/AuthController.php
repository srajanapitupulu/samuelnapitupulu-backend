<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


/**
 * AuthController
 *
 * Handles user authentication operations including registration, login, and logout.
 * Provides API endpoints for user authentication using Laravel Sanctum tokens.
 * 
 * @package App\Http\Controllers\Api
 */
class AuthController extends ApiController
{
    /**
     * Register a new user and issue an API token.
     *
     * @param RegisterRequest $request The validated registration request containing name, email, and password
     * @return \Illuminate\Http\JsonResponse JSON response with status 201 containing the authentication token and user data
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success(
            [
                'token' => $token,
                'user' => $user,
            ],
            'You have registered successfully',
            201
        );
    }

    /**
     * Authenticate a user and issue an API token.
     *
     * @param LoginRequest $request The validated login request containing email and password
     * @return \Illuminate\Http\JsonResponse JSON response containing the authentication token and user data
     * @throws \Illuminate\Validation\ValidationException If credentials are invalid
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success(
            [
                'token' => $token,
                'user' => $user,
            ],
            'You have logged in successfully'
        );
    }

    /**
     * Logout the authenticated user by deleting their current access token.
     *
     * @return \Illuminate\Http\JsonResponse JSON response confirming successful logout
     */
    public function logout()
    {
        $user = request()->user();

        $user->tokens()
            ->where('id', $user->currentAccessToken()->id)
            ->delete();

        return $this->success(
            null,
            'You are now logged out'
        );
    }

    public function logout_from_all_devices()
    {
        request()->user()->tokens()->delete();

        return $this->success(
            null,
            'You are now logged out from all devices'
        );
    }

    public function me()
    {
        return response()->json(request()->user());
    }
}
