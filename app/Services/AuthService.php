<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Inscription d'un nouvel utilisateur.
     * 1. Crée le User
     * 2. Crée le profil associé (Candidate OU Company) selon le rôle
     * 3. Génère un token Sanctum
     */
    public function register(array $data): array
    {
        // 1. Créer le User
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],  // hashé automatiquement grâce au cast 'hashed'
            'role'     => $data['role'],
        ]);

        // 2. Créer le profil selon le rôle
        if ($user->role === UserRole::Candidate) {
            Candidate::create(['user_id' => $user->id]);
        } elseif ($user->role === UserRole::Company) {
            Company::create([
                'user_id'      => $user->id,
                'company_name' => $data['name'], // nom temporaire, modifiable après
            ]);
        }

        // 3. Créer un token Sanctum
        // createToken('nom_du_token') → génère un token unique stocké dans personal_access_tokens
        $token = $user->createToken('auth_token')->plainTextToken;
        // plainTextToken → la version lisible du token (affichée une seule fois)

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Connexion d'un utilisateur existant.
     * 1. Vérifie les identifiants
     * 2. Génère un nouveau token
     */
    public function login(array $data): array
    {
        // Auth::attempt vérifie email + password (compare le hash)
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            // Si échec → on lance une exception que Laravel transforme en 422
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        // Récupérer le user authentifié
        $user = Auth::user();

        // Créer un nouveau token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Déconnexion : supprime le token actuel.
     * Le user ne pourra plus utiliser ce token pour faire des requêtes.
     */
    public function logout(User $user): void
    {
        // currentAccessToken() → le token utilisé pour cette requête
        // delete() → le supprime de la BDD → il devient invalide
        $user->currentAccessToken()->delete();
    }
}
