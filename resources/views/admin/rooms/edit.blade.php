@extends('layouts.dashboard')

@section('title', 'Edit Room')

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('roomForm', () => ({
            isUploading: false,
            formData: {
                room_number: '{{ old('room_number', $room->room_number) }}',
                monthly_rent: '{{ old('monthly_rent', $room->monthly_rent) }}',
                water_fee: '{{ old('water_fee', $room->water_fee) }}',
                electric_fee: '{{ old('electric_fee', $room->electric_fee) }}',
                capacity: '{{ old('capacity', $room->capacity) }}'
            },
            errors: {},
            debounceTimer: null,

            init() {
                this.setupValidation();
            },

            setupValidation() {
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
                switch(field) {
                    case 'room_number':
                        this.errors[field] = !value ? 'Room number is required' : '';
                        break;
                    case 'monthly_rent':
                    case 'water_fee':
                    case 'electric_fee':
                        this.errors[field] = value < 0 ? 'Amount cannot be negative' : '';
                        break;
                    case 'capacity':
                        this.errors[field] = value < 1 ? 'Capacity must be at least 1' : '';
                        break;
                }
            },

            validateForm() {
                const fileInput = this.$refs.mainImage;
                const additionalImages = this.$refs.additionalImages;
                
                if (fileInput && fileInput.files[0] && fileInput.files[0].size > 5 * 1024 * 1024) {
                    alert('Main image must be less than 5MB');
                    return false;
                }
                
                if (additionalImages && additionalImages.files.length > 0) {
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
                <a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white inline-flex items-center transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('rooms.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white ml-1 md:ml-2 transition-colors duration-200">Rooms</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="text-gray-700 dark:text-gray-300 ml-1 md:ml-2">Edit Room {{ $room->room_number }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 flex items-center justify-center transform transition-transform duration-200 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Room {{ $room->room_number }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Update the room's information and settings</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-8">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500 text-red-600 dark:text-red-500 px-4 py-3 rounded-lg">
                <div class="font-medium">Please fix the following errors:</div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" 
              action="{{ route('rooms.update', $room) }}" 
              enctype="multipart/form-data" 
              class="space-y-6"
              x-data="roomForm"
              x-on:submit.prevent="if (validateForm()) $el.submit()"
              :class="{ 'opacity-75 pointer-events-none': isUploading }">
            @csrf
            @method('PUT')

            <!-- Current Room Images -->
            @if($room->image || $room->images->count() > 0)
                <div class="bg-gray-50 dark:bg-gray-750 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Current Images</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @if($room->image)
                            <div class="relative aspect-video rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                <img src="{{ asset('storage/' . $room->image) }}" 
                                     alt="Main room image" 
                                     class="w-full h-full object-cover"
                                     loading="eager"
                                     decoding="async">
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 to-transparent"></div>
                                <div class="absolute bottom-2 left-2">
                                    <span class="text-white text-xs font-medium bg-gray-900/70 dark:bg-gray-900/60 px-2 py-1 rounded-full">Main Image</span>
                                </div>
                            </div>
                        @endif
                        @foreach($room->images as $index => $image)
                            <div class="relative aspect-video rounded-lg overflow-hidden bg-gray-700">
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                     alt="Additional room image {{ $index + 1 }}" 
                                     class="w-full h-full object-cover"
                                     loading="lazy"
                                     decoding="async">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6">
                <!-- Building Selection -->
                <div>
                    <label for="building_id" class="block text-sm font-medium text-gray-300 mb-1">Building</label>
                    <div class="relative">
                        <select name="building_id" id="building_id" required
                            class="block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                            <option value="">Select a building</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}" {{ (old('building_id', $room->building_id) == $building->id) ? 'selected' : '' }}>
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('building_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room Number -->
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Room Number</label>
                    <div class="relative">
                        <input type="text" name="room_number" id="room_number" required
                            value="{{ old('room_number', $room->room_number) }}"
                            class="block w-full rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-200"
                            placeholder="e.g., 101">
                    </div>
                    @error('room_number')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monthly Rent -->
                <div>
                    <label for="monthly_rent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monthly Rent</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400">៛</span>
                        </div>
                        <input type="number" name="monthly_rent" id="monthly_rent" required step="0.01"
                            value="{{ old('monthly_rent', $room->monthly_rent) }}"
                            class="pl-8 block w-full rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-200"
                            placeholder="0.00">
                    </div>
                    @error('monthly_rent')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Water Fee -->
                <div>
                    <label for="water_fee" class="block text-sm font-medium text-gray-300 mb-1">Water Fee</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">៛</span>
                        </div>
                        <input type="number" name="water_fee" id="water_fee" required step="0.01"
                            value="{{ old('water_fee', $room->water_fee) }}"
                            class="pl-8 block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                            placeholder="0.00">
                    </div>
                    @error('water_fee')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Electric Fee -->
                <div>
                    <label for="electric_fee" class="block text-sm font-medium text-gray-300 mb-1">Electric Fee</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">៛</span>
                        </div>
                        <input type="number" name="electric_fee" id="electric_fee" required step="0.01"
                            value="{{ old('electric_fee', $room->electric_fee) }}"
                            class="pl-8 block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200"
                            placeholder="0.00">
                    </div>
                    @error('electric_fee')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-1">Status</label>
                    <div class="relative">
                        <select name="status" id="status" required
                            class="block w-full rounded-lg bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-white transition-all duration-200">
                            <option value="{{ App\Models\Room::STATUS_VACANT }}"
                                {{ (old('status', $room->status) == App\Models\Room::STATUS_VACANT) ? 'selected' : '' }}>
                                Vacant
                            </option>
                            <option value="{{ App\Models\Room::STATUS_OCCUPIED }}"
                                {{ (old('status', $room->status) == App\Models\Room::STATUS_OCCUPIED) ? 'selected' : '' }}>
                                Occupied
                            </option>
                        </select>
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Main Image -->
                <div x-data="{ 
                    preview: null,
                    showPreview(event) {
                        const file = event.target.files[0];
                        if (file) {
                            if (file.size > 5 * 1024 * 1024) {
                                alert('Image must be less than 5MB');
                                event.target.value = '';
                                return;
                            }
                            this.preview = URL.createObjectURL(file);
                        }
                    }
                }">
                    <label for="image" class="block text-sm font-medium text-gray-300 mb-1">
                        Main Room Image
                        <span class="text-gray-400 text-xs ml-1">(Max 5MB)</span>
                    </label>
                    
                    <template x-if="preview">
                        <div class="relative w-full h-40 mb-4 rounded-lg overflow-hidden bg-gray-700">
                            <img :src="preview" class="w-full h-full object-cover" alt="New main image preview">
                            <button type="button" @click="preview = null; $refs.mainImage.value = ''"
                                class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>

                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-lg hover:border-indigo-500 transition-colors duration-200"
                         @dragover.prevent="$el.classList.add('border-indigo-500')"
                         @dragleave.prevent="$el.classList.remove('border-indigo-500')"
                         @drop.prevent="$el.classList.remove('border-indigo-500')">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-400">
                                <label for="image" class="relative cursor-pointer rounded-md font-medium text-indigo-500 hover:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="image" 
                                           name="image" 
                                           type="file" 
                                           class="sr-only" 
                                           x-ref="mainImage"
                                           accept="image/jpeg,image/png,image/webp"
                                           @change="showPreview($event)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">JPG, PNG, or WEBP up to 5MB</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Images -->
                <div x-data="{ 
                    previews: [],
                    maxFiles: 5,
                    showPreviews(event) {
                        const files = Array.from(event.target.files).slice(0, this.maxFiles);
                        if (files.some(file => file.size > 5 * 1024 * 1024)) {
                            alert('Each image must be less than 5MB');
                            event.target.value = '';
                            return;
                        }
                        this.previews = files.map(file => URL.createObjectURL(file));
                    },
                    removePreview(index) {
                        this.previews.splice(index, 1);
                        const dt = new DataTransfer();
                        const input = this.$refs.additionalImages;
                        const { files } = input;
                        
                        for(let i = 0; i < files.length; i++) {
                            if(i !== index) dt.items.add(files[i]);
                        }
                        
                        input.files = dt.files;
                    }
                }">
                    <label for="additional_images" class="block text-sm font-medium text-gray-300 mb-1">
                        Additional Images
                        <span class="text-gray-400 text-xs ml-1">(Max 5 images, 5MB each)</span>
                    </label>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-4" x-show="previews.length">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative aspect-video rounded-lg overflow-hidden bg-gray-700">
                                <img :src="preview" class="w-full h-full object-cover" :alt="'Additional image preview ' + (index + 1)">
                                <button type="button" @click="removePreview(index)"
                                    class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-lg hover:border-indigo-500 transition-colors duration-200"
                         @dragover.prevent="$el.classList.add('border-indigo-500')"
                         @dragleave.prevent="$el.classList.remove('border-indigo-500')"
                         @drop.prevent="$el.classList.remove('border-indigo-500')"
                         :class="{ 'opacity-50 pointer-events-none': previews.length >= maxFiles }">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-400">
                                <label for="additional_images" class="relative cursor-pointer rounded-md font-medium text-indigo-500 hover:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload files</span>
                                    <input id="additional_images" 
                                           name="additional_images[]" 
                                           type="file" 
                                           class="sr-only" 
                                           x-ref="additionalImages"
                                           accept="image/jpeg,image/png,image/webp" 
                                           multiple
                                           @change="showPreviews($event)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" x-text="previews.length >= maxFiles ? 'Maximum images reached' : 'JPG, PNG, or WEBP up to 5MB each'"></p>
                        </div>
                    </div>
                    @error('additional_images')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @error('additional_images.*')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700 mt-8">
                <a href="{{ route('rooms.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-transparent hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 dark:focus:ring-offset-gray-800 focus:ring-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
