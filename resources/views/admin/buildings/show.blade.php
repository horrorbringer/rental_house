@extends('layouts.dashboard')

@section('title', $building->name)

@push('styles')
<style>
    .hardware-accelerated {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
        will-change: transform;
    }
    .stat-card {
        background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.1), rgba(249, 250, 251, 0.05));
        backdrop-filter: blur(8px);
    }
    .light-mode .stat-card {
        background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.8), rgba(249, 250, 251, 0.9));
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    .image-gallery img {
        transition: transform 0.2s ease-in-out;
    }
    .image-gallery img:hover {
        transform: scale(1.05);
    }
    .image-gallery .active {
        border: 2px solid #6366f1;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="buildingDetails()">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $building->name }}</h1>
                <p class="text-gray-400 flex items-center mt-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $building->address }}
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <div class="stat-card rounded-xl p-6 border border-gray-200/20 dark:border-gray-700/50 hardware-accelerated">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Rooms</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $building->rooms->count() }}</h3>
                </div>
                <div class="p-3 bg-indigo-100 dark:bg-indigo-500/10 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-xl p-6 border border-gray-200/20 dark:border-gray-700/50 hardware-accelerated">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Available Rooms</p>
                    <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ $building->rooms->where('status', 'vacant')->count() }}
                    </h3>
                </div>
                <div class="p-3 bg-emerald-100 dark:bg-emerald-500/10 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-xl p-6 border border-gray-200/20 dark:border-gray-700/50 hardware-accelerated">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Occupancy Rate</p>
                    <h3 class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                        {{ $building->rooms->count() > 0 
                            ? round(($building->rooms->where('status', 'occupied')->count() / $building->rooms->count()) * 100)
                            : 0 }}%
                    </h3>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-500/10 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5M8 8v8m8-16l4 4-4 4m-4-4h8M3 4h18M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-2xl shadow-xl p-6 hardware-accelerated">
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-2xl font-semibold text-gray-100">{{ $building->name }}</h2>
            <div class="flex space-x-3">
                <a href="{{ route('buildings.edit', $building) }}"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Edit 
                </a>
                <form action="{{ route('buildings.destroy', $building) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                        onclick="return confirm('Are you sure you want to delete this building?')">
                        Delete 
                    </button>
                </form>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Building Information -->
            <div class="space-y-6">
                <!-- Building Images -->
                <div class="bg-gray-700/50 rounded-xl overflow-hidden border border-gray-700/50">
                    @if($building->images->count() > 0)
                        <!-- Primary Image -->
                        <div class="relative aspect-video">
                            @php
                                $primaryImage = $building->images->firstWhere('is_primary', true) 
                                    ?? $building->images->first();
                            @endphp
                            <img src="{{ Storage::url($primaryImage->path) }}" 
                                 alt="{{ $building->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <!-- Thumbnail Gallery -->
                        @if($building->images->count() > 1)
                            <div class="p-4 border-t border-gray-600/50">
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($building->images as $image)
                                        <div class="relative aspect-video rounded-lg overflow-hidden 
                                                    {{ $image->is_primary ? 'ring-2 ring-indigo-500' : '' }}">
                                            <img src="{{ Storage::url($image->path) }}" 
                                                 alt="Building image"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="aspect-video bg-gray-800 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-400">No images available</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Building Details -->
                <div class="bg-white dark:bg-gray-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700/50 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Building Information</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Owner</span>
                            <p class="mt-1 text-gray-900 dark:text-white font-medium">{{ $building->owner->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Contact Information</span>
                            <p class="mt-1 text-gray-900 dark:text-white font-medium">{{ $building->contact_info ?? 'Not provided' }}</p>
                        </div>
                        @if($building->description)
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Description</span>
                                <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $building->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700/50 shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('rooms.create', ['building' => $building->id]) }}"
                           class="flex items-center justify-between w-full p-3 bg-gray-50 dark:bg-gray-600/50 
                                  hover:bg-indigo-50 dark:hover:bg-gray-600 rounded-lg group 
                                  border border-gray-200 dark:border-gray-600
                                  hover:border-indigo-200 dark:hover:border-gray-500
                                  transition-all duration-200">
                            <span class="text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-white font-medium">
                                Add New Room
                            </span>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </a>
                        <a href="{{ route('utility-usages.index', ['building' => $building->id]) }}"
                           class="flex items-center justify-between w-full p-3 bg-gray-50 dark:bg-gray-600/50 
                                  hover:bg-indigo-50 dark:hover:bg-gray-600 rounded-lg group 
                                  border border-gray-200 dark:border-gray-600
                                  hover:border-indigo-200 dark:hover:border-gray-500
                                  transition-all duration-200">
                            <span class="text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-white font-medium">
                                Manage Utilities
                            </span>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Rooms List -->
            <div class="lg:col-span-2">
                <div class="bg-gray-700/50 rounded-xl border border-gray-700/50 overflow-hidden">
                    <div class="p-6 border-b border-gray-600/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-white">Rooms</h3>
                            <a href="{{ route('rooms.create', ['building' => $building->id]) }}"
                               class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Room
                            </a>
                        </div>
                    </div>

                    @if($building->rooms->count() > 0)
                        <div class="divide-y divide-gray-600/50">
                            @foreach($building->rooms as $room)
                                <div class="p-4 hover:bg-gray-600/20 transition-colors duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div @class([
                                                'w-2 h-2 rounded-full',
                                                'bg-emerald-400' => $room->status === 'vacant',
                                                'bg-orange-400' => $room->status === 'occupied',
                                                'bg-gray-400' => $room->status === 'maintenance'
                                            ])></div>
                                            <div>
                                                <h4 class="text-lg font-medium text-white">Room {{ $room->room_number }}</h4>
                                                <p class="text-sm text-gray-400">{{ ucfirst($room->type) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span @class([
                                                'px-3 py-1 text-xs font-medium rounded-full',
                                                'bg-emerald-400/10 text-emerald-400' => $room->status === 'vacant',
                                                'bg-orange-400/10 text-orange-400' => $room->status === 'occupied',
                                                'bg-gray-400/10 text-gray-400' => $room->status === 'maintenance'
                                            ])>
                                                {{ ucfirst($room->status) }}
                                            </span>
                                            <a href="{{ route('rooms.show', $room) }}"
                                               class="p-2 text-gray-400 hover:text-white rounded-lg transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-600/50 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-white mb-2">No Rooms Added Yet</h3>
                            <p class="text-gray-400 mb-6">Start adding rooms to this building to manage them.</p>
                            <a href="{{ route('rooms.create', ['building' => $building->id]) }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Your First Room
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('buildings.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200 hardware-accelerated">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Buildings
            </a>
        </div>

        <!-- Delete Confirmation Modal -->
        <template x-teleport="body">
            <div x-show="showDeleteModal" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="showDeleteModal = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                    <div x-show="showDeleteModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 hardware-accelerated"
                         @click.away="showDeleteModal = false">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-white">Delete Building</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">
                                        Are you sure you want to delete this building? This action cannot be undone.
                                        All associated rooms and data will be permanently removed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <form action="{{ route('buildings.destroy', $building) }}" method="POST" class="sm:ml-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                                    Delete Building
                                </button>
                            </form>
                            <button type="button" 
                                    @click="showDeleteModal = false"
                                    class="mt-3 inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-300 bg-gray-700 border border-gray-600 rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function buildingDetails() {
    return {
        showDeleteModal: false,
        confirmDelete() {
            this.showDeleteModal = true;
        }
    }
}
</script>
@endpush

@endsection
