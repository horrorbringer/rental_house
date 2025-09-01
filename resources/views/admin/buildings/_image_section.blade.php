                <!-- Building Images Section -->
                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="space-y-2">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Building Images
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Upload and manage images of the building.
                        </p>
                    </div>

                    <div x-data="buildingImages" class="mt-6 space-y-6">
                        <!-- Drag and Drop Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6"
                             x-on:dragover.prevent="$event.target.classList.add('border-indigo-500')"
                             x-on:dragleave.prevent="$event.target.classList.remove('border-indigo-500')"
                             x-on:drop.prevent="handleDrop($event)">
                            
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4 flex text-sm text-gray-600 dark:text-gray-400 justify-center">
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

                        <!-- Current Images -->
                        @if($building->images->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($building->images as $image)
                                    <div class="relative group aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                             alt="Building image"
                                             class="w-full h-full object-cover">
                                        
                                        <!-- Image Actions -->
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-2">
                                            <button type="button"
                                                    @click="setPrimaryImage({{ $image->id }})"
                                                    class="p-2 rounded-full {{ $image->is_primary ? 'bg-yellow-500' : 'bg-gray-800' }} hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                    @click="deleteImage({{ $image->id }})"
                                                    class="p-2 bg-red-600 rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        @if($image->is_primary)
                                            <div class="absolute top-2 left-2 px-2 py-1 bg-yellow-500 text-xs font-semibold text-white rounded-full">
                                                Primary
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
