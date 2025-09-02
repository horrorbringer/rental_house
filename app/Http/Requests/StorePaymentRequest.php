<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    protected ?Invoice $invoice = null;

    /**
     * Get the invoice instance before validation.
     */
    protected function getInvoice(): ?Invoice
    {
        if ($this->invoice === null) {
            $this->invoice = Invoice::find($this->input('invoice_id'));
        }
        return $this->invoice;
    }

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
        $maxAmount = $this->getInvoice()?->balance ?? 0;

        return [
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => "required|numeric|min:0.01|max:{$maxAmount}",
            'payment_date' => 'required|date|before_or_equal:today',
            'method' => 'required|in:cash,bank_transfer,credit_card,debit_card,check,other',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'amount.min' => 'The payment amount must be at least $0.01.',
            'amount.max' => 'The payment amount cannot exceed the invoice balance.',
            'payment_date.before_or_equal' => 'The payment date cannot be in the future.',
        ];
    }

}
