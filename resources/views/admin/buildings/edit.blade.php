@extends('layouts.dashboard')

@section('title', 'Edit Building')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-100">Edit Building</h1>
            <a href="{{ route('buildings.index') }}" class="flex items-center text-sm text-gray-400 hover:text-white transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Buildings
            </a>
        </div>
    </div>

    <div class="bg-gray-800 shadow-xl rounded-xl p-6">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-100">Edit Building</h1>
            <a href="{{ route('buildings.index') }}" class="flex items-center text-sm text-gray-400 hover:text-white transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Buildings
            </a>
        </div>
    </div>

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

    <!-- Main Form -->
    <form id="buildingForm" 
          method="POST" 
          action="{{ route('buildings.update', $building) }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')
        <div id="formMessages"></div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300">Building Name</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', $building->name) }}"
                   class="mt-1 block w-full rounded-lg bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring-indigo-500">
            @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Address -->
        <div>
            <label for="address" class="block text-sm font-medium text-gray-300">Address</label>
            <textarea name="address" 
                      id="address" 
                      rows="2"
                      class="mt-1 block w-full rounded-lg bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $building->address) }}</textarea>
            @error('address') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
            <textarea name="description" 
                      id="description" 
                      rows="3"
                    class="mt-1 block w-full rounded-lg bg-gray-700 text-white">{{ old('description', $building->description) }}</textarea>
        </div>

        <!-- Contact Info -->
        <div>
            <label for="contact_info" class="block text-sm font-medium text-gray-300">Contact Info</label>
            <input type="text" name="contact_info" id="contact_info" 
                value="{{ old('contact_info', $building->contact_info) }}"
                class="mt-1 block w-full rounded-lg bg-gray-700 text-white">
        </div>

        <!-- Existing Images -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-300 mb-2">Current Images</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="currentImages">
                @foreach($building->images as $image)
                    <div class="relative aspect-video bg-gray-700 rounded-lg overflow-hidden group" data-image-id="{{ $image->id }}">
                        <img src="{{ Storage::url($image->path) }}" alt="Building image" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button type="button" onclick="setPrimaryImage({{ $image->id }})" data-primary-button="{{ $image->id }}" class="p-2 text-white {{ $image->is_primary ? 'bg-green-500' : 'bg-indigo-500' }} rounded-full hover:bg-opacity-80 transition-colors" title="{{ $image->is_primary ? 'Primary Image' : 'Set as Primary' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            <button type="button" onclick="deleteImage({{ $image->id }})" class="p-2 text-white bg-red-500 rounded-full hover:bg-red-600 transition-colors" title="Delete Image">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        @if($image->is_primary)
                            <span class="absolute top-2 left-2 px-2 py-1 bg-green-500 text-white text-xs rounded-full">Primary</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <input type="hidden" name="deleted_image_ids" id="deleted_image_ids" value="">
        <input type="hidden" name="primary_image_id" id="primary_image_id" value="{{ $building->images->where('is_primary', true)->first()?->id }}">

        <!-- Upload New Images -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-300 mb-2">
                Add New Images <span class="text-xs text-gray-400">(Max 5 images, 5MB each)</span>
            </label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-lg 
                        hover:border-indigo-500 transition-colors duration-200">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="flex text-sm text-gray-400">
                        <label class="relative cursor-pointer bg-gray-800 rounded-md font-medium text-indigo-500 
                                    hover:text-indigo-400 focus-within:outline-none">
                            <span>Upload new images</span>
                            <input type="file" 
                                   name="new_images[]" 
                                   accept="image/jpeg,image/png,image/webp" 
                                   multiple
                                   @change="handleNewImages($event)"
                                   class="sr-only">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-400">PNG, JPG, or WEBP up to 5MB each</p>
                </div>
            </div>

            <!-- New Images Preview -->
            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4" x-show="newImagePreviews.length">
                <template x-for="(preview, index) in newImagePreviews" :key="index">
                    <div class="relative aspect-video">
                        <img :src="preview.url" 
                             class="w-full h-full object-cover rounded-lg"
                             :alt="preview.name">
                        
                        <!-- Remove Preview Button -->
                        <button type="button"
                                @click="removeNewImage(index)"
                                class="absolute top-2 right-2 p-1 rounded-full bg-red-500/80 hover:bg-red-500 transition-colors">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <div class="absolute bottom-2 left-2 text-xs text-white bg-black/50 px-2 py-1 rounded">
                            <span x-text="preview.name"></span>
                        </div>
                    </div>
                </template>
            </div>

            @error('new_images.*') 
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Actions -->
                <!-- Actions -->
        <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t border-gray-700">
            <a href="{{ route('buildings.index') }}" 
               class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Building
            </button>
        </div>
    </form>


