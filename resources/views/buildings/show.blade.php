@extends('layouts.dashboard')

@section('title', 'Building Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800 rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-2xl font-semibold text-gray-100">{{ $building->name }}</h2>
            <div class="flex space-x-3">
                <a href="{{ route('buildings.edit', $building) }}"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    Edit Building
                </a>
                <form action="{{ route('buildings.destroy', $building) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                        onclick="return confirm('Are you sure you want to delete this building?')">
                        Delete Building
                    </button>
                </form>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-300">Building Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Address:</span>
                        <span class="text-gray-200">{{ $building->address }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Owner:</span>
                        <span class="text-gray-200">{{ $building->owner->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Rooms:</span>
                        <span class="text-gray-200">{{ $building->rooms->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-300">Rooms</h3>
                @if($building->rooms->count() > 0)
                    <div class="space-y-3">
                        @foreach($building->rooms as $room)
                            <div class="bg-gray-700 p-4 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-200">Room {{ $room->room_number }}</span>
                                    <span class="text-sm px-2 py-1 rounded {{ $room->status === 'occupied' ? 'bg-green-500' : 'bg-gray-500' }} bg-opacity-20 text-{{ $room->status === 'occupied' ? 'green' : 'gray' }}-400">
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-400">
                                    {{ $room->type }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400">No rooms found in this building.</p>
                @endif
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-700">
            <a href="{{ route('buildings.index') }}"
                class="bg-gray-600 hover:bg-gray-500 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                Back to Buildings
            </a>
        </div>
    </div>
</div>
@endsection
