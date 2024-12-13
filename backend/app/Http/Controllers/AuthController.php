<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        /** @var User $request */
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
            ],
            'message' => 'User registered successfully.',
            'code' => 'USER_REGISTERED_SUCCESS',
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        /** @var User $request */
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => [
                    'message' => 'Invalid credentials provided.',
                    'code' => 'INVALID_CREDENTIALS',
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
            ],
            'message' => 'User logged in successfully.',
            'code' => 'USER_LOGGED_IN_SUCCESS',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
            'message' => 'User retrieved successfully.',
            'code' => 'USER_FETCHED_SUCCESS',
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->guard('web')->logout();

        return response()->json([
            'message' => 'User logged out successfully.',
            'code' => 'USER_LOGGED_OUT_SUCCESS',
        ]);
    }
}
