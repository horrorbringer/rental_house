@foreach ($rooms as $room)
    <div class="room-card group relative bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-[1.02]">
        <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-700">
            @if ($room->image_path)
                <img src="{{ Storage::url($room->image_path) }}" alt="Room {{ $room->room_number }}" class="object-cover w-full h-full">
            @else
                <div class="flex items-center justify-center h-full bg-gray-200 dark:bg-gray-700">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            @endif
        </div>
        
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Room {{ $room->room_number }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $room->building->name }}
                    </p>
                </div>
                <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                    ฿{{ number_format($room->monthly_rent) }}
                </p>
            </div>
            
            <div class="mt-4">
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    {{ $room->building->address }}
                </div>
                
                <div class="flex items-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                    Utilities: Water ฿{{ number_format($room->water_fee) }}, Electric ฿{{ number_format($room->electric_fee) }}
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('rooms.show', $room) }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:hover:bg-indigo-500 dark:focus:ring-offset-gray-900">
                    View Details
                </a>
            </div>
        </div>
    </div>
@endforeach
