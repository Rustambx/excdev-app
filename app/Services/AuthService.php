<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return false;
        }

        $user = Auth::user();

        $token = $user->createToken('main')->plainTextToken;

        return ['token' => $token];
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();
    }
}
