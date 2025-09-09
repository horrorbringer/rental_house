<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div x-data="buildingImages()" class="space-y-6">
                        <!-- Image Upload Section -->
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6"
                             x-on:dragover.prevent="$event.target.classList.add('border-indigo-500')"
                             x-on:dragleave.prevent="$event.target.classList.remove('border-indigo-500')"
                             x-on:drop.prevent="handleDrop($event)">
                            
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4 flex text-sm text-gray-600 dark:text-gray-400">
                                    <label class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-500 hover:text-indigo-500 dark:hover:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload images</span>
                                        <input type="file" name="images[]" class="sr-only" multiple accept="image/*" @change="handleFileSelect">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, WEBP up to 5MB</p>
                            </div>

                            <!-- Upload Progress -->
                            <div x-show="uploading" class="mt-4">
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block text-indigo-600 dark:text-indigo-500">
                                                Uploading...
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold inline-block text-indigo-600 dark:text-indigo-500"
                                                  x-text="progress + '%'">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200 dark:bg-indigo-700/30">
                                        <div :style="'width: ' + progress + '%'" 
                                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Images -->
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-show="images.length > 0">
                            <template x-for="(image, index) in images" :key="image.id">
                                <div class="relative group aspect-video rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img :src="image.url" 
                                         :alt="'Building image ' + (index + 1)"
                                         class="w-full h-full object-cover">
                                    
                                    <!-- Image Actions -->
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2">
                                        <button @click="setPrimaryImage(image.id)"
                                                :class="{'bg-yellow-500': image.is_primary}"
                                                class="p-2 bg-gray-800 rounded-full hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteImage(image.id)"
                                                class="p-2 bg-red-600 rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Primary Badge -->
                                    <div x-show="image.is_primary"
                                         class="absolute top-2 left-2 px-2 py-1 bg-yellow-500 text-xs font-semibold text-white rounded-full">
                                        Primary
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function buildingImages() {
            return {
                images: @json($building->images),
                uploading: false,
                progress: 0,

                async handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    await this.uploadFiles(files);
                },

                async handleDrop(event) {
                    event.target.classList.remove('border-indigo-500');
                    const files = Array.from(event.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                    await this.uploadFiles(files);
                },

                async uploadFiles(files) {
                    if (files.length === 0) return;

                    const formData = new FormData();
                    files.forEach(file => formData.append('images[]', file));

                    this.uploading = true;
                    this.progress = 0;

                    try {
                        const response = await fetch("{{ route('buildings.images.store', $building) }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!response.ok) throw new Error('Upload failed');

                        // Refresh the images list
                        location.reload();
                    } catch (error) {
                        alert('Failed to upload images');
                    } finally {
                        this.uploading = false;
                    }
                },

                async deleteImage(imageId) {
                    if (!confirm('Are you sure you want to delete this image?')) return;

                    try {
                        const response = await fetch(`/buildings/${buildingId}/images/${imageId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) throw new Error('Delete failed');

                        this.images = this.images.filter(img => img.id !== imageId);
                    } catch (error) {
                        alert('Failed to delete image');
                    }
                },

                async setPrimaryImage(imageId) {
                    try {
                        const response = await fetch(`/buildings/${buildingId}/images/${imageId}/primary`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) throw new Error('Update failed');

                        // Update local state
                        this.images = this.images.map(img => ({
                            ...img,
                            is_primary: img.id === imageId
                        }));
                    } catch (error) {
                        alert('Failed to set primary image');
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