</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('buildingForm');
    const formMessages = document.getElementById('formMessages');
    const imageInput = document.querySelector('input[type="file"]');
    const previewContainer = document.getElementById('newImagePreview');
    
    // Initialize or reset hidden inputs
    const deletedInput = document.getElementById('deleted_image_ids') || document.createElement('input');
    deletedInput.type = 'hidden';
    deletedInput.name = 'deleted_image_ids';
    deletedInput.id = 'deleted_image_ids';
    deletedInput.value = '[]';
    
    // Add to form if it doesn't exist
    if (!document.getElementById('deleted_image_ids')) {
        form.appendChild(deletedInput);
    }
    
    if (!document.getElementById('primary_image_id')) {
        const primaryInput = document.createElement('input');
        primaryInput.type = 'hidden';
        primaryInput.name = 'primary_image_id';
        primaryInput.id = 'primary_image_id';
        form.appendChild(primaryInput);
    }

    // Handle form submission
    form.addEventListener('submit', function(e) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Updating...';
        }
    });

    // Handle new image uploads
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (!files.length) return;

            // Clear previous previews
            while (previewContainer.firstChild) {
                previewContainer.removeChild(previewContainer.firstChild);
            }

            Array.from(files).forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`${file.name} is too large. Maximum size is 5MB`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
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
        });
    }

    // Handle image deletion
    window.deleteImage = function(imageId) {
        if (!confirm('Are you sure you want to delete this image?')) return;
        
        let input = document.getElementById('deleted_image_ids');
        let currentDeleted = [];
        
        try {
            currentDeleted = input.value ? JSON.parse(input.value) : [];
        } catch (e) {
            console.error('Error parsing deleted_image_ids:', e);
            currentDeleted = [];
        }
        
        // Make sure currentDeleted is an array
        if (!Array.isArray(currentDeleted)) {
            currentDeleted = [];
        }
        
        // Add the new ID to the array if it's not already there
        if (!currentDeleted.includes(imageId)) {
            currentDeleted.push(imageId);
        }
        
        // Update the hidden input
        input.value = JSON.stringify(currentDeleted);
        
        // Remove the image element from display
        const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
        if (imageElement) {
            imageElement.remove();
        }
        
        // If this was the primary image, remove the primary_image_id
        const primaryInput = document.getElementById('primary_image_id');
        if (primaryInput && primaryInput.value == imageId) {
            primaryInput.value = '';
            // Find first remaining image and make it primary
            const firstRemainingImage = document.querySelector('[data-image-id]');
            if (firstRemainingImage) {
                const firstImageId = firstRemainingImage.dataset.imageId;
                setPrimaryImage(firstImageId);
            }
        }
    };

    // Handle setting primary image
    window.setPrimaryImage = function(imageId) {
        // Update the hidden input for primary image
        let input = document.getElementById('primary_image_id');
        input.value = imageId;
        
        // Update UI to show which image is primary
        // First, remove all primary badges
        document.querySelectorAll('.primary-badge').forEach(badge => {
            badge.remove();
        });
        
        // Remove green background from all primary buttons
        document.querySelectorAll('[data-primary-button]').forEach(button => {
            button.classList.remove('bg-green-500');
            button.classList.add('bg-indigo-500');
        });
        
        // Update the selected image
        const selectedButton = document.querySelector(`[data-primary-button="${imageId}"]`);
        if (selectedButton) {
            selectedButton.classList.remove('bg-indigo-500');
            selectedButton.classList.add('bg-green-500');
            
            // Add primary badge to the selected image
            const imageContainer = document.querySelector(`[data-image-id="${imageId}"]`);
            if (imageContainer) {
                const badge = document.createElement('span');
                badge.className = 'primary-badge absolute top-2 left-2 px-2 py-1 bg-green-500 text-white text-xs rounded-full';
                badge.textContent = 'Primary';
                imageContainer.appendChild(badge);
            }
        }
    };
});
</script>
@endpush

@endsection
