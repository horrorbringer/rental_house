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
                <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                    Print
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
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->billing_month->format('F Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $invoice->created_at->format('M d, Y') }}</dd>
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
                                    {{ $payment->payment_date->format('M d, Y') }}
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
