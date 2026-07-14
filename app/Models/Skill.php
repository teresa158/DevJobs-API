<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // =====================
    // RELATIONS
    // =====================

    /**
     * Une Skill appartient à plusieurs JobOffers (Many-to-Many).
     * Table pivot : job_offer_skill
     * Permet de faire : $skill->jobOffers
     */
    public function jobOffers(): BelongsToMany
    {
        return $this->belongsToMany(JobOffer::class);
    }

    /**
     * Une Skill est maîtrisée par plusieurs Candidates (Many-to-Many).
     * Table pivot : candidate_skill
     * Permet de faire : $skill->candidates
     */
    public function candidates(): BelongsToMany
    {
        return $this->belongsToMany(Candidate::class);
    }
}
