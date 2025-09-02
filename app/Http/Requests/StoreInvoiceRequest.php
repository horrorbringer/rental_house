<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add proper authorization if needed
    }

    public function rules(): array
    {
        return [
            'rental_id' => ['required', 'exists:rentals,id'],
            'utility_usage_id' => ['nullable', 'exists:utility_usages,id'],
            'billing_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:billing_date'],
            'rent_amount' => ['required', 'numeric', 'min:0'],
            'other_charges' => ['nullable', 'numeric', 'min:0'],
            'other_charges_notes' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
