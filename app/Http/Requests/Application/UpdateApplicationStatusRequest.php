<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seules les entreprises peuvent changer le statut d'une candidature
        return $this->user()->isCompany();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:accepted,rejected,pending',
        ];
    }
}
