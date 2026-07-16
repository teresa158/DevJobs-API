<?php

namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seuls les candidats peuvent postuler
        return $this->user()->isCandidate();
    }

    public function rules(): array
    {
        return [
            'cover_letter' => 'nullable|string|max:5000',
        ];
    }
}
