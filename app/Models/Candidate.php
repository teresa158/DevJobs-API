<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'github_url',
        'portfolio_url',
        'years_experience',
    ];

    // =====================
    // RELATIONS
    // =====================

    /**
     * Un Candidate appartient à un User (inverse du hasOne).
     * Permet de faire : $candidate->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un Candidate a plusieurs Applications (One-to-Many).
     * Permet de faire : $candidate->applications
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Un Candidate maîtrise plusieurs Skills (Many-to-Many).
     * Laravel cherche automatiquement la table pivot "candidate_skill".
     * Permet de faire : $candidate->skills
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }
}
