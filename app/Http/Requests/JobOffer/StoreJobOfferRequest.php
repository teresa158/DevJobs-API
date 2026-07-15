<?php

namespace App\Http\Requests\JobOffer;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seules les entreprises peuvent créer des offres
        return $this->user()->isCompany();
    }

    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'salary_min'    => 'nullable|integer|min:0',
            'salary_max'    => 'nullable|integer|min:0|gte:salary_min',
            // gte:salary_min → salary_max doit être >= salary_min
            'location'      => 'required|string|max:255',
            'contract_type' => 'required|in:CDI,CDD,freelance,internship',
            'status'        => 'nullable|in:open,closed',
            'skills'        => 'nullable|array',
            'skills.*'      => 'integer|exists:skills,id',
            // skills.* → chaque élément du tableau doit être un id existant dans skills
        ];
    }
}
