<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOfferResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'salary_min'    => $this->salary_min,
            'salary_max'    => $this->salary_max,
            'location'      => $this->location,
            'contract_type' => $this->contract_type->value, // Enum → string
            'status'        => $this->status,
            'created_at'    => $this->created_at->toDateTimeString(),
            // Relations conditionnelles (apparaissent seulement si chargées)
            'company'       => new CompanyResource($this->whenLoaded('company')),
            'skills'        => SkillResource::collection($this->whenLoaded('skills')),
        ];
    }
}
