@extends('layouts.dashboard')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Payment</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Update payment details for payment made on {{ $payment->payment_date->format('M d, Y') }}.
                </p>
            </div>
        </div>

        <form action="{{ route('payments.update', $payment) }}" method="POST" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" 
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                       required>
                @error('payment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                <select name="method" id="method" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="cash" @selected(old('method', $payment->method) == 'cash')>Cash</option>
                    <option value="bank_transfer" @selected(old('method', $payment->method) == 'bank_transfer')>Bank Transfer</option>
                    <option value="credit_card" @selected(old('method', $payment->method) == 'credit_card')>Credit Card</option>
                    <option value="debit_card" @selected(old('method', $payment->method) == 'debit_card')>Debit Card</option>
                    <option value="check" @selected(old('method', $payment->method) == 'check')>Check</option>
                    <option value="other" @selected(old('method', $payment->method) == 'other')>Other</option>
                </select>
                @error('method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Any additional notes about the payment">{{ old('notes', $payment->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('payments.show', $payment) }}" 
                   class="mr-3 inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
