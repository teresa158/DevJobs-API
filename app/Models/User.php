<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * $fillable : colonnes autorisées à l'assignation de masse (mass assignment).
     * SÉCURITÉ : sans fillable, User::create([...]) ne fonctionnerait pas.
     * Ne jamais mettre 'role' ici → on le définit manuellement lors de l'inscription.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * $hidden : colonnes jamais exposées dans les réponses JSON.
     * Même si on fait return $user, ces champs sont invisibles.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * $casts : convertit automatiquement les types de données.
     * 'role' → UserRole : transforme la string 'candidate' en Enum UserRole::Candidate
     * 'password' → 'hashed' : Laravel hache automatiquement le mot de passe à l'assignation
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
        ];
    }

    // =====================
    // RELATIONS
    // =====================

    /**
     * Un User possède au plus UN profil Candidate (One-to-One).
     * Utilisé quand role = 'candidate'.
     */
    public function candidate(): HasOne
    {
        return $this->hasOne(Candidate::class);
    }

    /**
     * Un User possède au plus UN profil Company (One-to-One).
     * Utilisé quand role = 'company'.
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    // =====================
    // HELPERS DE RÔLE
    // Ces méthodes simplifient les vérifications dans les Policies et Controllers
    // =====================

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isCandidate(): bool
    {
        return $this->role === UserRole::Candidate;
    }

    public function isCompany(): bool
    {
        return $this->role === UserRole::Company;
    }
}
