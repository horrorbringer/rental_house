@extends('layouts.dashboard')
@section('title', 'Buildings')

@push('styles')
    <style>
        /* Enable hardware acceler                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Buildings -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Buildings</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $buildings->count() }}</h3>
                    </div>obally */
        .hardware-accelerated {
            transform: translateZ(0);
            backface-visibility: hidden;
            perspective: 1000px;
        }
        
        /* Smooth scrolling for the main container */
        .smooth-scroll {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* Minimal animations only for success messages */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Card hover effects */
        .building-card {
            transition: all 0.3s ease;
        }
        .building-card:hover {
            transform: translateY(-2px);
        }

        /* Optimize paint operations */
        .optimize-paint {
            contain: paint;
        }

        /* Custom scrollbar for light/dark modes */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgb(var(--color-gray-100));
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgb(var(--color-gray-300));
            border-radius: 3px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: rgb(var(--color-gray-800));
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgb(var(--color-gray-600));
        }
    </style>
@endpush

@section('content')
<div class="smooth-scroll hardware-accelerated" x-data="buildingList()">
    <div class="space-y-6 optimize-paint">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">អគារ</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">គ្រប់គ្រងអគារនិងឯកតារបស់អ្នក</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Stats Summary -->
                    <div class="hidden md:grid grid-cols-3 gap-4 items-center bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg px-4 py-3 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="text-center px-4">
                            <span class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $buildings->count() }}</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wider font-medium">អគារ</span>
                        </div>
                        <div class="text-center px-4 border-l border-r border-gray-200 dark:border-gray-700">
                            <span class="block text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $buildings->sum('vacant_rooms_count') }}</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wider font-medium">ទំនេរ</span>
                        </div>
                        <div class="text-center px-4">
                            <span class="block text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $buildings->sum('rooms_count') > 0 
                                ? round(($buildings->sum('rooms_count') - $buildings->sum('vacant_rooms_count')) / $buildings->sum('rooms_count') * 100) 
                                : 0 }}%</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wider font-medium">មានអ្នកជួល</span>
                        </div>
                    </div>

                    <!-- Add Building Button -->
                    <a href="{{ route('buildings.create') }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-indigo-500/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        បង្កើតអគារថ្មី
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="fixed top-4 right-4 z-50 animate-fade-in"
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

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Buildings -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">អគារសរុប</p>
                        <h3 class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $buildings->count() }}</h3>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Total Rooms -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">បន្ទប់សរុប</p>
                        <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $buildings->sum('rooms_count') }}</h3>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Available Rooms -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">ទំនេរ</p>
                        <h3 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $buildings->sum('vacant_rooms_count') }}</h3>
                    </div>
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Occupancy Rate -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">អត្រាអ្នកជួល</p>
                        <h3 class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                            {{ $buildings->sum('rooms_count') > 0 
                                ? round(($buildings->sum('rooms_count') - $buildings->sum('vacant_rooms_count')) / $buildings->sum('rooms_count') * 100) 
                                : 0 }}%
                        </h3>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5M8 8v8m8-16l4 4-4 4m-4-4h8M3 4h18M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-4 border border-gray-200 dark:border-gray-700/50">
            <div class="flex flex-wrap gap-4">
                <!-- Search Input -->
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="sr-only">Search buildings</label>
                    <div class="relative">
                        <input type="text" 
                               id="search" 
                               x-model.debounce.500ms="search" 
                               @input="filterBuildings"
                               class="w-full bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-200 rounded-lg pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-500 focus:border-transparent transition-colors duration-200"
                               placeholder="Search buildings...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Dropdown -->
                <div class="min-w-[150px]">
                    <select x-model="filter" 
                            @change="filterBuildings()"
                            class="w-full bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-200 rounded-lg py-2 px-3 border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-500 focus:border-transparent transition-colors duration-200">
                        <option value="all">អគារទាំងអស់</option>
                        <option value="with_vacant">មានបន្ទប់ទំនេរ</option>
                        <option value="fully_occupied">មានអ្នកជួលពេញ</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Buildings List -->
        <div class="space-y-4">
            <!-- Loading State -->
            <template x-if="loading">
                <div class="animate-pulse space-y-4">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/50 p-6">
                        <div class="h-6 bg-gray-700 rounded w-1/4 mb-4"></div>
                        <div class="h-4 bg-gray-700 rounded w-3/4"></div>
                    </div>
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg border border-gray-700/50 p-6">
                        <div class="h-6 bg-gray-700 rounded w-1/3 mb-4"></div>
                        <div class="h-4 bg-gray-700 rounded w-2/3"></div>
                    </div>
                </div>
            </template>

            <!-- Buildings List -->
            <template x-if="!loading">
                <div class="space-y-4">
                    <!-- No Buildings Found -->
                    <template x-if="filteredBuildings.length === 0">
                        <div class="bg-gray-800/50 backdrop-blur-sm p-8 rounded-2xl shadow-xl text-center border border-gray-700/50">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-900/50 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2" x-text="buildings.length === 0 ? 'No Buildings Yet' : 'No Buildings Found'"></h3>
                            <p class="text-gray-400 mb-6" x-text="buildings.length === 0 ? 'Start managing your properties by adding your first building.' : 'Try adjusting your search or filter criteria.'"></p>
                            <template x-if="buildings.length === 0">
                                <a href="{{ route('buildings.create') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-medium rounded-lg transition-all duration-200 group">
                                    <span>Add Your First Building</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </a>
                            </template>
                        </div>
                    </template>

                    <!-- Buildings Grid -->
                    <template x-for="building in filteredBuildings" :key="building.id">
                        <div class="building-card bg-white dark:bg-gray-800/50 rounded-xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700/50 transition-all duration-200"
                             :class="{'opacity-75': isDeleting === building.id}"
                             style="contain: content;">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4">
                                        <div class="p-3 bg-indigo-500/10 rounded-lg">
                                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-white" x-text="building.name"></h4>
                                            <p class="text-sm text-gray-400 flex items-center mt-1">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <span x-text="building.address"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <template x-if="building.description">
                                    <p class="text-sm text-gray-400 mt-4 border-t border-gray-700/50 pt-4" x-text="building.description"></p>
                                </template>

                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center space-x-6">
                                        <div class="flex items-center space-x-2">
                                            <div class="p-2 bg-indigo-500/10 rounded-lg">
                                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block text-2xl font-bold text-indigo-400" x-text="building.rooms_count"></span>
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
                                                <span class="block text-2xl font-bold" 
                                                      :class="building.vacant_rooms_count > 0 ? 'text-emerald-400' : 'text-gray-400'" 
                                                      x-text="building.vacant_rooms_count"></span>
                                                <span class="text-xs text-gray-400">ទំនេរ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <a :href="`/buildings/${building.id}`"
                                           class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg">
                                            <span>មើល</span>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function buildingList() {
    return {
        buildings: @json($buildings),
        search: '',
        filter: 'all',
        loading: false,
        isDeleting: null,
        filteredBuildings: @json($buildings),
        searchIndex: null,

        init() {
            // Pre-process buildings data for faster searching
            this.searchIndex = this.buildings.map(building => ({
                id: building.id,
                searchText: (building.name + ' ' + building.address + ' ' + (building.description || '')).toLowerCase(),
                vacant_rooms_count: building.vacant_rooms_count
            }));
            
            // Initial filter not needed as filteredBuildings is already set
        },

        filterBuildings() {
            if (!this.searchIndex) return;
            
            // Don't show loading state for fast operations
            const searchTerm = this.search.toLowerCase();
            const startTime = performance.now();
            
            // Use the pre-processed search index for faster filtering
            const filtered = !searchTerm && this.filter === 'all' 
                ? this.buildings // Return all buildings if no filters
                : this.searchIndex
                    .filter(idx => {
                        const matchesSearch = !searchTerm || idx.searchText.includes(searchTerm);
                        const matchesFilter = 
                            this.filter === 'all' ||
                            (this.filter === 'with_vacant' && idx.vacant_rooms_count > 0) ||
                            (this.filter === 'fully_occupied' && idx.vacant_rooms_count === 0);
                        
                        return matchesSearch && matchesFilter;
                    })
                    .map(idx => this.buildings.find(b => b.id === idx.id));
            
            // Only show loading state if operation takes more than 100ms
            const endTime = performance.now();
            if (endTime - startTime > 100) {
                this.loading = true;
                requestAnimationFrame(() => {
                    this.filteredBuildings = filtered;
                    this.loading = false;
                });
            } else {
                this.filteredBuildings = filtered;
            }
        },

        async deleteBuilding(buildingId) {
            if (!confirm('Are you sure you want to delete this building?')) return;

            this.isDeleting = buildingId;

            try {
                const response = await fetch(`/buildings/${buildingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Remove from both arrays
                    this.buildings = this.buildings.filter(b => b.id !== buildingId);
                    this.filterBuildings();

                    // Show success message
                    const successDiv = document.createElement('div');
                    successDiv.innerHTML = `
                        <div class="fixed top-4 right-4 z-50 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center space-x-3 animate-fade-in">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Building deleted successfully</span>
                        </div>
                    `;
                    document.body.appendChild(successDiv);
                    setTimeout(() => successDiv.remove(), 3000);
                } else {
                    throw new Error('Failed to delete building');
                }
            } catch (error) {
                alert('Failed to delete building. Please try again.');
            } finally {
                this.isDeleting = null;
            }
        }
    }
}
</script>
@endpush

@endsection
