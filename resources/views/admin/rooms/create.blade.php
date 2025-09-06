@extends('layouts.dashboard')

@section('title', isset($room) ? 'Edit Room' : 'Create Room')

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .hardware-accelerated {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
        will-change: transform;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formValidation', () => ({
            formData: {
                room_number: '{{ old('room_number', $room->room_number ?? '') }}',
                monthly_rent: '{{ old('monthly_rent', $room->monthly_rent ?? '') }}',
                water_fee: '{{ old('water_fee', $room->water_fee ?? '0') }}',
                electric_fee: '{{ old('electric_fee', $room->electric_fee ?? '0') }}',
            },
            errors: {},
            debounceTimer: null,

            init() {
                // Set up debounced validation
                Object.keys(this.formData).forEach(field => {
                    this.$watch(`formData.${field}`, value => {
                        clearTimeout(this.debounceTimer);
                        this.debounceTimer = setTimeout(() => {
                            this.validateField(field, value);
                        }, 300);
                    });
                });
            },

            validateField(field, value) {
                const validations = {
                    room_number: {
                        required: !value,
                        message: 'Room number is required'
                    },
                    monthly_rent: {
                        negative: value < 0,
                        message: 'Amount cannot be negative'
                    },
                    water_fee: {
                        negative: value < 0,
                        message: 'Amount cannot be negative'
                    },
                    electric_fee: {
                        negative: value < 0,
                        message: 'Amount cannot be negative'
                    },
                    width: {
                        negative: value < 0,
                        message: 'Measurement cannot be negative'
                    },
                    length: {
                        negative: value < 0,
                        message: 'Measurement cannot be negative'
                    },
                    capacity: {
                        min: value < 1,
                        message: 'Capacity must be at least 1'
                    }
                };

                const validation = validations[field];
                if (validation) {
                    const hasError = Object.keys(validation)
                        .filter(key => key !== 'message')
                        .some(key => validation[key]);
                    
                    this.errors[field] = hasError ? validation.message : '';
                    
                    // Add visual feedback
                    const input = document.getElementById(field);
                    if (input) {
                        input.classList.toggle('border-red-500', hasError);
                        input.classList.toggle('dark:border-red-500', hasError);
                        input.classList.toggle('focus:border-red-500', hasError);
                        input.classList.toggle('dark:focus:border-red-500', hasError);
                        input.classList.toggle('focus:ring-red-500', hasError);
                        input.classList.toggle('dark:focus:ring-red-500', hasError);
                    }
                }
                
                // Trigger error message animation
                if (this.errors[field]) {
                    const errorElement = document.querySelector(`[data-error="${field}"]`);
                    if (errorElement) {
                        errorElement.classList.remove('animate-fade-in');
                        void errorElement.offsetWidth; // Force reflow
                        errorElement.classList.add('animate-fade-in');
                    }
                }
            }
        }));
    });
