@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Add New Utility Reading
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Enter meter readings and upload images for both water and electricity
                        </p>
                    </div>
                    <a href="{{ route('utility-usages.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold">Please correct the following errors:</span>
                        </div>
                        <dl class="space-y-2">
                            @error('rental_id')
                            <div>
                                <dt class="font-medium">Rental Selection</dt>
                                <dd class="text-sm">{{ $message }}</dd>
                            </div>
                            @enderror

                            @error('water_usage')
                            <div>
                                <dt class="font-medium">Water Usage</dt>
                                <dd class="text-sm">{{ $message }}</dd>
                            </div>
                            @enderror

                            @error('electric_usage')
                            <div>
                                <dt class="font-medium">Electric Usage</dt>
                                <dd class="text-sm">{{ $message }}</dd>
                            </div>
                            @enderror

                            @error('reading_date')
                            <div>
                                <dt class="font-medium">Reading Date</dt>
                                <dd class="text-sm">{{ $message }}</dd>
                            </div>
                            @enderror

                            @error('notes')
                            <div>
                                <dt class="font-medium">Notes</dt>
                                <dd class="text-sm">{{ $message }}</dd>
                            </div>
                            @enderror

                            @if($errors->has('water_meter_image_start') || $errors->has('water_meter_image_end') || $errors->has('electric_meter_image_start') || $errors->has('electric_meter_image_end'))
                            <div>
                                <dt class="font-medium">Image Uploads</dt>
                                @error('water_meter_image_start')
                                <dd class="text-sm">Water Start: {{ $message }}</dd>
                                @enderror
                                @error('water_meter_image_end')
                                <dd class="text-sm">Water End: {{ $message }}</dd>
                                @enderror
                                @error('electric_meter_image_start')
                                <dd class="text-sm">Electric Start: {{ $message }}</dd>
                                @enderror
                                @error('electric_meter_image_end')
                                <dd class="text-sm">Electric End: {{ $message }}</dd>
                                @enderror
                            </div>
                            @endif
                        </dl>
                    </div>
                @endif

                <form action="{{ route('utility-usages.store') }}" method="POST" enctype="multipart/form-data" 
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 relative" autocomplete="on">
                    @csrf
                    <div id="form-overlay" class="hidden absolute inset-0 bg-gray-100/70 dark:bg-gray-900/70 flex items-center justify-center z-10">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </div>
                    <div class="p-6 space-y-8">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Utility Usage Details</h3>
                        <!-- Rental and Date Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <select name="rental_id" id="rental_id" required autofocus autocomplete="off"
                                    class="peer mt-1 block w-full rounded-md @error('rental_id') border-red-300 dark:border-red-600 text-red-900 dark:text-red-100 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 @enderror shadow-sm transition-all">
                                    <option value="">Select a rental</option>
                                    @foreach($rentals as $rental)
                                        <option value="{{ $rental->id }}" {{ old('rental_id') == $rental->id ? 'selected' : '' }}>
                                            {{ $rental->room->name }} - {{ $rental->tenant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="rental_id" class="absolute left-3 top-0 text-xs @error('rental_id') text-red-500 dark:text-red-400 @else text-gray-500 dark:text-gray-400 @enderror bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Rental/Tenant</label>
                                @error('rental_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <input type="date" name="reading_date" id="reading_date" autocomplete="off"
                                    class="peer mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                    value="{{ old('reading_date', date('Y-m-d')) }}" required>
                                <label for="reading_date" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Reading Date</label>
                            </div>
                        </div>

                        <!-- Water Usage -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <input type="number" step="0.01" name="water_usage" id="water_usage" autocomplete="off"
                                    class="peer block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 pr-12 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                    value="{{ old('water_usage') }}" required>
                                <label for="water_usage" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Water Usage</label>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">m³</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                    </svg>
                                    Rate will be taken from the room settings
                                </div>
                            </div>
                            <div class="relative">
                                <input type="number" step="0.01" name="electric_usage" id="electric_usage" autocomplete="off"
                                    class="peer block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 pr-12 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                    value="{{ old('electric_usage') }}" required>
                                <label for="electric_usage" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Electric Usage</label>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">kWh</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Rate will be taken from the room settings
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Rate Information</span>
                                Water and electric rates will be automatically applied based on the selected room's settings.
                                The rates can be configured in the room settings.
                            </p>
                        </div>

                        <!-- Notes -->
                        <div class="relative">
                            <textarea name="notes" id="notes" rows="3" autocomplete="off"
                                class="peer mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                placeholder="Any additional notes about the readings">{{ old('notes') }}</textarea>
                            <label for="notes" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Notes</label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                        <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                            <button type="submit" id="submitBtn"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-all disabled:opacity-50">
                                Save Utility Reading
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
