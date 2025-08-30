@extends('layouts.dashboard')

@section('title', 'Dashboard')

@php
    // Calculate total monthly revenue
    $totalRevenue = \App\Models\Room::whereHas('rental', function($query) {
        $query->where('status', 'active');
    })->sum('monthly_rent');

    // Get counts with a single query
    $buildings = \App\Models\Building::count();
    $rooms = \App\Models\Room::count();
    $vacantRooms = \App\Models\Room::where('status', 'vacant')->count();
    $occupiedRooms = $rooms - $vacantRooms;
    $occupancyRate = $rooms > 0 ? round(($occupiedRooms / $rooms) * 100) : 0;

    // Get recent activities
    $recentActivities = collect();

    // Get recent tenants
    $recentTenants = \App\Models\Tenant::latest()->take(3)->get()->map(function($tenant) {
        return [
            'type' => 'tenant',
            'message' => "New tenant registered",
            'subject' => $tenant->name,
            'date' => $tenant->created_at,
            'color' => 'blue'
        ];
    });
    $recentActivities = $recentActivities->concat($recentTenants);

    // Get recent room status changes
    $recentRoomChanges = \App\Models\Room::with('building')
        ->whereNotNull('updated_at')
        ->latest()
        ->take(3)
        ->get()
        ->map(function($room) {
            return [
                'type' => 'room',
                'message' => "Room status updated",
                'subject' => "Room {$room->room_number} in {$room->building->name}",
                'date' => $room->updated_at,
                'color' => 'emerald'
            ];
        });
    $recentActivities = $recentActivities->concat($recentRoomChanges);

    // Sort all activities by date
    $recentActivities = $recentActivities->sortByDesc('date')->take(5);
@endphp

@section('content')
    <div class="px-6 py-8 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="mt-2 text-sm text-gray-400">Dashboard overview and summary</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('buildings.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add New Property
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Revenue Card -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg transition-all duration-200 hover:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-600/10 text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Monthly Revenue</p>
                        <p class="mt-1 text-2xl font-semibold text-white">${{ number_format($totalRevenue, 0) }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-blue-600/10 text-blue-400">
                        Active Rentals
                    </span>
                </div>
            </div>

            <!-- Properties Card -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg transition-all duration-200 hover:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-600/10 text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Total Properties</p>
                        <p class="mt-1 text-2xl font-semibold text-white">{{ $buildings }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-purple-600/10 text-purple-400">
                        Managed Buildings
                    </span>
                </div>
            </div>

            <!-- Rooms Card -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg transition-all duration-200 hover:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-emerald-600/10 text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Room Status</p>
                        <p class="mt-1 text-2xl font-semibold text-white">{{ $rooms }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center space-x-2 text-sm">
                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-emerald-600/10 text-emerald-400">
                        {{ $vacantRooms }} Available
                    </span>
                    <span class="inline-block px-2 py-1 text-xs rounded-full bg-orange-600/10 text-orange-400">
                        {{ $occupiedRooms }} Occupied
                    </span>
                </div>
            </div>

            <!-- Occupancy Rate Card -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg transition-all duration-200 hover:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-pink-600/10 text-pink-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Occupancy Rate</p>
                        <p class="mt-1 text-2xl font-semibold text-white">{{ $occupancyRate }}%</p>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="relative w-full h-2 bg-gray-700 rounded-full overflow-hidden">
                        <div class="absolute top-0 left-0 h-full bg-pink-500 rounded-full" style="width: {{ $occupancyRate }}%"></div>
                    </div>
                </div>
                    <div class="w-12 h-12 rounded-full bg-orange-900/50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Quick Actions Section -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Add Property Card -->
                    <a href="{{ route('buildings.create') }}" class="group bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg hover:border-gray-700 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 rounded-full bg-purple-600/10 text-purple-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white group-hover:text-purple-400 transition-colors">Add Property</h3>
                                    <p class="text-sm text-gray-400">Register a new building</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-purple-400 transition-colors transform group-hover:translate-x-1 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    <!-- Add Room Card -->
                    <a href="{{ route('rooms.create') }}" class="group bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg hover:border-gray-700 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 rounded-full bg-emerald-600/10 text-emerald-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white group-hover:text-emerald-400 transition-colors">Add Room</h3>
                                    <p class="text-sm text-gray-400">Create new rental units</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-emerald-400 transition-colors transform group-hover:translate-x-1 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    <!-- Add Tenant Card -->
                    <a href="{{ route('tenants.create') }}" class="group bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg hover:border-gray-700 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 rounded-full bg-orange-600/10 text-orange-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white group-hover:text-orange-400 transition-colors">Add Tenant</h3>
                                    <p class="text-sm text-gray-400">Register new tenants</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-orange-400 transition-colors transform group-hover:translate-x-1 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    <!-- Find Rooms Card -->
                    <a href="{{ route('rooms.search') }}" class="group bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg hover:border-gray-700 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 rounded-full bg-blue-600/10 text-blue-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition-colors">Find Rooms</h3>
                                    <p class="text-sm text-gray-400">Search available units</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-400 transition-colors transform group-hover:translate-x-1 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>

                <!-- Recent Activity Timeline -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg">
                    <h3 class="text-lg font-semibold text-white mb-6">Recent Activity</h3>
                    <div class="space-y-6">
                        @foreach($recentActivities as $activity)
                            <div class="relative pl-6 pb-6 last:pb-0 border-l border-gray-700 last:border-0">
                                <div class="absolute left-0 top-0 -translate-x-1/2 w-3 h-3 rounded-full bg-{{ $activity['color'] }}-500"></div>
                                <div class="bg-gray-700/30 rounded-lg p-4">
                                    <p class="text-sm font-medium text-white">{{ $activity['message'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $activity['subject'] }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $activity['date']->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Calendar and Upcoming Events -->
            <div class="space-y-6">
                <!-- Rent Collection Calendar -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white">Upcoming Rent Collection</h3>
                        <button class="text-sm text-blue-400 hover:text-blue-300 transition-colors">View All</button>
                    </div>
                    <div class="space-y-4">
                        @foreach(range(1, 3) as $i)
                        <div class="bg-gray-700/30 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="text-sm font-medium text-white">Room #10{{ $i }}</h4>
                                    <p class="text-xs text-gray-400">Building A</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $i === 1 ? 'bg-red-500/10 text-red-400' :
                                       ($i === 2 ? 'bg-yellow-500/10 text-yellow-400' :
                                                  'bg-green-500/10 text-green-400') }}">
                                    {{ $i === 1 ? 'Due Today' :
                                       ($i === 2 ? 'Due in 3 days' :
                                                  'Due in 7 days') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400">Tenant: John Doe</span>
                                <span class="text-white">$800</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Maintenance Requests -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700/50 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white">Maintenance Requests</h3>
                        <button class="text-sm text-blue-400 hover:text-blue-300 transition-colors">View All</button>
                    </div>
                    <div class="space-y-4">
                        @foreach(['Plumbing issue', 'Electrical repair', 'AC maintenance'] as $issue)
                        <div class="bg-gray-700/30 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-white">{{ $issue }}</h4>
                                <span class="px-2 py-1 text-xs rounded-full bg-orange-500/10 text-orange-400">Pending</span>
                            </div>
                            <p class="text-xs text-gray-400">Room #101 - Building A</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
