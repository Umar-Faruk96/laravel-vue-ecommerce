<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request): Response
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean'],
        ]);

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'message' => 'Email or password is incorrect',
            ], 422);
        };

        $user = Auth::user();

        if (!$user->is_admin) {
            Auth::logout();
            return response([
                'message' => 'You don\'t have permission to access this resource',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function getUser(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function logout(): Response
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response('', 204);
    }
}
