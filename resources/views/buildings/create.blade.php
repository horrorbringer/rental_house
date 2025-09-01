@extends('layouts.dashboard')

@section('title', 'Create Building')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-indigo-600 bg-opacity-90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-100">Create New Building</h1>
                    <p class="mt-1 text-sm text-gray-400">Add a new building to your property management system</p>
                </div>
            </div>

            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-400">
                    <li>
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-200 transition-colors duration-150">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                    <li>
                        <a href="{{ route('buildings.index') }}" class="hover:text-gray-200 transition-colors duration-150">
                            Buildings
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                    <li>
                        <span class="text-gray-200">Create New</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="p-8 bg-gray-800 shadow-xl rounded-2xl">
            <!-- Success Message -->
            @if(session('success'))
                <div class="px-4 py-3 mb-6 text-green-500 bg-green-500 border border-green-500 rounded-lg bg-opacity-10">
                    <div class="font-medium">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="px-4 py-3 mb-6 text-red-500 bg-red-500 border border-red-500 rounded-lg bg-opacity-10">
                    <div class="font-medium">{{ session('error') }}</div>
                </div>
            @endif

            <!-- Laravel Validation Errors -->
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

        <!-- Form Container -->
        <div class="bg-gray-800 shadow-xl rounded-xl overflow-hidden">
            <form action="{{ route('buildings.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="relative"
                  id="buildingForm">
                @csrf

                <!-- Form Content -->
                <div class="p-6 space-y-6">
                    <!-- Building Information Section -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Name -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="name" class="block text-sm font-medium text-gray-300">
                                Building Name <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                    name="name" 
                                    id="name" 
                                    value="{{ old('name') }}"
                                    required
                                    class="block w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Enter building name">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="contact_info" class="block text-sm font-medium text-gray-300">
                                Contact Information
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                    name="contact_info" 
                                    id="contact_info" 
                                    value="{{ old('contact_info') }}"
                                    class="block w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Enter contact information">
                                @error('contact_info')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-300">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <textarea 
                                    name="address" 
                                    id="address" 
                                    rows="3"
                                    required
                                    class="block w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Enter building address">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>                <!-- Description -->
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                    <div class="mt-1">
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="4"
                            class="block w-full rounded-md bg-gray-700 text-gray-200 border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Enter building description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Building Images -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Building Images 
                        <span class="text-xs text-gray-400">(Max 5 images, 5MB each)</span>
                    </label>

                    <!-- Image Upload Area -->
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-lg hover:border-indigo-500 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-400">
                                <label class="relative cursor-pointer bg-gray-800 rounded-md font-medium text-indigo-500 hover:text-indigo-400 focus-within:outline-none">
                                    <span>Upload images</span>
                                    <input type="file" 
                                           name="images[]" 
                                           class="sr-only" 
                                           multiple 
                                           accept="image/jpeg,image/png,image/webp">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG, or WEBP up to 5MB each</p>
                        </div>
                    </div>

                    <!-- Image Preview Container -->
                    <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    </div>

                    @error('images')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between px-6 py-4 bg-gray-900/50 border-t border-gray-700">
                    <span class="text-sm text-gray-500">
                        All fields marked with <span class="text-red-500">*</span> are required
                    </span>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('buildings.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg text-sm
                                   font-medium text-gray-300 bg-transparent hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            id="submitButton"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg
                                   text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800
                                   focus:ring-indigo-500 transition-colors">
                            <svg class="submit-icon w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg class="loading-icon hidden animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            <span class="button-text">Create Building</span>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('buildingForm');
    const submitButton = document.getElementById('submitButton');
    const buttonText = submitButton.querySelector('.button-text');
    const submitIcon = submitButton.querySelector('.submit-icon');
    const loadingIcon = submitButton.querySelector('.loading-icon');
    const imageInput = document.querySelector('input[type="file"]');
    const previewContainer = document.getElementById('imagePreview');

    function handleSubmit(e) {
        if (submitButton.disabled) return;

        // Show loading state
        submitButton.disabled = true;
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        submitIcon.classList.add('hidden');
        loadingIcon.classList.remove('hidden');
        buttonText.textContent = 'Creating...';
    }

    function handleImageUpload(e) {
        const files = e.target.files;
        if (!files.length) return;

        // Clear previous previews
        while (previewContainer?.firstChild) {
            previewContainer.removeChild(previewContainer.firstChild);
        }

        Array.from(files).forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                alert(`${file.name} is too large. Maximum size is 5MB`);
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                if (!previewContainer) return;

                const preview = document.createElement('div');
                preview.className = 'relative aspect-video bg-gray-700 rounded-lg overflow-hidden';
                
                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'w-full h-full object-cover';
                
                preview.appendChild(img);
                previewContainer.appendChild(preview);
            };
            reader.readAsDataURL(file);
        });
    }

    // Event Listeners
    form.addEventListener('submit', handleSubmit);
    if (imageInput) {
        imageInput.addEventListener('change', handleImageUpload);
    }
});
</script>
@endpush
