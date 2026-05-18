<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\Auth\JwtTokenService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class JwtMiddleware
{
    public function __construct(
        private readonly JwtTokenService $tokenService,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('OPTIONS')) {
            return response()->noContent();
        }

        $authorization = (string) $request->header('Authorization', '');
        if (! str_starts_with($authorization, 'Bearer ')) {
            return ApiResponse::error('No autenticado.', 401);
        }

        $token = trim(substr($authorization, 7));
        if ($token === '') {
            return ApiResponse::error('No autenticado.', 401);
        }

        try {
            $userId = $this->tokenService->userIdFromToken($token);
        } catch (RuntimeException) {
            return ApiResponse::error('No autenticado.', 401);
        }

        $user = User::query()->find($userId);
        if (! $user instanceof User) {
            return ApiResponse::error('No autenticado.', 401);
        }

        Auth::setUser($user);
        $request->setUserResolver(static fn () => $user);

        return $next($request);
    }
}

