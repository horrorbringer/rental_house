@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                            Add New Utility Rate
                        </h2>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-400">
                            Set new rates for water and electricity utilities
                        </p>
                    </div>
                    <a href="{{ route('utility-rates.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Please correct the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside pl-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('utility-rates.store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    @csrf
                    
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="effective_date" class="block text-sm font-semibold text-gray-800 dark:text-gray-300">
                                Effective Date
                            </label>
                            <div class="relative mt-1">
                                <input type="date" name="effective_date" id="effective_date" required
                                    value="{{ old('effective_date', date('Y-m-d')) }}"
                                    class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5 text-gray-800 dark:text-gray-100">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="water_rate" class="block text-sm font-semibold text-gray-800 dark:text-gray-300">
                                    Water Rate per Unit
                                </label>
                                <div class="relative mt-1 rounded-lg shadow-sm">
                                    <input type="number" name="water_rate" id="water_rate" required
                                        min="0" step="0.01" value="{{ old('water_rate', '0.00') }}"
                                        class="block w-full pl-10 pr-12 border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2.5 text-gray-800 dark:text-gray-100">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-600 dark:text-gray-400 sm:text-sm">per unit</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="electric_rate" class="block text-sm font-semibold text-gray-800 dark:text-gray-300">
                                    Electric Rate per Unit
                                </label>
                                <div class="relative mt-1 rounded-lg shadow-sm">
                                    <input type="number" name="electric_rate" id="electric_rate" required
                                        min="0" step="0.01" value="{{ old('electric_rate', '0.00') }}"
                                        class="block w-full pl-10 pr-12 border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2.5 text-gray-800 dark:text-gray-100">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-600 dark:text-gray-400 sm:text-sm">per unit</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-semibold text-gray-800 dark:text-gray-300">
                                Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                        <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                            <a href="{{ route('utility-rates.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Rate
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar {
        background: transparent !important;
        border: 1px solid #e5e7eb;
    }
    .dark .flatpickr-calendar {
        background: #1f2937 !important;
        border-color: #374151;
    }
    .flatpickr-calendar.arrowTop:after {
        border-bottom-color: #ffffff;
    }
    .dark .flatpickr-calendar.arrowTop:after {
        border-bottom-color: #1f2937;
    }
    .flatpickr-current-month {
        color: #111827;
    }
    .dark .flatpickr-current-month {
        color: #f3f4f6;
    }
    .flatpickr-weekday {
        color: #6b7280;
    }
    .dark .flatpickr-weekday {
        color: #9ca3af;
    }
    .flatpickr-day {
        color: #374151;
    }
    .dark .flatpickr-day {
        color: #e5e7eb;
    }
    .flatpickr-day.selected {
        background: #4f46e5;
        border-color: #4f46e5;
    }
    .dark .flatpickr-day.selected {
        background: #6366f1;
        border-color: #6366f1;
    }
    .flatpickr-day:hover {
        background: #f3f4f6;
    }
    .dark .flatpickr-day:hover {
        background: #374151;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr('input[type="date"]', {
        dateFormat: 'Y-m-d',
        defaultDate: '{{ date("Y-m-d") }}',
        prevArrow: '<svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>',
        nextArrow: '<svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
        locale: {
            weekdays: {
                shorthand: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                longhand: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
            // Add animation when date changes
            instance.element.classList.add('ring-2', 'ring-indigo-500');
            setTimeout(() => instance.element.classList.remove('ring-2', 'ring-indigo-500'), 200);
        }
    });
</script>
@endpush
