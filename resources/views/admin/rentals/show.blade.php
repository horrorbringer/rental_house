@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Rental Details</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View rental contract and usage information.</p>
            </div>
            @if(!$rental->end_date)
                <a href="{{ route('rentals.edit', $rental) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    End Rental
                </a>
            @endif
        </div>

        <!-- Status Card -->
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center">
                                <span class="text-xl font-bold text-white">{{ strtoupper(substr($rental->tenant->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                {{ $rental->tenant->name }}
                            </h3>
                            <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rental->end_date ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                                    {{ $rental->end_date ? 'Ended' : 'Active' }}
                                </span>
                                <span>•</span>
                                <span>Room {{ $rental->room->room_number }}</span>
                                <span>•</span>
                                <span>{{ $rental->start_date->format('M d, Y') }} - {{ $rental->end_date ? $rental->end_date->format('M d, Y') : 'Present' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Rent</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            ฿{{ number_format($rental->room->price) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tenant Information -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Tenant Information
                    </h3>
                    <div class="mt-5 border-t border-gray-200 dark:border-gray-700">
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="py-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $rental->tenant->phone }}</dd>
                            </div>
                            <div class="py-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $rental->tenant->email }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Room Information -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                        Room Information
                    </h3>
                    <div class="mt-5 border-t border-gray-200 dark:border-gray-700">
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="py-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room Number</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $rental->room->number }}</dd>
                            </div>
                            <div class="py-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Building</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $rental->room->building->name }}</dd>
                            </div>
                            <div class="py-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Floor</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $rental->room->floor }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Utility Usage History -->
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    Utility Usage History
                </h3>
                <div class="mt-5">
                    @if($rental->utilityUsages->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Month
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Water Usage
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Electric Usage
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($rental->utilityUsages as $usage)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                {{ $usage->created_at->format('F Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ number_format($usage->water_usage, 1) }} units
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    ฿{{ number_format($usage->water_usage * $usage->water_price, 2) }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ number_format($usage->electric_usage, 1) }} units
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    ฿{{ number_format($usage->electric_usage * $usage->electric_price, 2) }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                ฿{{ number_format(
                                                    ($usage->water_usage * $usage->water_price) +
                                                    ($usage->electric_usage * $usage->electric_price),
                                                    2
                                                ) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                No utility usage records found.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
