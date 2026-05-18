<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\JwtAuthService;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Autenticación',
    description: 'Endpoints para autenticación de usuarios'
)]
final class AuthApiController extends Controller
{
    public function __construct(
        private readonly JwtAuthService $authService,
    ) {
    }

    #[OA\Post(
        path: '/api/auth/login',
        operationId: 'login',
        tags: ['Autenticación'],
        summary: 'Iniciar sesión',
        description: 'Autentica a un usuario y devuelve un token JWT'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest')
    )]
    #[OA\Response(
        response: 200,
        description: 'Login exitoso',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'data', ref: '#/components/schemas/LoginResponse')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'Credenciales inválidas')]
    #[OA\Response(response: 422, description: 'Datos de entrada inválidos')]
    public function login(LoginRequest $request)
    {
        $token = $this->authService->login(
            $request->validated('username'),
            $request->validated('password'),
        );

        return ApiResponse::success($token);
    }
}

