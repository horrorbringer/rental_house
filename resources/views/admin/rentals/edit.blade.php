@extends('layouts.dashboard')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">End Rental</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Set the end date for the rental contract.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
            <!-- Rental Info Card -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-full bg-indigo-600 flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">{{ strtoupper(substr($rental->tenant->name, 0, 1)) }}</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $rental->tenant->name }}</h3>
                        <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ $rental->tenant->phone }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Room</p>
                                <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $rental->room->number }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Start Date</p>
                                <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ \Carbon\Carbon::parse($rental->start_date)->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Duration</p>
                                <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ \Carbon\Carbon::parse($rental->start_date)->diffForHumans(now(), ['parts' => 2, 'join' => true]) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- End Date Form -->
            <form x-data="{ 
                endDate: '{{ old('end_date', date('Y-m-d')) }}',
                minDate: '{{ $rental->start_date }}',
                maxDate: '{{ now()->addDays(1)->format('Y-m-d') }}',
                isSubmitting: false
            }" 
            @submit.prevent="if(!isSubmitting) { isSubmitting = true; $el.submit(); }"
            action="{{ route('rentals.update', $rental) }}" 
            method="POST" 
            class="space-y-6">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- End Date Input -->
                    <div class="max-w-lg">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                            <input type="date" 
                                id="end_date" 
                                name="end_date" 
                                x-model="endDate"
                                :min="minDate"
                                :max="maxDate"
                                required 
                                class="block w-full pl-10 py-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500
                                      @error('end_date') border-red-500 ring-red-500 @enderror">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Date Selection Information -->
                        <div class="mt-2 text-sm">
                            <!-- Valid Date Range -->
                            <p class="text-gray-500 dark:text-gray-400 flex items-center mb-2">
                                <svg class="h-4 w-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Valid date range: 
                                <span class="font-medium ml-1">
                                    {{ \Carbon\Carbon::parse($rental->start_date)->format('M d, Y') }} to 
                                    {{ now()->addDays(1)->format('M d, Y') }}
                                </span>
                            </p>

                            <!-- Warning Message -->
                            <p class="text-amber-600 dark:text-amber-500 flex items-center">
                                <svg class="h-4 w-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                After ending this rental, the room's status will be automatically updated based on remaining tenants.
                            </p>
                        </div>

                        @error('end_date')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="h-4 w-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <!-- Cancel Button -->
                        <a href="{{ route('rentals.index') }}"
                            class="inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 disabled:opacity-50"
                            :disabled="isSubmitting">
                            <svg x-show="!isSubmitting" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg x-show="isSubmitting" class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span x-text="isSubmitting ? 'Ending Rental...' : 'End Rental'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
