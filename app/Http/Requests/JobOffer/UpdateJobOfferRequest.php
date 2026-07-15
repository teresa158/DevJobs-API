<?php

namespace App\Http\Requests\JobOffer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isCompany() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title'         => 'sometimes|string|max:255',
            // sometimes → ne valide QUE si le champ est présent dans la requête
            // Utile pour un PUT/PATCH partiel
            'description'   => 'sometimes|string',
            'salary_min'    => 'nullable|integer|min:0',
            'salary_max'    => 'nullable|integer|min:0|gte:salary_min',
            'location'      => 'sometimes|string|max:255',
            'contract_type' => 'sometimes|in:CDI,CDD,freelance,internship',
            'status'        => 'sometimes|in:open,closed',
            'skills'        => 'nullable|array',
            'skills.*'      => 'integer|exists:skills,id',
        ];
    }
}
