<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'bio'              => $this->bio,
            'github_url'       => $this->github_url,
            'portfolio_url'    => $this->portfolio_url,
            'years_experience' => $this->years_experience,
            'user'             => new UserResource($this->whenLoaded('user')),
            'skills'           => SkillResource::collection($this->whenLoaded('skills')),
        ];
    }
}
