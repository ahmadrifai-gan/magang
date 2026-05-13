<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:employee,admin',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    /**
     * Login user with email and password
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ], 200);
    }

    /**
     * Get current user
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ], 200);
    }

    /**
     * Refresh authentication token
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Delete old token
        $request->user()->currentAccessToken()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }

    /**
     * Login user with Google OAuth token (API)
     */
    public function googleLogin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($validated['access_token']);

            $email = $googleUser->getEmail();
            if (!$email) {
                return response()->json([
                    'message' => 'Google account tidak memiliki email.',
                ], 422);
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?: $email,
                    'email' => $email,
                    'password' => Hash::make(uniqid('google_', true)),
                    'role' => 'employee',
                    'oauth_id' => $googleUser->getId(),
                    'oauth_provider' => 'google',
                    'email_verified_at' => now(),
                ]);

                $user->leaveBalances()->create([
                    'year' => now()->year,
                    'total_quota' => 12,
                    'used_quota' => 0,
                    'remaining_quota' => 12,
                ]);
            } else {
                $user->update([
                    'oauth_id' => $user->oauth_id ?: $googleUser->getId(),
                    'oauth_provider' => $user->oauth_provider ?: 'google',
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login dengan Google berhasil',
                'user' => new UserResource($user),
                'token' => $token,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Login Google gagal',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
