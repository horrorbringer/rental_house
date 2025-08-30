@extends('layouts.dashboard')

@section('title', isset($room) ? 'Edit Room' : 'Create Room')

@section('content')
<div class="max-w-2xl mx-auto">
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
            <li><span class="text-gray-300">{{ isset($room) ? 'Edit' : 'Create New' }}</span></li>
        </ol>
    </nav>

    <div class="bg-gray-800 rounded-2xl shadow-xl p-8">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 rounded-lg bg-indigo-500 flex items-center justify-center mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-100">{{ isset($room) ? 'Edit Room' : 'Create New Room' }}</h2>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-500 bg-opacity-10 border border-red-500 text-red-500 px-4 py-3 rounded-lg">
                <div class="font-medium">Please fix the following errors:</div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ isset($room) ? route('rooms.update', $room) : route('rooms.store') }}" class="space-y-6">
            @csrf
            @if(isset($room))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6">
                <!-- Building Selection -->
                <div>
                    <label for="building_id" class="block text-sm font-medium text-gray-300 mb-1">Building</label>
                    <div class="relative">
                        <select name="building_id" id="building_id" required
                            class="block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                            <option value="">Select a building</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}" {{ (old('building_id', $room->building_id ?? '') == $building->id) ? 'selected' : '' }}>
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('building_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room Number -->
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-300 mb-1">Room Number</label>
                    <div class="relative">
                        <input type="text" name="room_number" id="room_number" required
                            value="{{ old('room_number', $room->room_number ?? '') }}"
                            class="block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                            placeholder="e.g., 101">
                    </div>
                    @error('room_number')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monthly Rent -->
                <div>
                    <label for="monthly_rent" class="block text-sm font-medium text-gray-300 mb-1">Monthly Rent</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">₱</span>
                        </div>
                        <input type="number" name="monthly_rent" id="monthly_rent" required step="0.01"
                            value="{{ old('monthly_rent', $room->monthly_rent ?? '') }}"
                            class="pl-8 block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                            placeholder="0.00">
                    </div>
                    @error('monthly_rent')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Water Fee -->
                <div>
                    <label for="water_fee" class="block text-sm font-medium text-gray-300 mb-1">Water Fee</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">₱</span>
                        </div>
                        <input type="number" name="water_fee" id="water_fee" required step="0.01"
                            value="{{ old('water_fee', $room->water_fee ?? '') }}"
                            class="pl-8 block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                            placeholder="0.00">
                    </div>
                    @error('water_fee')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Electric Fee -->
                <div>
                    <label for="electric_fee" class="block text-sm font-medium text-gray-300 mb-1">Electric Fee</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">₱</span>
                        </div>
                        <input type="number" name="electric_fee" id="electric_fee" required step="0.01"
                            value="{{ old('electric_fee', $room->electric_fee ?? '') }}"
                            class="pl-8 block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                            placeholder="0.00">
                    </div>
                    @error('electric_fee')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                    <div class="relative">
                        <select name="status" id="status" required
                            class="block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                            <option value="{{ App\Models\Room::STATUS_VACANT }}"
                                {{ (old('status', $room->status ?? '') == App\Models\Room::STATUS_VACANT) ? 'selected' : '' }}>
                                Vacant
                            </option>
                            <option value="{{ App\Models\Room::STATUS_OCCUPIED }}"
                                {{ (old('status', $room->status ?? '') == App\Models\Room::STATUS_OCCUPIED) ? 'selected' : '' }}>
                                Occupied
                            </option>
                        </select>
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-700 mt-8">
                <a href="{{ route('rooms.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg text-sm font-medium text-gray-300 bg-transparent hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back to Rooms
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($room) ? 'Update Room' : 'Create Room' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
