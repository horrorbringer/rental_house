@extends('layouts.dashboard')

@section('title', 'Buildings')

@section('content')
<div class="px-6 py-8">

    <div class="space-y-6">
        <div class="bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">Buildings</h1>
                    <p class="mt-1 text-sm text-gray-400">Manage your rental properties and units</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center bg-gray-900/50 rounded-lg px-4 py-2">
                        <div class="text-center px-4">
                            <span class="block text-2xl font-bold text-indigo-400">{{ $buildings->count() }}</span>
                            <span class="text-xs text-gray-400 uppercase tracking-wider">Total Buildings</span>
                        </div>
                    </div>
                    <a href="{{ route('buildings.create') }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-indigo-500/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Building
                    </a>
                </div>
            </div>
        </div>

    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-4 right-4 z-50 animate-fade-in-down"
             @click.away="show = false">
            <div class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center space-x-3 transform hover:scale-105 transition-all duration-200">
                <svg class="w-6 h-6 shrink-0 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="shrink-0 ml-3 hover:text-white/80 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out;
        }
    </style>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-purple-500/10 to-indigo-500/10 rounded-xl p-4 border border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Buildings</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $buildings->count() }}</h3>
                </div>
                <div class="p-3 bg-purple-500/10 rounded-lg">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-xl p-4 border border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Rooms</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $buildings->sum('rooms_count') }}</h3>
                </div>
                <div class="p-3 bg-blue-500/10 rounded-lg">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-xl p-4 border border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Available Rooms</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $buildings->sum('vacant_rooms_count') }}</h3>
                </div>
                <div class="p-3 bg-emerald-500/10 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500/10 to-red-500/10 rounded-xl p-4 border border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-400">Occupancy Rate</p>
                    <h3 class="text-2xl font-bold text-white mt-1">
                        {{ $buildings->sum('rooms_count') > 0 
                            ? round(($buildings->sum('rooms_count') - $buildings->sum('vacant_rooms_count')) / $buildings->sum('rooms_count') * 100) 
                            : 0 }}%
                    </h3>
                </div>
                <div class="p-3 bg-orange-500/10 rounded-lg">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5M8 8v8m8-16l4 4-4 4m-4-4h8M3 4h18M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($buildings as $building)
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/50">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-indigo-500/10 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white">{{ $building->name }}</h4>
                                <p class="text-sm text-gray-400 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $building->address }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($building->description)
                        <p class="text-sm text-gray-400 mt-4 border-t border-gray-700/50 pt-4">{{ Str::limit($building->description, 100) }}</p>
                    @endif

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center space-x-2">
                                <div class="p-2 bg-indigo-500/10 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-2xl font-bold text-indigo-400">{{ $building->rooms_count }}</span>
                                    <span class="text-xs text-gray-400">Total Rooms</span>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <div class="p-2 bg-emerald-500/10 rounded-lg">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-2xl font-bold {{ $building->vacant_rooms_count > 0 ? 'text-emerald-400' : 'text-gray-400' }}">
                                        {{ $building->vacant_rooms_count }}
                                    </span>
                                    <span class="text-xs text-gray-400">Available</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('buildings.show', $building) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg">
                                <span>View Details</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="{{ route('buildings.edit', $building) }}" 
                               class="p-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('buildings.destroy', $building) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this building?')"
                                        class="p-2 bg-gray-700 hover:bg-red-600 text-white rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-gray-800/50 backdrop-blur-sm p-8 rounded-2xl shadow-xl text-center border border-gray-700/50">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-900/50 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No Buildings Yet</h3>
                    <p class="text-gray-400 mb-6">Start managing your properties by adding your first building.</p>
                    <a href="{{ route('buildings.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-medium rounded-lg transition-all duration-200 group">
                        <span>Add Your First Building</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
