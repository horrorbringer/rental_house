@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Record Payment
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        For Invoice #{{ $invoice->invoice_number }}
                    </p>
                </div>

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="bg-gray-50 dark:bg-gray-700 p-6 mb-6 rounded-lg">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-2">Invoice Details</h3>
                            <dl class="divide-y divide-gray-200 dark:divide-gray-600">
                                <div class="py-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $invoice->rental->tenant->name }}</dd>
                                </div>
                                <div class="py-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $invoice->rental->room->building->name }} - Room {{ $invoice->rental->room->room_number }}
                                    </dd>
                                </div>
                                <div class="py-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $invoice->due_date->format('F j, Y') }}
                                        @if($invoice->due_date->isPast())
                                            <span class="text-red-600 ml-2">(Overdue)</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium mb-2">Amount Details</h3>
                            <dl class="divide-y divide-gray-200 dark:divide-gray-600">
                                <div class="py-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">₱{{ number_format($invoice->total_amount, 2) }}</dd>
                                </div>
                                <div class="py-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount Paid</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">₱{{ number_format($invoice->amount_paid, 2) }}</dd>
                                </div>
                                <div class="py-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Remaining Balance</dt>
                                    <dd class="text-lg font-bold text-gray-900 dark:text-gray-100">₱{{ number_format($invoice->balance, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <form action="{{ route('payments.store', $invoice) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Payment Amount <span class="text-red-600">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" 
                                       name="amount" 
                                       id="amount" 
                                       step="0.01"
                                       max="{{ $invoice->balance }}"
                                       required
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="{{ old('amount') ?? $invoice->balance }}"
                                       placeholder="0.00">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Payment Date <span class="text-red-600">*</span>
                            </label>
                            <input type="date" 
                                   name="payment_date" 
                                   id="payment_date" 
                                   required
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   value="{{ old('payment_date') ?? now()->format('Y-m-d') }}">
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Payment Method <span class="text-red-600">*</span>
                            </label>
                            <select name="payment_method" 
                                    id="payment_method" 
                                    required
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Select payment method</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="reference_number_group" style="display: none;">
                            <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Reference Number <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   name="reference_number" 
                                   id="reference_number" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   value="{{ old('reference_number') }}"
                                   placeholder="Enter reference number">
                            @error('reference_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="payment_proof_group" style="display: none;">
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Payment Proof <span class="text-red-600">*</span>
                            </label>
                            <input type="file" 
                                   name="payment_proof" 
                                   id="payment_proof" 
                                   accept="image/*"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('payment_proof')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                      placeholder="Any additional notes about the payment">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('invoices.show', $invoice) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.getElementById('payment_method');
    const referenceGroup = document.getElementById('reference_number_group');
    const proofGroup = document.getElementById('payment_proof_group');

    function toggleOnlinePaymentFields() {
        const isOnline = paymentMethod.value === 'gcash' || paymentMethod.value === 'bank_transfer';
        referenceGroup.style.display = isOnline ? 'block' : 'none';
        proofGroup.style.display = isOnline ? 'block' : 'none';
    }

    paymentMethod.addEventListener('change', toggleOnlinePaymentFields);
    toggleOnlinePaymentFields(); // Run on load for existing value
});
</script>
@endpush

@endsection
