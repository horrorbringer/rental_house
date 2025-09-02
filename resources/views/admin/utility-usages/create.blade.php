@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Add New Utility Reading
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Enter meter readings and upload images for both water and electricity
                        </p>
                    </div>
                    <a href="{{ route('utility-usages.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold">Please correct the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside pl-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('utility-usages.store') }}" method="POST" enctype="multipart/form-data" 
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 relative" autocomplete="on">
                    @csrf
                    <div id="form-overlay" class="hidden absolute inset-0 bg-gray-100/70 dark:bg-gray-900/70 flex items-center justify-center z-10">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </div>
                    <div class="p-6 space-y-8">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Utility Usage Details</h3>
                        <!-- Rental and Date Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <select name="rental_id" id="rental_id" required autofocus autocomplete="off"
                                    class="peer mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                    <option value="">Select a rental</option>
                                    @foreach($rentals as $rental)
                                        <option value="{{ $rental->id }}" {{ old('rental_id') == $rental->id ? 'selected' : '' }}>
                                            {{ $rental->room->name }} - {{ $rental->tenant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="rental_id" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Rental/Tenant</label>
                            </div>
                            <div class="relative">
                                <input type="date" name="reading_date" id="reading_date" autocomplete="off"
                                    class="peer mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                    value="{{ old('reading_date', date('Y-m-d')) }}" required>
                                <label for="reading_date" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Reading Date</label>
                            </div>
                        </div>

                        <!-- Utility Rate Selection -->
                        <div class="relative">
                            <select name="utility_rate_id" id="utility_rate_id" required autocomplete="off"
                                class="peer mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                <option value="">Select utility rate</option>
                                @foreach($utilityRates as $rate)
                                    <option value="{{ $rate->id }}" {{ old('utility_rate_id') == $rate->id ? 'selected' : '' }}>
                                        Water: ₱{{ number_format($rate->water_rate, 2) }} / Electric: ₱{{ number_format($rate->electric_rate, 2) }}
                                        ({{ $rate->effective_date }})
                                    </option>
                                @endforeach
                            </select>
                            <label for="utility_rate_id" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Utility Rate</label>
                        </div>

                        <!-- Water Meter Readings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <input type="number" step="0.01" name="water_meter_start" id="water_meter_start" autocomplete="off"
                                    class="peer block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 pr-12 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                    value="{{ old('water_meter_start') }}" required>
                                <label for="water_meter_start" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Water Meter Start Reading</label>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">m³</span>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="number" step="0.01" name="water_meter_end" id="water_meter_end" autocomplete="off"
                                    class="peer block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 pr-12 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                    value="{{ old('water_meter_end') }}" required>
                                <label for="water_meter_end" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Water Meter End Reading</label>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">m³</span>
                                </div>
                            </div>
                        </div>

                        <!-- Electric Meter Readings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <input type="number" step="0.01" name="electric_meter_start" id="electric_meter_start" autocomplete="off"
                                    class="peer block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 pr-12 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                    value="{{ old('electric_meter_start') }}" required>
                                <label for="electric_meter_start" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Electric Meter Start Reading</label>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">kWh</span>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="number" step="0.01" name="electric_meter_end" id="electric_meter_end" autocomplete="off"
                                    class="peer block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 pr-12 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                    value="{{ old('electric_meter_end') }}" required>
                                <label for="electric_meter_end" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Electric Meter End Reading</label>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">kWh</span>
                                </div>
                            </div>
                        </div>

                        <!-- Meter Images -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Meter Images</h3>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400" id="upload_status">Images enabled</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="toggle_images" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                            </div>
                            
                            <div id="image_section" class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-300">
                                <!-- Water Meter Images -->
                                <div class="space-y-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">Water Meter</h4>
                                    <div class="relative">
                                        <div class="flex items-center justify-between mb-2">
                                            <label for="water_meter_image_start" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Start Reading
                                                <span class="ml-1 text-xs text-gray-400 dark:text-gray-500" title="Upload a clear photo of the water meter at the start">ⓘ</span>
                                            </label>
                                            <button type="button" onclick="clearImage('water_meter_image_start')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                Clear
                                            </button>
                                        </div>
                                        <input type="file" name="water_meter_image_start" id="water_meter_image_start" accept="image/*"
                                            class="mt-1 block w-full text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-100 dark:hover:file:bg-gray-600"
                                            onchange="previewImage(event, 'water_meter_image_start_preview')">
                                        <img id="water_meter_image_start_preview" class="mt-2 hidden w-32 h-20 object-cover rounded shadow" alt="Preview">
                                    </div>
                                    <div class="relative">
                                        <div class="flex items-center justify-between mb-2">
                                            <label for="water_meter_image_end" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                End Reading
                                                <span class="ml-1 text-xs text-gray-400 dark:text-gray-500" title="Upload a clear photo of the water meter at the end">ⓘ</span>
                                            </label>
                                            <button type="button" onclick="clearImage('water_meter_image_end')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                Clear
                                            </button>
                                        </div>
                                        <input type="file" name="water_meter_image_end" id="water_meter_image_end" accept="image/*"
                                            class="mt-1 block w-full text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-100 dark:hover:file:bg-gray-600"
                                            onchange="previewImage(event, 'water_meter_image_end_preview')">
                                        <img id="water_meter_image_end_preview" class="mt-2 hidden w-32 h-20 object-cover rounded shadow" alt="Preview">
                                    </div>
                                </div>

                                <!-- Electric Meter Images -->
                                <div class="space-y-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">Electric Meter</h4>
                                    <div class="relative">
                                        <div class="flex items-center justify-between mb-2">
                                            <label for="electric_meter_image_start" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Start Reading
                                                <span class="ml-1 text-xs text-gray-400 dark:text-gray-500" title="Upload a clear photo of the electric meter at the start">ⓘ</span>
                                            </label>
                                            <button type="button" onclick="clearImage('electric_meter_image_start')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                Clear
                                            </button>
                                        </div>
                                        <input type="file" name="electric_meter_image_start" id="electric_meter_image_start" accept="image/*"
                                            class="mt-1 block w-full text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-100 dark:hover:file:bg-gray-600"
                                            onchange="previewImage(event, 'electric_meter_image_start_preview')">
                                        <img id="electric_meter_image_start_preview" class="mt-2 hidden w-32 h-20 object-cover rounded shadow" alt="Preview">
                                    </div>
                                    <div class="relative">
                                        <div class="flex items-center justify-between mb-2">
                                            <label for="electric_meter_image_end" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                End Reading
                                                <span class="ml-1 text-xs text-gray-400 dark:text-gray-500" title="Upload a clear photo of the electric meter at the end">ⓘ</span>
                                            </label>
                                            <button type="button" onclick="clearImage('electric_meter_image_end')" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                                Clear
                                            </button>
                                        </div>
                                        <input type="file" name="electric_meter_image_end" id="electric_meter_image_end" accept="image/*"
                                            class="mt-1 block w-full text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-100 dark:hover:file:bg-gray-600"
                                            onchange="previewImage(event, 'electric_meter_image_end_preview')">
                                        <img id="electric_meter_image_end_preview" class="mt-2 hidden w-32 h-20 object-cover rounded shadow" alt="Preview">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="relative">
                            <textarea name="notes" id="notes" rows="3" autocomplete="off"
                                class="peer mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                placeholder="Any additional notes about the readings">{{ old('notes') }}</textarea>
                            <label for="notes" class="absolute left-3 top-0 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-1 transition-all peer-focus:-top-4 peer-focus:text-indigo-600 peer-focus:dark:text-indigo-400">Notes</label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                        <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                            <button type="submit" id="submitBtn"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-all disabled:opacity-50">
                                Save Utility Reading
                            </button>
                        </div>
                    </div>
                </form>
                <script>
                function previewImage(event, previewId) {
                    const input = event.target;
                    const preview = document.getElementById(previewId);
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        preview.src = '';
                        preview.classList.add('hidden');
                    }
                }

                function clearImage(inputId) {
                    const input = document.getElementById(inputId);
                    const preview = document.getElementById(inputId + '_preview');
                    input.value = '';
                    preview.src = '';
                    preview.classList.add('hidden');
                }

                function toggleImageInputs(enabled) {
                    const imageSection = document.getElementById('image_section');
                    const inputs = imageSection.querySelectorAll('input[type="file"]');
                    const clearButtons = imageSection.querySelectorAll('button');
                    const statusText = document.getElementById('upload_status');
                    
                    // Update status text
                    statusText.textContent = enabled ? 'Images enabled' : 'Images disabled';
                    statusText.classList.toggle('text-gray-600', enabled);
                    statusText.classList.toggle('text-gray-400', !enabled);
                    
                    // Handle file inputs and previews
                    inputs.forEach(input => {
                        input.disabled = !enabled;
                        if (!enabled) {
                            input.value = '';
                            const preview = document.getElementById(input.id + '_preview');
                            if (preview) {
                                preview.src = '';
                                preview.classList.add('hidden');
                            }
                        }
                    });

                    // Handle clear buttons if any
                    clearButtons.forEach(button => button.disabled = !enabled);
                    
                    // Update section visibility with smooth transition
                    imageSection.style.opacity = enabled ? '1' : '0.5';
                    imageSection.style.pointerEvents = enabled ? 'auto' : 'none';
                    imageSection.style.filter = enabled ? 'none' : 'grayscale(100%)';
                }

                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('image_section').classList.add('hidden');
                    const toggle = document.getElementById('toggle_images');
                    toggle.addEventListener('change', function() {
                        document.getElementById('image_section').classList.toggle('hidden', !this.checked);
                        toggleImageInputs(this.checked);
                        // Save preference to localStorage
                        localStorage.setItem('imageUploadEnabled', this.checked);
                    });

                    // Restore previous preference if any
                    const savedPreference = localStorage.getItem('imageUploadEnabled');
                    if (savedPreference !== null) {
                        toggle.checked = savedPreference === 'true';
                        toggleImageInputs(toggle.checked);
                    }
                });

                document.querySelector('form').addEventListener('submit', function(e) {
                    document.getElementById('form-overlay').classList.remove('hidden');
                    document.getElementById('submitBtn').disabled = true;
                });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
