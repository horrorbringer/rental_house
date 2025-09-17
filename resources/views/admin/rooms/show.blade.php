@extends('layouts.dashboard')

@section('title', 'Room Details')

@section('content')
<div class="mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-gray-500 dark:text-gray-400 text-sm" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700 dark:hover:text-white transition-colors duration-200">Dashboard</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('rooms.index') }}" class="hover:text-gray-700 dark:hover:text-white transition-colors duration-200">Rooms</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </li>
            <li><span class="text-gray-700 dark:text-gray-300">Room {{ $room->room_number }}</span></li>
        </ol>
    </nav>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                <div class="flex items-start lg:items-center flex-grow">
                    <div class="w-14 h-14 rounded-xl bg-indigo-600 dark:bg-indigo-500 flex items-center justify-center mr-4 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Room {{ $room->room_number }}</h1>
                            <span @class([
                                'px-3 py-1 text-sm font-medium rounded-full shadow-sm',
                                'bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500' => $room->status === App\Models\Room::STATUS_VACANT,
                                'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500' => $room->status === App\Models\Room::STATUS_OCCUPIED,
                            ])>
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex items-center text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                                <span>{{ $room->building->name }}</span>
                            </div>
                            <div class="flex items-center text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>៛{{ number_format($room->monthly_rent, 2) }}/month</span>
                            </div>
                            @if($room->status === App\Models\Room::STATUS_OCCUPIED)
                            <div class="flex items-center text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ $room->rentals->count() }} {{ Str::plural('Tenant', $room->rentals->count()) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('rooms.edit', $room) }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit Room
                    </a>
                    <a href="{{ route('rooms.quick-add-rental',$room) }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Rental
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Room Information --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Room Details</h3>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-600 px-2.5 py-1 rounded-full">
                            ID: {{ $room->id }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Monthly Rent</div>
                                <div class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                    <span class="text-2xl mr-1">៛</span>
                                    {{ number_format($room->monthly_rent, 2) }}
                                </div>
                            </div>
                            <div class="flex space-x-4">
                                <div class="flex-1 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Water Fee</div>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                        <span class="text-lg mr-1">៛</span>
                                        {{ number_format($room->water_fee, 2) }}
                                    </div>
                                </div>
                                <div class="flex-1 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Electric Fee</div>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                        <span class="text-lg mr-1">៛</span>
                                        {{ number_format($room->electric_fee, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-3">Additional Information</div>
                            <div class="space-y-3">
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Created: {{ $room->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Last Updated: {{ $room->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rental Information --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Rental Information</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                @if($room->status === App\Models\Room::STATUS_OCCUPIED)
                                    Managing {{ $room->rentals->count() }} {{ Str::plural('tenant', $room->rentals->count()) }}
                                @else
                                    Room available for rent
                                @endif
                            </p>
                        </div>
                        @if($room->status === App\Models\Room::STATUS_VACANT)
                            <a href="{{ route('rooms.quick-add-rental',$room) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors duration-200 group">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span class="font-medium">Add New Rental</span>
                            </a>
                        @endif
                    </div>
                    @if($room->status === App\Models\Room::STATUS_OCCUPIED && $room->rentals->count() > 0)
                        <div class="space-y-4">
                            @foreach ($room->rentals as $rental)
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center mr-3">
                                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-md font-semibold text-gray-900 dark:text-white">{{ $rental->tenant->name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Tenant ID: {{ $rental->tenant->id }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('rentals.show', $rental) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors duration-200">
                                            <span class="mr-1">View Details</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="block text-gray-500 dark:text-gray-400">Start Date</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $rental->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-gray-500 dark:text-gray-400">Duration</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $rental->created_at->diffForHumans(null, true) }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-gray-500 dark:text-gray-400">Status</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-500">
                                                Active
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">This room is currently vacant and ready for new tenants.</p>
                            <a href="{{ route('rooms.quick-add-rental',$room) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span class="font-medium">Add New Rental</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Utility Readings --}}
                @if($room->status === App\Models\Room::STATUS_OCCUPIED && $room->rentals->count() > 0)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Utility Readings</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Record monthly meter readings for billing
                            </p>
                        </div>
                        <button type="button" onclick="document.getElementById('quickUtilityModal').classList.remove('hidden')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="font-medium">Record Reading</span>
                        </button>
                    </div>

                    {{-- Quick Add Utility Modal --}}
                    <div id="quickUtilityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
                        <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-lg bg-white dark:bg-gray-800">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Record Utility Reading
                                </h3>
                                <button type="button" onclick="document.getElementById('quickUtilityModal').classList.add('hidden')"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            @foreach($room->rentals as $rental)
                                <form action="{{ route('rooms.store-quick-utility', $rental) }}" method="POST" class="space-y-4">
                                    @csrf

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="water_usage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Water Meter Reading
                                            </label>
                                            <input type="number" name="water_usage" id="water_usage" step="0.01" min="0"
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                                required>
                                            @error('water_usage')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="electric_usage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Electric Meter Reading
                                            </label>
                                            <input type="number" name="electric_usage" id="electric_usage" step="0.01" min="0"
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                                required>
                                            @error('electric_usage')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="reading_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Reading Date
                                        </label>
                                        <input type="date" name="reading_date" id="reading_date"
                                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                            required>
                                        @error('reading_date')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Notes
                                        </label>
                                        <textarea name="notes" id="notes" rows="3"
                                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                            placeholder="Optional notes about this reading"></textarea>
                                        @error('notes')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end space-x-3 pt-4">
                                        <button type="button"
                                            onclick="document.getElementById('quickUtilityModal').classList.add('hidden')"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                            Record Reading
                                        </button>
                                    </div>
                                </form>

                                {{-- Previous Readings --}}
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Previous Readings</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Water</th>
                                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Electric</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($rental->utilityUsages()->latest('reading_date')->take(5)->get() as $usage)
                                                    <tr>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                            {{ $usage->reading_date->format('M d, Y') }}
                                                        </td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                            {{ number_format($usage->water_usage, 2) }}m³
                                                        </td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                            {{ number_format($usage->electric_usage, 2) }}kWh
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Utility Usage History --}}
                    <div class="space-y-4">
                        @foreach($room->rentals as $rental)
                            @php
                                $latestUsage = $rental->utilityUsages()->latest('reading_date')->first();
                            @endphp
                            @if($latestUsage)
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Latest Reading</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $latestUsage->reading_date->format('F d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="block text-sm text-gray-500 dark:text-gray-400">Water Usage</span>
                                            <span class="text-lg font-medium text-gray-900 dark:text-white">
                                                {{ number_format($latestUsage->water_usage, 2) }}m³
                                            </span>
                                        </div>
                                        <div>
                                            <span class="block text-sm text-gray-500 dark:text-gray-400">Electric Usage</span>
                                            <span class="text-lg font-medium text-gray-900 dark:text-white">
                                                {{ number_format($latestUsage->electric_usage, 2) }}kWh
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    No utility readings recorded yet
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Room Images -->
                @if($room->image)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Room Images</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $room->images->count() + 1 }} {{ Str::plural('photo', $room->images->count() + 1) }} available
                            </p>
                        </div>
                        <button type="button" class="inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">Add Photos</span>
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Main Image --}}
                        <div class="relative aspect-[21/9] rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 shadow-lg group">
                            <img src="{{ asset('storage/' . $room->image) }}" 
                                alt="Room {{ $room->room_number }}" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4">
                                <span class="text-white text-sm font-medium bg-gray-900/70 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-sm">
                                    Main Image
                                </span>
                            </div>
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button type="button" class="p-2 bg-white/90 dark:bg-gray-800/90 rounded-full shadow-sm hover:bg-white dark:hover:bg-gray-800 transition-colors duration-200">
                                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Additional Images --}}
                        @if($room->images->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($room->images as $image)
                                    <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 shadow-sm group">
                                        <img src="{{ asset('storage/' . $image->path) }}" 
                                            alt="Room {{ $room->room_number }}" 
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <div class="flex space-x-2">
                                                <button type="button" class="p-2 bg-white/90 dark:bg-gray-800/90 rounded-full shadow-sm hover:bg-white dark:hover:bg-gray-800 transition-colors duration-200">
                                                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button type="button" class="p-2 bg-white/90 dark:bg-gray-800/90 rounded-full shadow-sm hover:bg-white dark:hover:bg-gray-800 transition-colors duration-200">
                                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        <div class="bg-gray-50 my-3 rounded-lg dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('rooms.index') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-transparent hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back to Rooms
                </a>
                <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-3">
                    <a href="{{ route('rooms.edit', $room) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 rounded-lg text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit Room
                    </a>
                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="w-full sm:w-auto"
                        onsubmit="return confirm('Are you sure you want to delete this room? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-6 py-2 rounded-lg text-sm font-medium text-red-500 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 dark:focus:ring-offset-gray-800 focus:ring-red-500 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Room
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
