@extends('layouts.dashboard')

@section('title', 'Rooms')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h3 class="text-2xl font-semibold">Rooms Management</h3>
            <p class="text-gray-400 mt-1">Manage your building rooms and their status</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 4v16m8-8H4"/>
            </svg>
            Add New Room
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 rounded-lg p-4">
        <form action="{{ route('rooms.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="building_id" class="block text-sm font-medium text-gray-300 mb-1">Building</label>
                <select name="building_id" id="building_id" class="w-full rounded-lg bg-gray-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white">
                    <option value="">All Buildings</option>
                    @foreach($buildings as $building)
                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-lg bg-gray-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white">
                    <option value="">All Status</option>
                    <option value="{{ App\Models\Room::STATUS_VACANT }}"
                        {{ request('status') == App\Models\Room::STATUS_VACANT ? 'selected' : '' }}>Vacant</option>
                    <option value="{{ App\Models\Room::STATUS_OCCUPIED }}"
                        {{ request('status') == App\Models\Room::STATUS_OCCUPIED ? 'selected' : '' }}>Occupied</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-500 bg-opacity-10 border border-green-500 text-green-500 px-4 py-3 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($rooms as $room)
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-lg font-semibold text-white">Room {{ $room->room_number }}</h4>
                            <p class="text-sm text-gray-400">{{ $room->building->name }}</p>
                        </div>
                        <span @class([
                            'px-2 py-1 text-xs font-medium rounded-full',
                            'bg-green-500 bg-opacity-10 text-green-500' => $room->status === App\Models\Room::STATUS_VACANT,
                            'bg-red-500 bg-opacity-10 text-red-500' => $room->status === App\Models\Room::STATUS_OCCUPIED,
                        ])>
                            {{ ucfirst($room->status) }}
                        </span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Monthly Rent</span>
                            <span class="text-white font-medium">₱{{ number_format($room->monthly_rent, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Water Fee</span>
                            <span class="text-white">₱{{ number_format($room->water_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">Electric Fee</span>
                            <span class="text-white">₱{{ number_format($room->electric_fee, 2) }}</span>
                        </div>
                    </div>

                    @if($room->rental)
                        <div class="border-t border-gray-700 pt-4 mt-4">
                            <p class="text-sm text-gray-400">Current Tenant</p>
                            <p class="text-white">{{ $room->rental->tenant->name }}</p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-850 px-5 py-4 border-t border-gray-700">
                    <div class="flex justify-between">
                        <a href="{{ route('rooms.edit', $room) }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">
                            Edit Room
                        </a>
                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this room?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-gray-800 rounded-lg p-6 text-center">
                    <p class="text-gray-400">No rooms found. Create your first room to get started.</p>
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
