@extends('layouts.dashboard')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Edit Invoice #{{ $invoice->id }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update invoice details for Room {{ $invoice->rental->room->number }} - {{ $invoice->rental->tenant->name }}
                </p>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Invoice Details -->
                        <div class="col-span-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Invoice Information</h4>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="billing_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Month</label>
                            <input type="month" name="billing_month" id="billing_month" 
                                value="{{ old('billing_month', $invoice->billing_month->format('Y-m')) }}" disabled
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                <option value="unpaid" {{ $invoice->status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Charges</h4>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="rent_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rent Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="rent_amount" id="rent_amount" 
                                    value="{{ old('rent_amount', $invoice->rent_amount) }}"
                                    class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            </div>
                            @error('rent_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="water_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Water Fee</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="water_fee" id="water_fee" 
                                    value="{{ old('water_fee', $invoice->water_fee) }}"
                                    class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            </div>
                            @error('water_fee')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="water_usage_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Water Usage Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="water_usage_amount" id="water_usage_amount" 
                                    value="{{ old('water_usage_amount', $invoice->water_usage_amount) }}"
                                    class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            </div>
                            @error('water_usage_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="electric_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Electric Fee</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="electric_fee" id="electric_fee" 
                                    value="{{ old('electric_fee', $invoice->electric_fee) }}"
                                    class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            </div>
                            @error('electric_fee')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="electric_usage_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Electric Usage Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="electric_usage_amount" id="electric_usage_amount" 
                                    value="{{ old('electric_usage_amount', $invoice->electric_usage_amount) }}"
                                    class="pl-7 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            </div>
                            @error('electric_usage_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Update Invoice
                        </button>
                        <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-calculate total when any amount changes
    const amountInputs = document.querySelectorAll('input[type="number"]');
    amountInputs.forEach(input => {
        input.addEventListener('change', calculateTotal);
    });

    function calculateTotal() {
        const rentAmount = parseFloat(document.getElementById('rent_amount').value) || 0;
        const waterFee = parseFloat(document.getElementById('water_fee').value) || 0;
        const waterUsage = parseFloat(document.getElementById('water_usage_amount').value) || 0;
        const electricFee = parseFloat(document.getElementById('electric_fee').value) || 0;
        const electricUsage = parseFloat(document.getElementById('electric_usage_amount').value) || 0;

        const total = rentAmount + waterFee + waterUsage + electricFee + electricUsage;
        document.getElementById('total').textContent = total.toFixed(2);
    }
</script>
@endpush
@endsection
