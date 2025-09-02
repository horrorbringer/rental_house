@extends('layouts.dashboard')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Invoice #{{ $invoice->id }}</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Invoice details for Room {{ $invoice->rental->room->number }} - {{ $invoice->rental->tenant->name }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
                <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                    Edit
                </a>
                <a href="{{ route('invoices.download-pdf', $invoice) }}" target="_blank" class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                    Print
                </a>
                <a href="{{ route('invoices.download-pdf', $invoice) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                    Download PDF
                </a>
                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this invoice?')" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Invoice Details -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Invoice Information</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Billing Month</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->billing_month ? $invoice->billing_month->format('F Y') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->created_at ? $invoice->created_at->format('M d, Y') : 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Tenant & Room Info -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tenant & Room Information</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->rental->tenant->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->rental->room->number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Building</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->rental->room->building->name }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Charges Breakdown -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 sm:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Charges Breakdown</h3>
                <div class="mt-6 border-t border-gray-200 dark:border-gray-600">
                    <dl class="divide-y divide-gray-200 dark:divide-gray-600">
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Rent</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">${{ number_format($invoice->rent_amount, 2) }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Water Fee</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">${{ number_format($invoice->water_fee, 2) }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Water Usage Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">${{ number_format($invoice->water_usage_amount, 2) }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Electric Fee</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">${{ number_format($invoice->electric_fee, 2) }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Electric Usage Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">${{ number_format($invoice->electric_usage_amount, 2) }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-lg font-bold text-gray-900 dark:text-white">Total Amount</dt>
                            <dd class="mt-1 text-lg font-bold text-gray-900 dark:text-white sm:col-span-2">${{ number_format($invoice->total, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Add Payment Form -->
            @if($invoice->status !== 'paid')
            <div class="bg-white dark:bg-gray-700 rounded-lg p-6 sm:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Record Payment</h3>
                <form action="{{ route('payments.store', $invoice) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Amount</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" step="0.01" name="amount" id="amount" 
                                   class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md"
                                   placeholder="0.00"
                                   value="{{ $invoice->balance }}"
                                   max="{{ $invoice->balance }}"
                                   required>
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Date</label>
                        <div class="mt-1">
                            <input type="date" name="payment_date" id="payment_date" 
                                   class="block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md"
                                   value="{{ now()->format('Y-m-d') }}"
                                   required>
                        </div>
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                        <select name="method" id="method" 
                                class="mt-1 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md"
                                required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="check">Check</option>
                            <option value="other">Other</option>
                        </select>
                        @error('method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <div class="mt-1">
                            <textarea name="notes" id="notes" rows="3" 
                                      class="block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md"
                                      placeholder="Any additional notes about the payment"></textarea>
                        </div>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
            @endif

            @if($invoice->payments->isNotEmpty())
            <!-- Payment History -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 sm:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment History</h3>
                <div class="mt-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 bg-gray-100 dark:bg-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($invoice->payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ${{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ ucfirst($payment->method) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
