@extends('layouts.guest')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Search Filters -->
        <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <form action="{{ route('rooms.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="building" class="block text-sm font-medium text-gray-300">Building</label>
                    <input type="text" name="building" id="building" value="{{ request('building') }}"
                        class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="price_min" class="block text-sm font-medium text-gray-300">Min Price</label>
                    <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}"
                        class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="price_max" class="block text-sm font-medium text-gray-300">Max Price</label>
                    <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}"
                        class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-3">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Search Available Rooms
                    </button>
                </div>
            </form>
        </div>

        <!-- Room Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-200 mb-2">{{ $room->name }}</h3>
                    <p class="text-gray-400 mb-4">{{ $room->building->name }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl font-bold text-indigo-400">${{ number_format($room->price, 2) }}</span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Available
                        </span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('rooms.public.show', $room) }}"
                            class="w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $rooms->links() }}
        </div>
    </div>
</div>
@endsection
