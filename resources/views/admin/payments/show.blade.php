@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Payment Receipt
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Payment #{{ $payment->payment_number }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                    
                    @if($payment->created_at->diffInDays(now()) <= 7)
                        <a href="{{ route('payments.edit', $payment) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                            Edit
                        </a>

                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            Receipt Details
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Payment information and transaction details
                        </p>
                        @if($payment->invoice->paid_at && $payment->invoice->status === 'paid')
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Full Payment Completed
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Payment Date</p>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $payment->payment_date->format('F j, Y') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Recorded on {{ $payment->created_at->format('M j, Y g:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Payment Number
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $payment->payment_number }}
                        </dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Invoice Number
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $payment->invoice->invoice_number }}
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Tenant
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $payment->invoice->rental->tenant->name }}
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Room
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $payment->invoice->rental->room->building->name }} - 
                            Room {{ $payment->invoice->rental->room->room_number }}
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Payment Method
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                {{ match($payment->payment_method) {
                                    'cash' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                    'gcash' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                    'bank_transfer' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                } }}">
                                {{ str_replace('_', ' ', $payment->payment_method) }}
                            </span>
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Reference Number
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $payment->reference_number ?? 'N/A' }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Amount Paid
                        </dt>
                        <dd class="mt-1 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            ₱{{ number_format($payment->amount, 2) }}
                        </dd>
                    </div>

                    @if($payment->payment_proof)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Payment Proof
                            </dt>
                            <dd class="mt-1">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <img src="{{ Storage::url($payment->payment_proof) }}" 
                                         alt="Payment Proof" 
                                         class="w-full max-w-md h-auto">
                                </div>
                            </dd>
                        </div>
                    @endif

                    @if($payment->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Notes
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $payment->notes }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <div class="font-medium text-gray-500 dark:text-gray-400">Invoice Status</div>
                    <div class="mt-2">
                        <p class="text-gray-900 dark:text-gray-100">
                            Amount Due: ₱{{ number_format($payment->invoice->total_amount, 2) }}
                        </p>
                        <p class="text-gray-900 dark:text-gray-100">
                            Amount Paid: ₱{{ number_format($payment->invoice->amount_paid, 2) }}
                        </p>
                        <p class="text-gray-900 dark:text-gray-100">
                            Remaining Balance: ₱{{ number_format($payment->invoice->balance, 2) }}
                        </p>
                    </div>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ match($payment->invoice->status) {
                                'paid' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                'overdue' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100'
                            } }}">
                            {{ ucfirst($payment->invoice->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('invoices.show', $payment->invoice) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Invoice
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .hide-print {
        display: none !important;
    }
}
</style>
@endsection