</script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white inline-flex items-center">
                    <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('rooms.index') }}" class="text-gray-400 hover:text-white ml-1 md:ml-2">Rooms</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="text-gray-500 ml-1 md:ml-2">{{ isset($room) ? 'Edit Room' : 'Create Room' }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-6 mb-6 transition-all duration-200">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 shadow-lg flex items-center justify-center transform transition-transform duration-200 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ isset($room) ? 'Edit Room' : 'Create New Room' }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Fill in the details below to {{ isset($room) ? 'update' : 'create' }} a room</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl shadow-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
            <!-- Room Information Section -->
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Room Information
                </h3>
                <p class="text-sm text-gray-400">Basic details about the room and its location</p>
            </div>
            <!-- Pricing Section -->
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pricing & Fees
                </h3>
                <p class="text-sm text-gray-400">Set the room's rental rates and utility fees</p>
            </div>
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

        <form method="POST" action="{{ isset($room) ? route('rooms.update', $room) : route('rooms.store') }}" 
            class="space-y-6" 
            enctype="multipart/form-data"
            x-data="{ 
                isUploading: false,
                validateForm() {
                    const fileInput = this.$refs.mainImage;
                    const additionalImages = this.$refs.additionalImages;
                    
                    if (fileInput && fileInput.files[0] && fileInput.files[0].size > 5 * 1024 * 1024) {
                        alert('Main image must be less than 5MB');
                        return false;
                    }
                    
                    if (additionalImages) {
                        const files = Array.from(additionalImages.files);
                        if (files.length > 5) {
                            alert('Maximum 5 additional images allowed');
                            return false;
                        }
                        if (files.some(file => file.size > 5 * 1024 * 1024)) {
                            alert('Each additional image must be less than 5MB');
                            return false;
                        }
                    }
                    
                    this.isUploading = true;
                    return true;
                }
            }"
            x-on:submit.prevent="if (validateForm()) $el.submit()"
        >
            @csrf
            @if(isset($room))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Left Column - Room Information -->
                <div class="space-y-6">
                    <!-- Building Selection -->
                    <div class="relative group">
                        <label for="building_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Building Location
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <select name="building_id" 
                                id="building_id" 
                                required
                                class="pl-10 block w-full rounded-lg bg-white dark:bg-gray-700 
                                       border border-gray-300 dark:border-gray-600 
                                       text-gray-900 dark:text-gray-100 
                                       focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                       focus:border-indigo-500 dark:focus:border-indigo-400 
                                       transition-colors duration-200"
                                class="block w-full h-10 pl-3 pr-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                                <option value="">Select a building</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ (old('building_id', $room->building_id ?? '') == $building->id) ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('building_id')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Number -->
                    <div class="relative group">
                        <label for="room_number" class="block text-sm font-medium text-gray-300 mb-1">
                            Room Number
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                            </div>
                            <input type="text" name="room_number" id="room_number" required
                                value="{{ old('room_number', $room->room_number ?? '') }}"
                                class="block w-full h-10 pl-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                                placeholder="e.g., 101">
                        </div>
                        @error('room_number')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="relative group">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Room Status
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="status" 
                                id="status" 
                                required
                                class="block w-full rounded-lg bg-white dark:bg-gray-700 
                                       border border-gray-300 dark:border-gray-600 
                                       text-gray-900 dark:text-gray-100 
                                       focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                       focus:border-indigo-500 dark:focus:border-indigo-400 
                                       transition-colors duration-200"
                                class="block w-full h-10 pl-3 pr-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                                <option value="{{ App\Models\Room::STATUS_VACANT }}"
                                    {{ (old('status', $room->status ?? '') == App\Models\Room::STATUS_VACANT) ? 'selected' : '' }}>
                                    Vacant
                                </option>
                                <option value="{{ App\Models\Room::STATUS_OCCUPIED }}"
                                    {{ (old('status', $room->status ?? '') == App\Models\Room::STATUS_OCCUPIED) ? 'selected' : '' }}>
                                    Occupied
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Additional Room Images -->
                    <div class="relative group md:col-span-2" 
                        x-data="{ 
                            previews: [], 
                            maxFiles: 5,
                            removePreview(index) {
                                this.previews.splice(index, 1);
                                const dt = new DataTransfer();
                                const input = $refs.additionalImages;
                                const { files } = input;
                                
                                for(let i = 0; i < files.length; i++) {
                                    if(i !== index) dt.items.add(files[i]);
                                }
                                
                                input.files = dt.files;
                            }
                        }">
                        <label for="additional_images" class="block text-sm font-medium text-gray-300 mb-1">
                            Additional Room Images
                            <span class="text-gray-400 text-xs ml-1">(Max 5 images)</span>
                        </label>
                        <div class="relative space-y-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4" x-show="previews.length">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative aspect-video rounded-lg overflow-hidden bg-gray-700">
                                        <img :src="preview" class="w-full h-full object-cover" :alt="'Additional image ' + (index + 1)">
                                        <button type="button" @click="removePreview(index)"
                                            class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <input type="file" 
                                name="additional_images[]" 
                                id="additional_images" 
                                x-ref="additionalImages"
                                accept="image/jpeg,image/png,image/webp" 
                                multiple
                                @change="const files = Array.from($event.target.files).slice(0, maxFiles); 
                                        previews = files.map(file => URL.createObjectURL(file));"
                                :class="{ 'opacity-50 pointer-events-none': previews.length >= maxFiles }"
                                class="block w-full py-2 px-3 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200 file:mr-4 file:py-1 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                        </div>
                        @error('additional_images')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        @error('additional_images.*')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <div class="flex items-center gap-2 mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-gray-400">Supported formats: JPG, PNG, WEBP. Max file size: 5MB each.</p>
                        </div>
                    </div>

                </div>

                <!-- Right Column - Pricing Information -->
                <div class="space-y-6">
                    <!-- Monthly Rent -->
                <!-- Monthly Rent -->
                <div class="relative group">
                    <label for="monthly_rent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Monthly Rent
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400">₱</span>
                        </div>
                        <input type="number" 
                            name="monthly_rent" 
                            id="monthly_rent" 
                            required 
                            step="0.01"
                            x-model="formData.monthly_rent"
                            class="pl-8 block w-full rounded-lg bg-white dark:bg-gray-700 
                                   border border-gray-300 dark:border-gray-600 
                                   text-gray-900 dark:text-gray-100 
                                   focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                   focus:border-indigo-500 dark:focus:border-indigo-400 
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   transition-colors duration-200"
                            placeholder="0.00"                    <!-- Water Fee -->
                    <div class="relative group">
                        <label for="water_fee" class="block text-sm font-medium text-gray-300 mb-1">
                            Water Fee
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">₱</span>
                            </div>
                            <input type="number" name="water_fee" id="water_fee" required step="0.01"
                                value="{{ old('water_fee', $room->water_fee ?? '') }}"
                                class="pl-8 block w-full h-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                                placeholder="0.00">
                        </div>
                        @error('water_fee')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Electric Fee -->
                    <div class="relative group">
                        <label for="electric_fee" class="block text-sm font-medium text-gray-300 mb-1">
                            Electric Fee
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400">₱</span>
                            </div>
                            <input type="number" name="electric_fee" id="electric_fee" required step="0.01"
                                value="{{ old('electric_fee', $room->electric_fee ?? '') }}"
                                class="pl-8 block w-full h-10 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                                placeholder="0.00">
                        </div>
                        @error('electric_fee')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="relative group" x-data="{ preview: '{{ isset($room) && $room->image ? asset('storage/' . $room->image) : '' }}' }">
                    <label for="image" class="block text-sm font-medium text-gray-300 mb-1">
                        Main Room Image
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative space-y-4">
                        <template x-if="preview">
                            <div class="relative w-full h-40 rounded-lg overflow-hidden bg-gray-700">
                                <img :src="preview" class="w-full h-full object-cover" alt="Main room image preview">
                                <button type="button" @click="preview = ''; $refs.mainImage.value = ''"
                                    class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <input type="file" 
                            name="image" 
                            id="image" 
                            x-ref="mainImage"
                            accept="image/jpeg,image/png,image/webp"
                            @change="const file = $event.target.files[0]; if (file) preview = URL.createObjectURL(file)"
                            class="block w-full py-2 px-3 rounded-lg bg-gray-700/50 border border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200 file:mr-4 file:py-1 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-8 mt-8 border-t border-gray-700">
                <a href="{{ route('rooms.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-sm font-medium text-gray-200 hover:bg-gray-600 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <div class="flex gap-4">
                    <button type="reset"
                        class="inline-flex items-center px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-sm font-medium text-gray-200 hover:bg-gray-600 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ isset($room) ? 'Update Room' : 'Create' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
