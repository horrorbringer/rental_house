@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Edit Utility Reading
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Update utility meter readings and recalculate invoice
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

                <div class="mb-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <div class="flex items-center text-sm mb-1">
                                <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="font-medium text-gray-500 dark:text-gray-400">Room</span>
                            </div>
                            <p class="text-sm text-gray-900 dark:text-gray-100 pl-7">
                                {{ $utilityUsage->rental->room->building->name }} - Room {{ $utilityUsage->rental->room->room_number }}
                            </p>
                        </div>
                        <div>
                            <div class="flex items-center text-sm mb-1">
                                <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium text-gray-500 dark:text-gray-400">Tenant</span>
                            </div>
                            <p class="text-sm text-gray-900 dark:text-gray-100 pl-7">
                                {{ $utilityUsage->rental->tenant->name }}
                            </p>
                        </div>
                        <div>
                            <div class="flex items-center text-sm mb-1">
                                <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium text-gray-500 dark:text-gray-400">Billing Month</span>
                            </div>
                            <p class="text-sm text-gray-900 dark:text-gray-100 pl-7">
                                {{ Carbon\Carbon::parse($utilityUsage->billing_month)->format('F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('utility-usages.update', $utilityUsage) }}" method="POST" enctype="multipart/form-data" 
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 relative">
                    @csrf
                    @method('PUT')
                    <div id="form-overlay" class="hidden absolute inset-0 bg-gray-100/70 dark:bg-gray-900/70 z-10">
                        <div class="h-full w-full flex items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="water_usage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Water Usage (units)
                                </label>
                                <div class="relative mt-1 rounded-lg shadow-sm">
                                    <input type="number" name="water_usage" id="water_usage" required
                                        min="0" step="0.01" value="{{ old('water_usage', $utilityUsage->water_usage) }}"
                                        class="block w-full pl-10 pr-12 border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2.5">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">units</span>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Rate: ฿{{ number_format($utilityUsage->rental->room->water_rate, 2) }}/unit
                                </div>
                            </div>

                            <div>
                                <label for="electric_usage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Electric Usage (units)
                                </label>
                                <div class="relative mt-1 rounded-lg shadow-sm">
                                    <input type="number" name="electric_usage" id="electric_usage" required
                                        min="0" step="0.01" value="{{ old('electric_usage', $utilityUsage->electric_usage) }}"
                                        class="block w-full pl-10 pr-12 border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2.5">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">units</span>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Rate: ฿{{ number_format($utilityUsage->rental->room->electric_rate, 2) }}/unit
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Water Calculation</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($utilityUsage->water_usage, 2) }} units × ฿{{ number_format($utilityUsage->rental->room->water_rate, 2) }} = 
                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                            ฿{{ number_format($utilityUsage->water_usage * $utilityUsage->rental->room->water_rate, 2) }}
                                        </span>
                                    </p>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Electric Calculation</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($utilityUsage->electric_usage, 2) }} units × ฿{{ number_format($utilityUsage->rental->room->electric_rate, 2) }} = 
                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                            ฿{{ number_format($utilityUsage->electric_usage * $utilityUsage->rental->room->electric_rate, 2) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                        <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                            <button type="submit" id="submitBtn"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-all disabled:opacity-50">
                                Update Reading
                            </button>
                        </div>
                    </div>
                </form>

