@extends('layouts.dashboard')

@section('title', 'Room Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-gray-400 text-sm" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors duration-200">Dashboard</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('rooms.index') }}" class="hover:text-white transition-colors duration-200">Rooms</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </li>
            <li><span class="text-gray-300">Room {{ $room->room_number }}</span></li>
        </ol>
    </nav>

    <div class="bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-indigo-500 flex items-center justify-center mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-white">Room {{ $room->room_number }}</h1>
                        <p class="text-gray-400">{{ $room->building->name }}</p>
                    </div>
                </div>
                <span @class([
                    'px-3 py-1 text-sm font-medium rounded-full',
                    'bg-green-500 bg-opacity-10 text-green-500' => $room->status === App\Models\Room::STATUS_VACANT,
                    'bg-red-500 bg-opacity-10 text-red-500' => $room->status === App\Models\Room::STATUS_OCCUPIED,
                ])>
                    {{ ucfirst($room->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-750 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Room Details</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Monthly Rent</span>
                            <span class="text-white font-medium">₱{{ number_format($room->monthly_rent, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Water Fee</span>
                            <span class="text-white">₱{{ number_format($room->water_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Electric Fee</span>
                            <span class="text-white">₱{{ number_format($room->electric_fee, 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($room->rental)
                    <div class="bg-gray-750 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-white mb-4">Current Tenant</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Name</span>
                                <span class="text-white">{{ $room->rental->tenant->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Start Date</span>
                                <span class="text-white">{{ $room->rental->start_date->format('M d, Y') }}</span>
                            </div>
                            @if($room->rental->end_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">End Date</span>
                                    <span class="text-white">{{ $room->rental->end_date->format('M d, Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            @if($room->rental && $room->rental->utilityUsage)
                <div class="mt-6 bg-gray-750 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-4">Utility Usage</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-400 mb-2">Water Consumption</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Previous Reading</span>
                                    <span class="text-white">{{ $room->rental->utilityUsage->previous_water_reading }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Current Reading</span>
                                    <span class="text-white">{{ $room->rental->utilityUsage->current_water_reading }}</span>
                                </div>
                                <div class="flex justify-between font-medium">
                                    <span class="text-gray-400">Consumption</span>
                                    <span class="text-white">{{ $room->rental->utilityUsage->current_water_reading - $room->rental->utilityUsage->previous_water_reading }} cu.m</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-400 mb-2">Electric Consumption</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Previous Reading</span>
                                    <span class="text-white">{{ $room->rental->utilityUsage->previous_electric_reading }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Current Reading</span>
                                    <span class="text-white">{{ $room->rental->utilityUsage->current_electric_reading }}</span>
                                </div>
                                <div class="flex justify-between font-medium">
                                    <span class="text-gray-400">Consumption</span>
                                    <span class="text-white">{{ $room->rental->utilityUsage->current_electric_reading - $room->rental->utilityUsage->previous_electric_reading }} kWh</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-6">
                @if($room->image)
                    <img src="{{ asset('storage/' . $room->image) }}" alt="Room {{ $room->room_number }}" class="w-full max-w-2xl rounded-lg shadow-md mb-4">
                @endif

                @if($room->images->count() > 0)
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($room->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Room {{ $room->room_number }}" class="w-full rounded-lg shadow-md">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-gray-850 px-6 py-4 border-t border-gray-700">
            <div class="flex justify-between items-center">
                <a href="{{ route('rooms.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg text-sm font-medium text-gray-300 bg-transparent hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back to Rooms
                </a>
                <div class="flex space-x-3">
                    <a href="{{ route('rooms.edit', $room) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit Room
                    </a>
                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this room? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-red-500 transition-colors duration-200">
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
