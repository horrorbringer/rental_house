@extends('layouts.dashboard')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Rental</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create a new rental contract for a tenant.</p>
        </div>

        <form action="{{ route('rentals.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-4 sm:p-6 space-y-8">
                    <!-- Room Selection -->
                    <div class="space-y-4">
                        <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Room Information <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="room_id" name="room_id" required
                                class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pr-10">
                                <option value="">Select a room</option>
                                                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                        data-monthly-rent="{{ $room->monthly_rent }}"
                                        data-has-active-rental="{{ $room->hasActiveRental ? 'true' : 'false' }}"
                                        {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        Room {{ $room->room_number }} - {{ $room->building->name }} 
                                        (Monthly: ${{ number_format($room->monthly_rent, 2) }}, 
                                        Water: ${{ number_format($room->water_fee, 2) }}/m³, 
                                        Electric: ${{ number_format($room->electric_fee, 2) }}/kWh)
                                        @if($room->activeRentals->count() > 0)
                                            <span class="text-blue-500"> - Shared with {{ $room->activeRentals->count() }} tenant(s)</span>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('room_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tenant Selection -->
                    <div class="space-y-4">
                        <label for="tenant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tenant Information <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="tenant_id" name="tenant_id" required
                                class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pr-10">
                                <option value="">Select a tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->name }} - {{ $tenant->phone }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('tenant_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Deposit Amount --}}
                    <div class="space-y-4">
                        <label for="deposit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Deposit Amount
                        </label>
                        <div class="relative">
                            <input type="number" id="deposit" name="deposit"
                                value="{{ old('deposit') }}"
                                step="0.01"
                                min="0"
                                placeholder="Enter deposit amount (optional)"
                                class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m-3-3H9"></path>
                                </svg>
                            </div>
                        </div>
                        @error('deposit')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div class="space-y-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" id="start_date" name="start_date" required 
                                value="{{ old('start_date', date('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}"
                                class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            The rental period will start from this date.
                        </p>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    <!-- Initialize Utility Usages -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Initial Utility Readings <span class="text-red-500">*</span>
                        </label>

                        <!-- Reading Date -->
                        <div class="space-y-2">
                            <div class="relative">
                                <input type="date" name="reading_date" id="reading_date" required
                                    class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('reading_date') border-red-300 dark:border-red-600 text-red-900 dark:text-red-300 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 @error('reading_date') text-red-400 @else text-gray-400 @enderror" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('reading_date')
                                <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Select the date when initial meter readings were taken (must be in the same month as rental start date)
                                </p>
                            @enderror

                            <!-- Water Usage -->
                            <div class="space-y-2">
                                <div class="relative">
                                    <input type="number" step="0.01" name="water_usage" id="water_usage" required
                                        value="{{ old('water_usage') }}"
                                        placeholder="Enter water meter reading"
                                        class="block w-full pl-10 pr-16 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('water_usage') border-red-300 dark:border-red-600 text-red-900 dark:text-red-300 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 @error('water_usage') text-red-400 @else text-gray-400 @enderror" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="@error('water_usage') text-red-400 @else text-gray-400 @enderror dark:text-gray-400 sm:text-sm">m³</span>
                                    </div>
                                </div>
                                @error('water_usage')
                                    <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Electric Usage -->
                            <div class="space-y-2">
                                <div class="relative">
                                    <input type="number" step="0.01" name="electric_usage" id="electric_usage" required
                                        value="{{ old('electric_usage') }}"
                                        placeholder="Enter electric meter reading"
                                        class="block w-full pl-10 pr-16 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('electric_usage') border-red-300 dark:border-red-600 text-red-900 dark:text-red-300 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 @error('electric_usage') text-red-400 @else text-gray-400 @enderror" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="@error('electric_usage') text-red-400 @else text-gray-400 @enderror dark:text-gray-400 sm:text-sm">kWh</span>
                                    </div>
                                </div>
                                @error('electric_usage')
                                    <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Utility rates will be taken from the room settings.
                        </p>


                        <!-- Notes -->
                        <div class="space-y-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Additional Notes
                            </label>
                            <div class="relative">
                                <textarea name="notes" id="notes" rows="3"
                                    class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Any additional notes about the readings">{{ old('notes') }}</textarea>
                                <div class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 text-right sm:px-6 rounded-b-lg border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('rentals.index') }}"
                            class="inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Rental
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const readingDateInput = document.getElementById('reading_date');
            const roomSelect = document.getElementById('room_id');
            const utilitySection = document.querySelector('.space-y-4 [for="reading_date"]').closest('.space-y-4');
            const waterUsageInput = document.getElementById('water_usage');
            const electricUsageInput = document.getElementById('electric_usage');

            function updateReadingDateConstraints() {
                const startDate = new Date(startDateInput.value);
                const firstDayOfMonth = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
                const lastDayOfMonth = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
                
                // Set default reading date to first day of the month if not set
                if (!readingDateInput.value) {
                    readingDateInput.value = firstDayOfMonth.toISOString().split('T')[0];
                }
                
                // Set min/max to constrain to the same month
                readingDateInput.min = firstDayOfMonth.toISOString().split('T')[0];
                readingDateInput.max = lastDayOfMonth.toISOString().split('T')[0];
            }

            function handleRoomSelection() {
                const selectedOption = roomSelect.options[roomSelect.selectedIndex];
                if (!selectedOption.value) return;

                const hasActiveRental = selectedOption.dataset.hasActiveRental === 'true';
                
                // If room has active rentals, hide utility initialization section
                if (hasActiveRental) {
                    utilitySection.style.display = 'none';
                    readingDateInput.disabled = true;
                    waterUsageInput.disabled = true;
                    electricUsageInput.disabled = true;
                    readingDateInput.required = false;
                    waterUsageInput.required = false;
                    electricUsageInput.required = false;

                    // Add info message about shared utilities
                    let infoMsg = document.querySelector('.utility-info');
                    if (!infoMsg) {
                        infoMsg = document.createElement('div');
                        infoMsg.className = 'utility-info text-blue-600 dark:text-blue-400 text-sm mt-2 flex items-center bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg';
                        infoMsg.innerHTML = `
                            <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>This room already has active rentals. Utilities are being tracked and will be shared among all tenants. No need for initialization.</span>
                        `;
                        utilitySection.parentNode.insertBefore(infoMsg, utilitySection);
                    }
                } else {
                    // Show and enable utility initialization for new rentals
                    utilitySection.style.display = 'block';
                    readingDateInput.disabled = false;
                    waterUsageInput.disabled = false;
                    electricUsageInput.disabled = false;
                    readingDateInput.required = true;
                    waterUsageInput.required = true;
                    electricUsageInput.required = true;

                    // Remove info message if exists
                    const infoMsg = document.querySelector('.utility-info');
                    if (infoMsg) {
                        infoMsg.remove();
                    }
                }
            }

            // Set initial constraints
            updateReadingDateConstraints();
            handleRoomSelection();

            // Update constraints whenever start_date changes
            startDateInput.addEventListener('change', updateReadingDateConstraints);
            // Handle room selection changes
            roomSelect.addEventListener('change', handleRoomSelection);
        });
    </script>
@endsection
