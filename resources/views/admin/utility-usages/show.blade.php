@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Utility Usage Details
                    </h2>
                    <a href="{{ route('utility-usages.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to List
                    </a>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Room Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Building</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $utilityUsage->rental->room->building->name }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $utilityUsage->rental->room->room_number }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Rent</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        ฿{{ number_format($utilityUsage->rental->room->monthly_rent, 2) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tenant Information</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $utilityUsage->rental->tenant->name }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $utilityUsage->rental->tenant->phone }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $utilityUsage->rental->tenant->email ?? 'N/A' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            Usage Details for {{ Carbon\Carbon::parse($utilityUsage->billing_month)->format('F Y') }}
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <dl>
                            <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Water Usage
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                    {{ number_format($utilityUsage->water_usage, 2) }} units
                                    <span class="text-gray-500 dark:text-gray-400">
                                        (Rate: ฿{{ number_format($utilityUsage->water_price, 2) }}/unit)
                                    </span>
                                    <br>
                                    <span class="font-medium">
                                        Total: ฿{{ number_format($utilityUsage->water_usage * $utilityUsage->water_price, 2) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50 dark:bg-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Electric Usage
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                    {{ number_format($utilityUsage->electric_usage, 2) }} units
                                    <span class="text-gray-500 dark:text-gray-400">
                                        (Rate: ฿{{ number_format($utilityUsage->electric_price, 2) }}/unit)
                                    </span>
                                    <br>
                                    <span class="font-medium">
                                        Total: ฿{{ number_format($utilityUsage->electric_usage * $utilityUsage->electric_price, 2) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($utilityUsage->rental->invoices->where('billing_month', $utilityUsage->billing_month)->first())
                    @php
                        $invoice = $utilityUsage->rental->invoices->where('billing_month', $utilityUsage->billing_month)->first();
                    @endphp
                    <div class="mt-6 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                Invoice Summary
                            </h3>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700">
                            <dl>
                                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Rent</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                        ฿{{ number_format($invoice->rent_amount, 2) }}
                                    </dd>
                                </div>
                                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50 dark:bg-gray-700">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Water Charges</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                        ฿{{ number_format($invoice->water_usage_amount, 2) }}
                                    </dd>
                                </div>
                                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Electric Charges</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                        ฿{{ number_format($invoice->electric_usage_amount, 2) }}
                                    </dd>
                                </div>
                                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50 dark:bg-gray-700">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                        ฿{{ number_format($invoice->total, 2) }}
                                    </dd>
                                </div>
                                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</dt>
                                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
