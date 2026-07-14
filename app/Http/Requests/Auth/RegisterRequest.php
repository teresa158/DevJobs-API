<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * authorize() → qui a le droit d'utiliser cette requête ?
     * true = tout le monde (l'inscription est publique)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * rules() → les règles de validation pour chaque champ.
     * Si un champ ne respecte pas sa règle → Laravel retourne 422 automatiquement.
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            // required   → obligatoire
            // string     → doit être une chaîne de caractères
            // max:255    → maximum 255 caractères

            'email'    => 'required|email|unique:users,email',
            // email      → doit être un email valide (format xxx@xxx.xx)
            // unique:users,email → l'email ne doit pas déjà exister dans la table users

            'password' => 'required|string|min:8|confirmed',
            // min:8      → minimum 8 caractères
            // confirmed  → le body doit aussi contenir "password_confirmation" avec la même valeur

            'role'     => 'required|in:candidate,company',
            // in:candidate,company → seules ces 2 valeurs sont acceptées
            // PAS 'admin' → on ne peut pas s'inscrire comme admin (sécurité)
        ];
    }
}
