@extends('layouts.dashboard')

@section('title', 'Buildings')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex text-sm">
            <li class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-300 transition-colors duration-200">Dashboard</a>
                <svg class="w-4 h-4 mx-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </li>
            <li><span class="text-gray-300">Buildings</span></li>
        </ol>
    </nav>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Buildings</h1>
                <p class="mt-1 text-sm text-gray-400">Manage your rental properties</p>
            </div>
            <a href="{{ route('buildings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Building
            </a>
        </div>

    @if(session('success'))
        <div class="bg-green-900/50 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($buildings as $building)
            <div class="bg-gray-800 rounded-xl shadow-sm border border-gray-700 hover:border-gray-600 transition-colors duration-200">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-semibold text-white mb-1">{{ $building->name }}</h4>
                            <p class="text-sm text-gray-400">{{ $building->address }}</p>
                        </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('buildings.edit', $building) }}" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('buildings.destroy', $building) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors duration-200" onclick="return confirm('Are you sure you want to delete this building?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-700">
                    @if($building->description)
                        <p class="text-sm text-gray-400 mb-2">{{ Str::limit($building->description, 100) }}</p>
                    @endif
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-400">Total Rooms:</span>
                        <span class="text-gray-300">{{ $building->rooms_count }}</span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-400">Available Rooms:</span>
                        <span class="text-gray-300">{{ $building->vacant_rooms_count }}</span>
                    </div>
                </div>
                <a href="{{ route('buildings.show', $building) }}" class="mt-4 block text-center bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    View Details
                </a>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-gray-800 p-6 rounded-2xl shadow-xl text-center">
                    <p class="text-gray-400">No buildings found.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
