<?php

namespace App\Models;

use App\Enums\ContractType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOffer extends Model
{
    use HasFactory, SoftDeletes;
    // SoftDeletes : active la gestion du champ deleted_at
    // Eloquent ajoutera automatiquement WHERE deleted_at IS NULL à toutes les requêtes

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'salary_min',
        'salary_max',
        'location',
        'contract_type',
        'status',
    ];

    /**
     * $casts : convertit contract_type string → Enum ContractType
     */
    protected function casts(): array
    {
        return [
            'contract_type' => ContractType::class,
            'salary_min'    => 'integer',
            'salary_max'    => 'integer',
        ];
    }

    // =====================
    // RELATIONS
    // =====================

    /**
     * Une JobOffer appartient à une Company (Many-to-One).
     * Permet de faire : $jobOffer->company->company_name
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Une JobOffer requiert plusieurs Skills (Many-to-Many).
     * Table pivot : job_offer_skill
     * Permet de faire : $jobOffer->skills
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    /**
     * Une JobOffer reçoit plusieurs Applications (One-to-Many).
     * Permet de faire : $jobOffer->applications
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // =====================
    // SCOPES
    // Un scope = filtre réutilisable sur les requêtes Eloquent
    // =====================

    /**
     * Scope : ne retourner que les offres ouvertes.
     * Utilisation : JobOffer::open()->get()
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope : filtrer par type de contrat.
     * Utilisation : JobOffer::ofType('CDI')->get()
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('contract_type', $type);
    }

    /**
     * Scope : filtrer par ville.
     * Utilisation : JobOffer::inCity('Paris')->get()
     */
    public function scopeInCity($query, string $city)
    {
        return $query->where('location', 'like', "%{$city}%");
    }
}
