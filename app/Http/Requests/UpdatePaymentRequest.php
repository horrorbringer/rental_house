<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bank_transfer,gcash,credit_card,debit_card,check,other',
            'reference_number' => 'required_if:payment_method,bank_transfer,gcash|nullable|string|max:50',
            'payment_proof' => 'sometimes|required_if:payment_method,bank_transfer,gcash|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'payment_date.before_or_equal' => 'The payment date cannot be in the future.',
        ];
    }
}
