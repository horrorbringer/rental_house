@extends('layouts.dashboard')

@section('title', 'Rooms')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white  dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Rooms Management</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Manage your building rooms and monitor their status</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('rooms.create') }}" class="bg-indigo-600 hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-2.5 px-4 rounded-lg transition-all duration-200 inline-flex items-center group shadow-lg hover:shadow-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:scale-110 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Room
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Rooms -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-100 dark:bg-indigo-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="text-sm text-indigo-600 dark:text-indigo-400 font-medium px-2.5 py-0.5 rounded-full bg-indigo-50 dark:bg-indigo-500/10">Total</span>
            </div>
            <div class="flex flex-col">
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $rooms->total() }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Rooms</p>
            </div>
        </div>
        
        <!-- Vacant Rooms -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 dark:bg-green-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm text-green-600 dark:text-green-400 font-medium px-2.5 py-0.5 rounded-full bg-green-50 dark:bg-green-500/10">Available</span>
            </div>
            <div class="flex flex-col">
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $rooms->where('status', App\Models\Room::STATUS_VACANT)->count() }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Vacant Rooms</p>
            </div>
        </div>

        <!-- Occupied Rooms -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-red-100 dark:bg-red-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-sm text-red-600 dark:text-red-400 font-medium px-2.5 py-0.5 rounded-full bg-red-50 dark:bg-red-500/10">Occupied</span>
            </div>
            <div class="flex flex-col">
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $rooms->where('status', App\Models\Room::STATUS_OCCUPIED)->count() }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Occupied Rooms</p>
            </div>
        </div>

        <!-- Revenue Overview -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-100 dark:bg-emerald-500/10 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10">Revenue</span>
            </div>
            <div class="flex flex-col">
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">៛{{ number_format($rooms->sum('monthly_rent'), 2) }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Revenue</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('rooms.index') }}" method="GET" class="space-y-4" x-data="{ showFilters: false }">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Search & Filters</h3>
                </div>
                <button type="button" @click="showFilters = !showFilters" 
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white transition-colors duration-200 flex items-center gap-2">
                    <span x-show="!showFilters">Show Filters</span>
                    <span x-show="showFilters">Hide Filters</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-200" 
                         :class="{'rotate-180': showFilters}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <div x-show="showFilters" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label for="building_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Building</label>
                    <select name="building_id" id="building_id" 
                            class="w-full rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-shadow">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status" 
                            class="w-full rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-shadow">
                        <option value="">All Status</option>
                        <option value="{{ App\Models\Room::STATUS_VACANT }}" 
                            {{ request('status') == App\Models\Room::STATUS_VACANT ? 'selected' : '' }}>
                            Vacant
                        </option>
                        <option value="{{ App\Models\Room::STATUS_OCCUPIED }}"
                            {{ request('status') == App\Models\Room::STATUS_OCCUPIED ? 'selected' : '' }}>
                            Occupied
                        </option>
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 inline-flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-indigo-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Apply Filters
                    </button>
                    @if(request()->has('building_id') || request()->has('status'))
                        <a href="{{ route('rooms.index') }}" 
                           class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 inline-flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-gray-600/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-500 bg-opacity-10 border border-green-500 text-green-500 px-4 py-3 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('room'))
        <div class="bg-green-500 bg-opacity-10 border border-green-500 text-green-500 px-4 py-3 rounded-lg" role="alert">
            {{ session('room') }}
        </div>
    @endif

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($rooms as $room)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow group hover:shadow-lg dark:hover:shadow-indigo-500/10 transition-all duration-300 border border-gray-100 dark:border-gray-700/50 hover:border-indigo-500/50 dark:hover:border-indigo-500/50">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center">
                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $room->room_number }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $room->building->name }}</p>
                                </div>
                                <p class="mt-0.5 text-sm font-medium text-gray-900 dark:text-white">៛{{ number_format($room->monthly_rent, 2) }} <span class="text-xs text-gray-500 dark:text-gray-400">/ month</span></p>
                            </div>
                        </div>
                        <span @class([
                            'px-2.5 py-1 text-xs font-medium rounded-md',
                            'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400 ring-1 ring-green-500/20' => $room->status === App\Models\Room::STATUS_VACANT,
                            'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400 ring-1 ring-red-500/20' => $room->status === App\Models\Room::STATUS_OCCUPIED,
                        ])>
                            <span class="flex items-center gap-1">
                                @if($room->status === App\Models\Room::STATUS_VACANT)
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                @endif
                                {{ ucfirst($room->status) }}
                            </span>
                        </span>
                    </div>

                {{-- quick actions add rental and utilities --}}
                    <div class="flex items-center gap-2 border-t border-gray-100 dark:border-gray-700/50 pt-3 mt-3">
                        <a href="{{ route('rooms.quick-add-rental', $room) }}" 
                           class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-medium rounded transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Add Rental
                        </a>
                        @if($room->status === App\Models\Room::STATUS_OCCUPIED)
                        <a href="{{ route('rooms.quick-utility', $room->activeRentals->first()) }}" 
                           class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-medium rounded transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Add Utilities
                        </a>
                        @endif
                        <a href="{{ route('rooms.show', $room) }}" 
                           class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 hover:bg-gray-100 dark:bg-gray-600/10 dark:hover:bg-gray-600/20 text-gray-600 dark:text-gray-400 text-xs font-medium rounded transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </a>
                    </div>

                    @if($room->rentals && $room->rentals->isNotEmpty())
                        <div class="flex items-center gap-2 border-t border-gray-100 dark:border-gray-700/50 pt-3 mt-3">
                            <div class="flex -space-x-2">
                                @foreach ($room->rentals->take(3) as $rental)
                                    <div class="h-6 w-6 rounded-full bg-indigo-50 dark:bg-indigo-500/10 ring-2 ring-white dark:ring-gray-800 flex items-center justify-center">
                                        <span class="text-[10px] font-medium text-indigo-600 dark:text-indigo-400">
                                            {{ strtoupper(substr($rental->tenant->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endforeach
                                @if($room->rentals->count() > 3)
                                    <div class="h-6 w-6 rounded-full bg-indigo-50 dark:bg-indigo-500/10 ring-2 ring-white dark:ring-gray-800 flex items-center justify-center">
                                        <span class="text-[10px] font-medium text-indigo-600 dark:text-indigo-400">
                                            +{{ $room->rentals->count() - 3 }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $room->rentals->count() }} {{ Str::plural('tenant', $room->rentals->count()) }}
                            </span>
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center">
                    <div class="mx-auto w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No rooms found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Create your first room to get started managing your properties.</p>
                    <a href="{{ route('rooms.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Room
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
</div>
@endsection
