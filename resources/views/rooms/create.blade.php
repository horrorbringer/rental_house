@extends('layouts.dashboard')

@section('title', isset($room) ? 'Edit Room' : 'Create Room')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white inline-flex items-center">
                    <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('rooms.index') }}" class="text-gray-400 hover:text-white ml-1 md:ml-2">Rooms</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="text-gray-500 ml-1 md:ml-2">{{ isset($room) ? 'Edit Room' : 'Create Room' }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-indigo-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ isset($room) ? 'Edit Room' : 'Create New Room' }}</h1>
                <p class="text-gray-400 mt-1">Fill in the details below to {{ isset($room) ? 'update' : 'create' }} a room</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
            <!-- Room Information Section -->
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Room Information
                </h3>
                <p class="text-sm text-gray-400">Basic details about the room and its location</p>
            </div>
            <!-- Pricing Section -->
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pricing & Fees
                </h3>
                <p class="text-sm text-gray-400">Set the room's rental rates and utility fees</p>
            </div>
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

        <form method="POST" action="{{ isset($room) ? route('rooms.update', $room) : route('rooms.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @if(isset($room))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Left Column - Room Information -->
                <div class="space-y-6">
                    <!-- Building Selection -->
                    <div class="relative group">
                        <label for="building_id" class="block text-sm font-medium text-gray-300 mb-1">
                            Building Location
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="building_id" id="building_id" required
                                class="block w-full h-10 pl-3 pr-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                                <option value="">Select a building</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ (old('building_id', $room->building_id ?? '') == $building->id) ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('building_id')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Number -->
                    <div class="relative group">
                        <label for="room_number" class="block text-sm font-medium text-gray-300 mb-1">
                            Room Number
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                            </div>
                            <input type="text" name="room_number" id="room_number" required
                                value="{{ old('room_number', $room->room_number ?? '') }}"
                                class="block w-full h-10 pl-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                                placeholder="e.g., 101">
                        </div>
                        @error('room_number')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="relative group">
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-1">
                            Room Status
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="status" id="status" required
                                class="block w-full h-10 pl-3 pr-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                                <option value="{{ App\Models\Room::STATUS_VACANT }}"
                                    {{ (old('status', $room->status ?? '') == App\Models\Room::STATUS_VACANT) ? 'selected' : '' }}>
                                    Vacant
                                </option>
                                <option value="{{ App\Models\Room::STATUS_OCCUPIED }}"
                                    {{ (old('status', $room->status ?? '') == App\Models\Room::STATUS_OCCUPIED) ? 'selected' : '' }}>
                                    Occupied
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Pricing Information -->
                <div class="space-y-6">
                    <!-- Monthly Rent -->
                    <div class="relative group">
                        <label for="monthly_rent" class="block text-sm font-medium text-gray-300 mb-1">
                            Monthly Rent
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">₱</span>
                            </div>
                            <input type="number" name="monthly_rent" id="monthly_rent" required step="0.01"
                                value="{{ old('monthly_rent', $room->monthly_rent ?? '') }}"
                                class="pl-8 block w-full h-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                                placeholder="0.00">
                        </div>
                        @error('monthly_rent')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Water Fee -->
                    <div class="relative group">
                        <label for="water_fee" class="block text-sm font-medium text-gray-300 mb-1">
                            Water Fee
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">₱</span>
                            </div>
                            <input type="number" name="water_fee" id="water_fee" required step="0.01"
                                value="{{ old('water_fee', $room->water_fee ?? '') }}"
                                class="pl-8 block w-full h-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                                placeholder="0.00">
                        </div>
                        @error('water_fee')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Electric Fee -->
                    <div class="relative group">
                        <label for="electric_fee" class="block text-sm font-medium text-gray-300 mb-1">
                            Electric Fee
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">₱</span>
                            </div>
                            <input type="number" name="electric_fee" id="electric_fee" required step="0.01"
                                value="{{ old('electric_fee', $room->electric_fee ?? '') }}"
                                class="pl-8 block w-full h-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                                placeholder="0.00">
                        </div>
                        @error('electric_fee')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                    <div class="relative">
                        <select name="status" id="status" required
                            class="block w-full h-10 rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
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

            <!-- Capacity and Images Section -->
            <div class="mt-8 pt-8 border-t border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Room Capacity
                        </h3>
                        <p class="text-sm text-gray-400">Set the maximum number of tenants allowed</p>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Room Images
                        </h3>
                        <p class="text-sm text-gray-400">Upload photos of the room</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Capacity Input -->
                    <div class="relative group">
                        <label for="capacity" class="block text-sm font-medium text-gray-300 mb-1">
                            Maximum Tenants
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <input type="number" name="capacity" id="capacity" required min="1"
                                value="{{ old('capacity', $room->capacity ?? 1) }}"
                                class="pl-10 block w-full h-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                        </div>
                        @error('capacity')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Main Room Image -->
                    <div class="relative group">
                        <label for="image" class="block text-sm font-medium text-gray-300 mb-1">
                            Main Room Image
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" name="image" id="image" accept="image/*"
                                class="block w-full py-2 px-3 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200 file:mr-4 file:py-1 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Room Images -->
                    <div class="relative group md:col-span-2">
                        <label for="additional_images" class="block text-sm font-medium text-gray-300 mb-1">
                            Additional Room Images
                            <span class="text-gray-400 text-xs ml-1">(Max 5 images)</span>
                        </label>
                        <div class="relative">
                            <input type="file" name="additional_images[]" id="additional_images" accept="image/*" multiple
                                class="block w-full py-2 px-3 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200 file:mr-4 file:py-1 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                        </div>
                        @error('additional_images')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        @error('additional_images.*')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-400">Supported formats: JPG, PNG, WEBP. Max file size: 5MB each.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-700">
                <a href="{{ route('rooms.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-sm font-medium text-gray-200 hover:bg-gray-600 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Rooms List
                </a>
                <div class="flex gap-4">
                    <button type="reset"
                        class="inline-flex items-center px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-sm font-medium text-gray-200 hover:bg-gray-600 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Form
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ isset($room) ? 'Update Room' : 'Create Room' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
