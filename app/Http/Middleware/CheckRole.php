<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Le middleware reçoit un ou plusieurs rôles autorisés.
     * Exemple d'utilisation dans les routes :
     *   ->middleware('role:company')         → seulement les entreprises
     *   ->middleware('role:company,admin')    → entreprises ET admins
     *
     * $roles est une chaîne : "company" ou "company,admin"
     * On la split avec explode pour obtenir un tableau.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // $request->user() = le user connecté (grâce à auth:sanctum avant)
        $user = $request->user();

        // Si le user n'est pas connecté (ne devrait pas arriver si auth:sanctum est avant)
        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié.',
            ], 401);
        }

        // Vérifie si le rôle du user est dans la liste des rôles autorisés
        // $user->role->value = la valeur string de l'Enum (ex: 'company')
        if (!in_array($user->role->value, $roles)) {
            return response()->json([
                'message' => 'Accès interdit. Rôle requis : ' . implode(' ou ', $roles),
            ], 403); // 403 = Forbidden
        }

        // Si tout est bon → on laisse passer la requête
        return $next($request);
    }
}
