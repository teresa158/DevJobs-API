<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'cover_letter' => $this->cover_letter,
            'status'       => $this->status->value, // Enum → string
            'created_at'   => $this->created_at->toDateTimeString(),
            'candidate'    => new CandidateResource($this->whenLoaded('candidate')),
            'job_offer'    => new JobOfferResource($this->whenLoaded('jobOffer')),
        ];
    }
}
