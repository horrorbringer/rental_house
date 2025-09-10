@extends('layouts.dashboard')

@section('title', 'Create Building')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start sm:items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Building</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add a new building to your property management system</p>
                </div>
            </div>

            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <li>
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-200 transition-colors duration-150">
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

        <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700">
            <!-- Success Message -->
            @if(session('success'))
                <div class="px-4 py-3 mb-6 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="px-4 py-3 mb-6 text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
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
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
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
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Building Name <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                    name="name" 
                                    id="name" 
                                    value="{{ old('name') }}"
                                    required
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                                           border border-gray-300 dark:border-gray-600 
                                           focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                           focus:border-indigo-500 dark:focus:border-indigo-400
                                           placeholder-gray-400 dark:placeholder-gray-500
                                           sm:text-sm transition-colors duration-200"
                                    placeholder="Enter building name">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-span-2 sm:col-span-1">
                            <label for="contact_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contact Information
                            </label>
                            <div class="mt-1 relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                    name="contact_info" 
                                    id="contact_info" 
                                    value="{{ old('contact_info') }}"
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 
                                           text-gray-900 dark:text-gray-100 
                                           border border-gray-300 dark:border-gray-600 
                                           focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                           focus:border-indigo-500 dark:focus:border-indigo-400
                                           placeholder-gray-400 dark:placeholder-gray-500
                                           sm:text-sm transition-colors duration-200"
                                    placeholder="Enter contact information">
                                @error('contact_info')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-lg shadow-sm">
                                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <textarea 
                                    name="address" 
                                    id="address" 
                                    rows="3"
                                    required
                                    class="block w-full pl-10 pr-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 
                                           text-gray-900 dark:text-gray-100 
                                           border border-gray-300 dark:border-gray-600 
                                           focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                           focus:border-indigo-500 dark:focus:border-indigo-400
                                           placeholder-gray-400 dark:placeholder-gray-500
                                           sm:text-sm transition-colors duration-200
                                           resize-none"
                                    placeholder="Enter complete building address">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>                <!-- Description -->
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                            </svg>
                        </div>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="4"
                            class="block w-full pl-10 pr-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 
                                   text-gray-900 dark:text-gray-100 
                                   border border-gray-300 dark:border-gray-600 
                                   focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                   focus:border-indigo-500 dark:focus:border-indigo-400
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   sm:text-sm transition-colors duration-200
                                   resize-none"
                            placeholder="Enter detailed building description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
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
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors duration-200 group">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors duration-200" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <label class="relative cursor-pointer rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 focus-within:outline-none">
                                    <span>Upload images</span>
                                    <input type="file" 
                                           name="images[]" 
                                           class="sr-only" 
                                           multiple 
                                           accept="image/jpeg,image/png,image/webp">
                                </label>
                                <p>or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, or WEBP up to 5MB each</p>
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

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-6 py-4 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 w-full sm:w-auto order-1 sm:order-2">
                        <a href="{{ route('buildings.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 
                                   border border-gray-200 dark:border-gray-600 rounded-lg text-sm
                                   font-medium text-gray-700 dark:text-gray-300 
                                   bg-white dark:bg-gray-700/50 
                                   hover:bg-gray-50 hover:border-gray-300 
                                   dark:hover:bg-gray-700 dark:hover:border-gray-500
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 
                                   focus:ring-offset-white dark:focus:ring-offset-gray-800
                                   focus:ring-gray-400 dark:focus:ring-gray-400 
                                   transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            id="submitButton"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 
                                   border border-transparent rounded-lg text-sm font-medium 
                                   text-white bg-gradient-to-r from-indigo-600 to-indigo-700 
                                   hover:from-indigo-700 hover:to-indigo-800
                                   dark:from-indigo-500 dark:to-indigo-600
                                   dark:hover:from-indigo-600 dark:hover:to-indigo-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 
                                   focus:ring-offset-white dark:focus:ring-offset-gray-800
                                   focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                   transition-all duration-200 
                                   disabled:opacity-50 disabled:cursor-not-allowed 
                                   shadow-md hover:shadow-lg 
                                   active:scale-[0.98] transform">
                            <svg class="submit-icon w-5 h-5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg class="loading-icon hidden animate-spin -ml-1 mr-2 h-5 w-5 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            <span class="button-text">Create</span>
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
    const dropZone = document.querySelector('.border-dashed');

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
        const files = e.files || e.target.files;
        if (!files.length) return;

        // Clear previous previews
        while (previewContainer?.firstChild) {
            previewContainer.removeChild(previewContainer.firstChild);
        }

        Array.from(files).forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                const errorMessage = document.createElement('div');
                errorMessage.className = 'fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-xl flex items-center animate-fade-in';
                errorMessage.innerHTML = `
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>${file.name} is too large. Maximum size is 5MB</span>
                `;
                document.body.appendChild(errorMessage);
                setTimeout(() => errorMessage.remove(), 3000);
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                if (!previewContainer) return;

                const preview = document.createElement('div');
            preview.className = 'group relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-200';
            
            const img = document.createElement('img');
            img.src = event.target.result;
            img.className = 'w-full h-full object-cover transition-transform duration-200 group-hover:scale-105';
            
            // Add a loading state
            img.style.opacity = '0';
            img.onload = () => {
                img.style.transition = 'opacity 0.3s ease-in-out';
                img.style.opacity = '1';
            };
            
            preview.appendChild(img);
            
            // Add remove button
            const removeButton = document.createElement('button');
            removeButton.className = 'absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full opacity-0 transform translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200';
            removeButton.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `;
            removeButton.onclick = (e) => {
                e.preventDefault();
                preview.remove();
            };
            preview.appendChild(removeButton);
            
            previewContainer.appendChild(preview);
            };
            reader.readAsDataURL(file);
        });
    }

    // Drag and drop handling
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleImageUpload({files});
        }
    }

    // Event Listeners
    form.addEventListener('submit', handleSubmit);
    if (imageInput) {
        imageInput.addEventListener('change', handleImageUpload);
    }

    // Performance optimizations
    const debouncedResize = debounce(() => {
        if (previewContainer) {
            previewContainer.style.opacity = '1';
        }
    }, 100);

    if (previewContainer) {
        window.addEventListener('resize', () => {
            previewContainer.style.opacity = '0.6';
            debouncedResize();
        });
    }

    function debounce(fn, ms) {
        let timer;
        return () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                timer = null;
                fn.apply(this, arguments);
            }, ms);
        };
    }
});
</script>
@endpush
