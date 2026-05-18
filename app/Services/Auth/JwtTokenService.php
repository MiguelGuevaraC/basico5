<?php

namespace App\Services\Auth;

use App\Models\User;
use Carbon\CarbonImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;
use Throwable;

final class JwtTokenService
{
    private const ALGORITHM = 'HS256';

    public function issueToken(User $user): array
    {
        $secret = $this->secret();
        $now = CarbonImmutable::now();
        $expiresAt = $now->addMinutes($this->ttlMinutes());

        $payload = [
            'sub' => $user->getKey(),
            'iat' => $now->timestamp,
            'exp' => $expiresAt->timestamp,
        ];

        return [
            'access_token' => JWT::encode($payload, $secret, self::ALGORITHM),
            'token_type' => 'Bearer',
            'expires_in' => $expiresAt->diffInSeconds($now),
        ];
    }

    public function userIdFromToken(string $token): int
    {
        $secret = $this->secret();

        try {
            $decoded = JWT::decode($token, new Key($secret, self::ALGORITHM));
        } catch (Throwable $exception) {
            throw new RuntimeException('Token inválido.', 0, $exception);
        }

        $userId = $decoded->sub ?? null;
        if (! is_int($userId) && ! (is_string($userId) && ctype_digit($userId))) {
            throw new RuntimeException('Token inválido.');
        }

        return (int) $userId;
    }

    private function secret(): string
    {
        $secret = (string) env('JWT_SECRET', '');
        if ($secret === '') {
            throw new RuntimeException('JWT_SECRET no está configurado.');
        }

        return $secret;
    }

    private function ttlMinutes(): int
    {
        $ttl = (int) env('JWT_TTL_MINUTES', 120);

        return max(1, $ttl);
    }
}

