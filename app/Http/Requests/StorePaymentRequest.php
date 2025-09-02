<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

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
            'payment_method' => 'required|in:cash,bank_transfer,gcash,credit_card,debit_card,check,other',
            'reference_number' => 'required_if:payment_method,bank_transfer,gcash|nullable|string|max:50',
            'payment_proof' => 'required_if:payment_method,bank_transfer,gcash|nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'amount.min' => 'The payment amount must be at least ₱0.01.',
            'amount.max' => 'The payment amount cannot exceed the invoice balance of ₱:max.',
            'payment_date.before_or_equal' => 'The payment date cannot be in the future.',
            'payment_method.required' => 'Please select a payment method.',
            'reference_number.required_if' => 'Reference number is required for :value payments.',
            'payment_proof.required_if' => 'Payment proof is required for :value payments.',
            'payment_proof.mimes' => 'Payment proof must be an image (jpg, jpeg, png) or PDF file.',
            'payment_proof.max' => 'Payment proof must not exceed 2MB.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert amount to two decimal places
        if ($this->has('amount')) {
            $this->merge([
                'amount' => number_format((float) $this->amount, 2, '.', ''),
            ]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Generate payment number
        $latestPayment = Payment::latest()->first();
        $nextId = $latestPayment ? $latestPayment->id + 1 : 1;
        $paymentNumber = 'PY' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        $this->merge([
            'payment_number' => $paymentNumber,
        ]);

        // Handle payment proof upload if provided
        if ($this->hasFile('payment_proof')) {
            $path = $this->file('payment_proof')->store('payment-proofs', 'public');
            $this->merge([
                'payment_proof' => $path,
            ]);
        }
    }

}
