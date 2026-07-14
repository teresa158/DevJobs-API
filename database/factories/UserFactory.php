<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Le mot de passe par défaut pour tous les users de test.
     * On utilise une variable statique pour ne hasher qu'une seule fois (performance).
     */
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            // "password" sera le mot de passe de TOUS les users de test
            'role'              => UserRole::Candidate->value,
            // Par défaut candidate, on override dans les états ci-dessous
            'remember_token'    => Str::random(10),
        ];
    }

    // =====================
    // ÉTATS (States)
    // Permettent de créer des users avec un rôle spécifique
    // Exemple : User::factory()->candidate()->create()
    // =====================

    public function candidate(): static
    {
        return $this->state(['role' => UserRole::Candidate->value]);
    }

    public function company(): static
    {
        return $this->state(['role' => UserRole::Company->value]);
    }

    public function admin(): static
    {
        return $this->state(['role' => UserRole::Admin->value]);
    }

    /**
     * Email non vérifié (pour tester ce cas)
     */
    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}
