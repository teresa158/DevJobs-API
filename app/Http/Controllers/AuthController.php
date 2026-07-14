<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    // Injection de dépendances via le constructeur
    // Laravel instancie automatiquement AuthService et le passe ici
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * POST /api/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // $request->validated() → retourne UNIQUEMENT les champs validés (sécurité)
        $result = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'Inscription réussie.',
            'user'    => $result['user'],
            'token'   => $result['token'],
        ], 201); // 201 = Created
    }

    /**
     * POST /api/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return response()->json([
            'message' => 'Connexion réussie.',
            'user'    => $result['user'],
            'token'   => $result['token'],
        ], 200); // 200 = OK
    }

    /**
     * POST /api/logout (route protégée par auth:sanctum)
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout(auth()->user());

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ], 200);
    }
}
