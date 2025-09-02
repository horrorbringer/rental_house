<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUtilityRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add proper authorization if needed
    }

    public function rules(): array
    {
        return [
            'effective_date' => ['required', 'date'],
            'water_rate' => ['required', 'numeric', 'min:0'],
            'electric_rate' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
