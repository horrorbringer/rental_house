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
                            Room Information
                        </label>
                        <div class="relative">
                            <select id="room_id" name="room_id" required
                                class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pr-10">
                                <option value="">Select a room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        Room {{ $room->room_number }} - {{ $room->building->name }} 
                                        ({{ $room->active_tenants_count }}/{{ $room->capacity }} occupied) - 
                                        ฿{{ number_format($room->monthly_rent) }}
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
                            Tenant Information
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

                    <!-- Start Date -->
                    <div class="space-y-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Start Date
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
@endsection
