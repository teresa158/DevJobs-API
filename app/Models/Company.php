<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'siret',
        'website',
        'description',
        'address',
        'city',
    ];

    // =====================
    // RELATIONS
    // =====================

    /**
     * Une Company appartient à un User (inverse du hasOne).
     * Permet de faire : $company->user->email
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Une Company possède plusieurs JobOffers (One-to-Many).
     * Permet de faire : $company->jobOffers
     */
    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }
}
