<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_offer_id',
        'cover_letter',
        'status',
    ];

    /**
     * $casts : convertit le statut string → Enum ApplicationStatus
     * Exemple : 'pending' devient automatiquement ApplicationStatus::Pending
     */
    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
        ];
    }

    // =====================
    // RELATIONS
    // =====================

    /**
     * Une Application appartient à un Candidate (Many-to-One).
     * Permet de faire : $application->candidate->user->name
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Une Application concerne une JobOffer (Many-to-One).
     * Permet de faire : $application->jobOffer->title
     */
    public function jobOffer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }

    // =====================
    // HELPERS DE STATUT
    // =====================

    public function isPending(): bool
    {
        return $this->status === ApplicationStatus::Pending;
    }

    public function isAccepted(): bool
    {
        return $this->status === ApplicationStatus::Accepted;
    }

    public function isRejected(): bool
    {
        return $this->status === ApplicationStatus::Rejected;
    }
}
