<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'company_name' => $this->company_name,
            'siret'        => $this->siret,
            'website'      => $this->website,
            'description'  => $this->description,
            'address'      => $this->address,
            'city'         => $this->city,
            // whenLoaded → n'apparaît QUE si la relation est chargée (évite le N+1)
            'user'         => new UserResource($this->whenLoaded('user')),
        ];
    }
}
