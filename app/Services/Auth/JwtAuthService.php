<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class JwtAuthService
{
    public function __construct(
        private readonly JwtTokenService $tokenService,
    ) {
    }

    public function login(string $username, string $password): array
    {
        $user = User::query()->where('username', $username)->first();
        if (! $user instanceof User || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Credenciales inválidas.'],
            ]);
        }

        return $this->tokenService->issueToken($user);
    }
}

