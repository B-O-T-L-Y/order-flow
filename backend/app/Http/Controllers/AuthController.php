<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'message' => 'User registered successfully.',
            'code' => 'USER_REGISTERED_SUCCESS',
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json([
                'error' => [
                    'message' => 'Invalid credentials provided.',
                    'code' => 'INVALID_CREDENTIALS',
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = $request->user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
            'message' => 'User logged in successfully.',
            'code' => 'USER_LOGGED_IN_SUCCESS',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'error' => [
                    'message' => 'Unauthenticated.',
                    'code' => 'UNAUTHENTICATED'
                ]
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'user' => $request->user(),
            'message' => 'User retrieved successfully.',
            'code' => 'USER_FETCHED_SUCCESS',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $success = [
            'message' => 'User logged out successfully.',
            'code' => 'USER_LOGGED_OUT_SUCCESS',
        ];

        if ($request->bearerToken()) {
            $request->user()->currentAccessToken()->delete();

            return response()->json($success);
        }

        if ($request->session()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json($success);
        }

        return response()->json([
            'message' => 'No user or token found to logout',
            'code' => 'LOGOUT_FAILURE',
        ]);
    }
}
