@extends('layouts.dashboard')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Invoice #{{ $invoice->invoice_number }}</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Invoice details for Room {{ $invoice->rental->room->room_number }} - {{ $invoice->rental->tenant->name }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
                @if($invoice->status !== 'paid')
                    <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Edit
                    </a>
                @endif
                {{-- cuz pdf render not correct with unicode khmer --}}
                {{-- <a href="{{ route('invoices.download-pdf', $invoice) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                    Download PDF
                </a> --}}
                <a href="{{ route('invoices.download-pdf-en', $invoice) }}"
                    class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600"
                    >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    PDF-EN
                </a>

                @if($invoice->status === 'draft')
                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this invoice?')" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            Delete
                        </button>
                    </form>
                @endif
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
                            @if($invoice->paid_at)
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    Paid on {{ $invoice->paid_at->format('M d, Y g:i A') }}
                                </span>
                            @endif
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
                        <!-- Room Rent -->
                        <div class="py-4">
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Rent</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">
                                    ៛{{ number_format($invoice->rent_amount, 2) }}
                                </dd>
                            </div>
                        </div>
                        
                        <!-- Water Usage Details -->
                        <div class="py-4">
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 mb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Water Usage</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span>Current Reading:</span>
                                            <span>{{ number_format($invoice->utilityUsage->water_usage, 2) }} m³</span>
                                        </div>
                                        <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                            <span>Previous Reading:</span>
                                            <span>{{ $previousUsage ? number_format($previousUsage->water_usage, 2) : '0.00' }} m³</span>
                                        </div>
                                        <div class="flex justify-between font-medium border-t border-gray-200 dark:border-gray-600 pt-2">
                                            <span>Usage:</span>
                                            <span>{{ number_format($invoice->utilityUsage->water_usage - ($previousUsage ? $previousUsage->water_usage : 0), 2) }} m³</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span>Rate per m³:</span>
                                            <span>៛{{ number_format($invoice->rental->room->water_fee, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between font-medium text-indigo-600 dark:text-indigo-400">
                                            <span>Total Water Charge:</span>
                                            <span>៛{{ number_format($invoice->total_water_fee, 2) }}</span>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                        </div>
                        
                        <!-- Electric Usage Details -->
                        <div class="py-4">
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 mb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Electric Usage</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span>Current Reading:</span>
                                            <span>{{ number_format($invoice->utilityUsage->electric_usage, 2) }} kWh</span>
                                        </div>
                                        <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                            <span>Previous Reading:</span>
                                            <span>{{ $previousUsage ? number_format($previousUsage->electric_usage, 2) : '0.00' }} kWh</span>
                                        </div>
                                        <div class="flex justify-between font-medium border-t border-gray-200 dark:border-gray-600 pt-2">
                                            <span>Usage:</span>
                                            <span>{{ number_format($invoice->utilityUsage->electric_usage - ($previousUsage ? $previousUsage->electric_usage : 0), 2) }} kWh</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span>Rate per kWh:</span>
                                            <span>៛{{ number_format($invoice->rental->room->electric_fee, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between font-medium text-indigo-600 dark:text-indigo-400">
                                            <span>Total Electric Charge:</span>
                                            <span>៛{{ number_format($invoice->total_electric_fee, 2) }}</span>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="py-4 px-2 sm:grid sm:grid-cols-3 sm:gap-4 bg-gray-100 dark:bg-gray-600 rounded-lg mt-4">
                            <dt class="text-base font-semibold text-gray-900 dark:text-white">Total Amount</dt>
                            <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-white sm:col-span-2 text-right">
                                ៛{{ number_format($invoice->total_amount, 2) }}
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
