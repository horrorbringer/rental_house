@extends('layouts.dashboard')

@section('title', 'Create Building')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex p-0 text-sm list-none">
                <li class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 transition-colors duration-200 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Dashboard</a>
                    <svg class="w-4 h-4 mx-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('buildings.index') }}" class="text-gray-500 transition-colors duration-200 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Buildings</a>
                    <svg class="w-4 h-4 mx-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                <li><span class="text-gray-700 dark:text-gray-300">Create New</span></li>
            </ol>
        </nav>

        <div class="p-8 bg-gray-800 shadow-xl rounded-2xl">
            <div class="flex items-center mb-6">
                <div class="flex items-center justify-center w-10 h-10 mr-4 bg-indigo-500 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-100">Create New Building</h2>
            </div>

            @if ($errors->any())
                <div class="px-4 py-3 mb-6 text-red-500 bg-red-500 border border-red-500 rounded-lg bg-opacity-10">
                    <div class="font-medium">Please fix the following errors:</div>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                    <!-- Form -->
        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('buildings.store') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Building Name</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 h-10 block w-full rounded-md border-gray-300 dark:border-gray-600
                               shadow-sm focus:border-blue-500 focus:ring-blue-500
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                               @error('name') border-red-500 dark:border-red-500 @enderror"
                        value="{{ old('name') }}"
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                               shadow-sm focus:border-blue-500 focus:ring-blue-500
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200
                               @error('address') border-red-500 dark:border-red-500 @enderror"
                        required>{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="block w-full mt-1 text-gray-900 bg-white border-gray-300 rounded-md shadow-sm dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Information -->
                <div>
                    <label for="contact_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Information</label>
                    <input type="text" name="contact_info" id="contact_info"
                        class="block h-10 w-full rounded-md border-gray-300 bg-white text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('contact_info') }}">
                    @error('contact_info')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('buildings.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-md dark:border-gray-600 dark:text-gray-200 dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Building
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
