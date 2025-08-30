@extends('layouts.dashboard')

@section('title', 'Edit Building')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gray-800 rounded-2xl shadow-xl p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-100">Edit Building</h2>
                    <form method="POST" action="{{ route('buildings.update', $building) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $building->name) }}" required
                                class="mt-1 block w-full rounded-lg bg-gray-700 border-transparent focus:border-indigo-500 focus:ring-indigo-500 text-white">
                            @error('name')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-300">Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $building->address) }}" required
                                class="mt-1 block w-full rounded-lg bg-gray-700 border-transparent focus:border-indigo-500 focus:ring-indigo-500 text-white">
                            @error('address')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('buildings.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Building
                            </button>
                        </div>
                    </form>
        </div>
    </div>
</div>
@endsection
