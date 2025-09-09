@extends('layouts.dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate">
                        Edit Invoice #{{ $invoice->invoice_number }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Room {{ $invoice->rental->room->name }} - {{ $invoice->rental->tenant->name }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('invoices.show', $invoice) }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Invoice
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PUT')
                
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="billing_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Billing Date
                            </label>
                            <div class="mt-1">
                                <input type="date" name="billing_date" id="billing_date" disabled
                                    value="{{ $invoice->billing_date->format('Y-m-d') }}"
                                    class="bg-gray-50 dark:bg-gray-600 shadow-sm block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                            <div class="mt-1">
                                <input type="date" name="due_date" id="due_date"
                                    value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md @error('due_date') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            </div>
                            @error('due_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <div class="mt-1">
                                <select name="status" id="status" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">
                                    @foreach(\App\Models\Invoice::$statuses as $statusOption)
                                        <option value="{{ $statusOption }}" {{ old('status', $invoice->status) === $statusOption ? 'selected' : '' }}>
                                            {{ ucfirst($statusOption) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Charges</h3>
                            <p class="mt-1 text-sm text-gray-500">Update the charges for this invoice.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="rent_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Rent Amount
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" step="0.01" name="rent_amount" id="rent_amount"
                                    value="{{ old('rent_amount', $invoice->rent_amount) }}"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md @error('rent_amount') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            </div>
                            @error('rent_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="total_water_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Water Fee
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" step="0.01" name="total_water_fee" id="total_water_fee"
                                    value="{{ old('total_water_fee', $invoice->total_water_fee) }}"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md @error('total_water_fee') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            </div>
                            @error('total_water_fee')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="total_electric_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Electric Fee
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" step="0.01" name="total_electric_fee" id="total_electric_fee"
                                    value="{{ old('total_electric_fee', $invoice->total_electric_fee) }}"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md @error('total_electric_fee') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            </div>
                            @error('total_electric_fee')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Notes
                            </label>
                            <div class="mt-1">
                                <textarea id="notes" name="notes" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">{{ old('notes', $invoice->notes) }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Add any additional notes about this invoice.</p>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 rounded-md">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount</span>
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">₱<span id="totalAmount">0.00</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 text-right sm:px-6 space-x-3">
                    <button type="button" onclick="window.location.href='{{ route('invoices.show', $invoice) }}'"
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('invoiceForm');
    const rentAmount = document.getElementById('rent_amount');
    const totalWaterFee = document.getElementById('total_water_fee');
    const totalElectricFee = document.getElementById('total_electric_fee');
    const totalAmountDisplay = document.getElementById('totalAmount');

    function calculateTotal() {
        const rent = parseFloat(rentAmount.value) || 0;
        const water = parseFloat(totalWaterFee.value) || 0;
        const electric = parseFloat(totalElectricFee.value) || 0;
        const total = rent + water + electric;
        totalAmountDisplay.textContent = total.toFixed(2);
    }

    // Calculate initial total
    calculateTotal();

    // Add event listeners for real-time total calculation
    [rentAmount, totalWaterFee, totalElectricFee].forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    form.addEventListener('submit', function(e) {
        const status = document.getElementById('status').value;
        if (status === 'paid') {
            if (!confirm('Are you sure you want to mark this invoice as paid? This will create a payment record.')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endpush
@endsection
