@extends('layouts.dashboard')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Create New Invoice</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Create a new invoice for a rental.
                </p>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('invoices.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="rental_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rental</label>
                            <select id="rental_id" name="rental_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                <option value="">Select a rental</option>
                                @foreach($rentals as $rental)
                                    <option value="{{ $rental->id }}">
                                        Room {{ $rental->room->number }} - {{ $rental->tenant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rental_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="billing_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Month</label>
                            <input type="month" name="billing_month" id="billing_month" value="{{ old('billing_month', now()->format('Y-m')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @error('billing_month')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="rent_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rent Amount</label>
                            <input type="number" step="0.01" name="rent_amount" id="rent_amount" value="{{ old('rent_amount') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @error('rent_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="water_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Water Fee</label>
                            <input type="number" step="0.01" name="water_fee" id="water_fee" value="{{ old('water_fee', 0) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @error('water_fee')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="water_usage_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Water Usage Amount</label>
                            <input type="number" step="0.01" name="water_usage_amount" id="water_usage_amount" value="{{ old('water_usage_amount', 0) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @error('water_usage_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="electric_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Electric Fee</label>
                            <input type="number" step="0.01" name="electric_fee" id="electric_fee" value="{{ old('electric_fee', 0) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @error('electric_fee')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="electric_usage_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Electric Usage Amount</label>
                            <input type="number" step="0.01" name="electric_usage_amount" id="electric_usage_amount" value="{{ old('electric_usage_amount', 0) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            @error('electric_usage_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Create Invoice
                        </button>
                        <a href="{{ route('invoices.index') }}" class="ml-3 inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
