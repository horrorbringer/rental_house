@extends('layouts.dashboard')
@section('content')
{{-- quick add utility usage --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quick Add Rental for Room {{ $rental->room->room_number }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new rental and initialize utility readings for this room.</p>
    </div>

    <!-- Breadcrumbs -->
    <nav class="text-sm mb-6" aria-label="Breadcrumb">
        <ol class="list-reset
            flex text-gray-500 dark:text-gray-400">
            <li>
                <a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:underline">Dashboard</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li>
                <a href="{{ route('rooms.index') }}" class="text-gray-500 dark:text-gray-400 hover:underline">Rooms</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li>
                <a href="{{ route('rooms.show', $rental->room) }}" class="text-gray-500 dark:text-gray-400 hover:underline">Room {{ $rental->room->room_number }}</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 dark:text-white font-semibold">Add utility usage</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="bg-green-500 bg-opacity-10 border border-green-500 text-green-500 px-4 py-3 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <!--utility usage Information 4 fields (water_usage, electric_usage, reading_date , notes is optional) -->
    <form action="{{ route('rooms.store-quick-utility', $rental) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="{{$initializeUtility ? 'grid grid-cols-1 md:grid-cols-2 gap-6' : ''}} bg-white-800 dark:bg-gray-800 px-4 py-5 shadow sm:rounded-lg sm:p-6 ">
            @if($initializeUtility)
                <div>
                    <label for="water_usage" class="block text-sm font-medium text-gray-600 dark:text-white">Initial Water Reading (m³) <span class="text-red-500">*</span></label>
                    <input type="number" name="water_usage" id="water_usage" value="{{ old('water_usage') }}" required
                        class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('water_usage')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="electric_usage" class="block text-sm font-medium text-gray-600 dark:text-white">Initial Electric Reading (kWh) <span class="text-red-500">*</span></label>
                    <input type="number" name="electric_usage" id="electric_usage" value="{{ old('electric_usage') }}" required
                        class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('electric_usage')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reading_date" class="block text-sm font-medium text-gray-600 dark:text-white">Reading Date <span class="text-red-500">*</span></label>
                    <input type="date" name="reading_date" id="reading_date" value="{{ old('reading_date') }}" required
                        class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('reading_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-600 dark:text-white">Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-300">Utility readings have already been initialized for this room.</p>
            @endif
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('rooms.index') }}"
                class="inline-flex justify-center rounded-md border border-gray-700 bg-gray-800 py-2 px-4 text-sm font-medium text-gray-300 shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Save Rental
            </button>
        </div>
    </form>


@endsection
