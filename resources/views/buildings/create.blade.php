@extends('layouts.dashboard')

@section('title', 'Create Building')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex text-sm">
                <li class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">Dashboard</a>
                    <svg class="w-4 h-4 mx-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                <li class="flex items-center">
                    <a href="{{ route('buildings.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">Buildings</a>
                    <svg class="w-4 h-4 mx-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                <li><span class="text-gray-700 dark:text-gray-300">Create New</span></li>
            </ol>
        </nav>

        <div class="bg-gray-800 rounded-2xl shadow-xl p-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-indigo-500 flex items-center justify-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-100">Create New Building</h2>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-500 bg-opacity-10 border border-red-500 text-red-500 px-4 py-3 rounded-lg">
                    <div class="font-medium">Please fix the following errors:</div>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                    <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <form action="{{ route('buildings.store') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Building Name</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
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
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                               shadow-sm focus:border-blue-500 focus:ring-blue-500
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Information -->
                <div>
                    <label for="contact_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Information</label>
                    <input type="text" name="contact_info" id="contact_info"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600
                               shadow-sm focus:border-blue-500 focus:ring-blue-500
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                        value="{{ old('contact_info') }}">
                    @error('contact_info')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('buildings.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600
                               text-sm font-medium rounded-md text-gray-700 dark:text-gray-200
                               bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500
                               transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent
                               text-sm font-medium rounded-md text-white
                               bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                               transition-colors duration-200">
                        Create Building
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
        </div>
    </div>
@endsection
