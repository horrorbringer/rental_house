@extends('layouts.guest')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Hanuman:wght@100..900&display=swap');
    .font-hanuman {
        font-family: 'Hanuman', serif;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search Filters -->
    <div class="mb-8">
        <form action="{{ route('tenant.rooms.search') }}" method="GET" class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
            <div class="space-y-6">
                <!-- Price Range -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 font-hanuman">តម្លៃជួល</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">តម្លៃអប្បបរមា ($)</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                       class="pl-7 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">តម្លៃអតិបរមា ($)</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                       class="pl-7 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="1000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Building Selection -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 font-hanuman">ទីតាំង</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">អគារ</label>
                        <select name="building" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="" class="font-hanuman">ជ្រើសរើសអគារ</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}" {{ request('building') == $building->id ? 'selected' : '' }}
                                        class="font-hanuman">
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 font-hanuman">ស្ថានភាព</h3>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="all" 
                                   {{ !request('status') || request('status') === 'all' ? 'checked' : '' }}
                                   class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="ml-2 text-gray-700 dark:text-gray-300 font-hanuman">ទាំងអស់</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="available" 
                                   {{ request('status') === 'available' ? 'checked' : '' }}
                                   class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="ml-2 text-gray-700 dark:text-gray-300 font-hanuman">បន្ទប់ទំនេរ</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center justify-between">
                @if(request()->hasAny(['min_price', 'max_price', 'building', 'status']))
                    <a href="{{ route('tenant.rooms.index') }}" 
                       class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-hanuman">
                        សម្អាតការស្វែងរក
                    </a>
                @else
                    <div></div>
                @endif
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-hanuman transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    ស្វែងរក
                </button>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-hanuman">
            បន្ទប់សរុប ({{ $rooms->total() }})
        </h2>
    </div>

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($rooms as $room)
        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
            <div class="aspect-w-16 aspect-h-9 overflow-hidden">
                @if($room->images->isNotEmpty())
                    <img src="{{ Storage::url($room->images->first()->path) }}" 
                         alt="Room {{ $room->room_number }}"
                         class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-500">
                @else
                    <img src="{{ asset('images/living-room-4037295_640.jpg') }}" 
                         alt="Room {{ $room->room_number }}"
                         class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-500">
                @endif
            </div>
            <div class="p-6">
                <!-- Room Header -->
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white font-hanuman">
                            បន្ទប់លេខ {{ $room->room_number }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1 font-hanuman">
                            {{ $room->building->name }}
                        </p>
                    </div>
                    @if($room->is_available)
                        <span class="flex items-center px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full font-hanuman">
                            <span class="w-2 h-2 bg-green-600 rounded-full mr-1.5"></span>
                            ទំនេរ
                        </span>
                    @else
                        <span class="flex items-center px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full font-hanuman">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-1.5"></span>
                            មានអ្នកជួលរួច
                        </span>
                    @endif
                </div>

                <!-- Room Details -->
                <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="font-hanuman">{{ $room->floor ?? 'ជាន់ផ្ទាល់ដី' }}</span>
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                        <span class="font-hanuman">{{ $room->width }}x{{ $room->length }}m</span>
                    </div>
                </div>

                <!-- Price and Actions -->
                <div class="mt-6 flex justify-between items-center">
                    <div>
                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 font-hanuman">
                            ${{ number_format($room->monthly_rent, 2) }}
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-400 font-hanuman">/ខែ</span>
                    </div>
                    <div class="flex space-x-2">
                        @if($room->is_available)
                            <a href="{{ route('tenant.rooms.show', $room) }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-300 font-hanuman">
                                ជួល
                            </a>
                        @endif
                        <a href="{{ route('tenant.rooms.show', $room) }}" 
                           class="inline-flex items-center px-3 py-2 border border-indigo-600 text-sm font-medium rounded-lg text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-300 font-hanuman">
                            មើលបន្ថែម
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="rounded-full bg-gray-100 dark:bg-gray-800 w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2 font-hanuman">
                មិនមានបន្ទប់
            </h3>
            <p class="text-gray-500 dark:text-gray-400 font-hanuman">
                មិនមានបន្ទប់ដែលត្រូវនឹងលក្ខខណ្ឌស្វែងរករបស់អ្នកទេ
            </p>
            @if(request()->hasAny(['min_price', 'max_price', 'building', 'status']))
                <a href="{{ route('tenant.rooms.index') }}" 
                   class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors duration-300 font-hanuman">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    ត្រឡប់ទៅមើលបន្ទប់ទាំងអស់
                </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border border-gray-200 dark:border-gray-700 rounded-lg sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($rooms->previousPageUrl())
                    <a href="{{ $rooms->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 font-hanuman">
                        មុន
                    </a>
                @endif
                @if($rooms->nextPageUrl())
                    <a href="{{ $rooms->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 font-hanuman">
                        បន្ទាប់
                    </a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 font-hanuman">
                        កំពុងបង្ហាញ
                        <span class="font-medium">{{ $rooms->firstItem() ?? 0 }}</span>
                        ដល់
                        <span class="font-medium">{{ $rooms->lastItem() ?? 0 }}</span>
                        នៃ
                        <span class="font-medium">{{ $rooms->total() }}</span>
                        បន្ទប់
                    </p>
                </div>
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
