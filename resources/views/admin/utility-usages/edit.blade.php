@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Edit Utility Reading
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Update utility meter readings and recalculate invoice
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
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Please correct the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside pl-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <div class="flex items-center text-sm mb-1">
                                <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="font-medium text-gray-500 dark:text-gray-400">Room</span>
                            </div>
                            <p class="text-sm text-gray-900 dark:text-gray-100 pl-7">
                                {{ $utilityUsage->rental->room->building->name }} - Room {{ $utilityUsage->rental->room->room_number }}
                            </p>
                        </div>
                        <div>
                            <div class="flex items-center text-sm mb-1">
                                <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium text-gray-500 dark:text-gray-400">Tenant</span>
                            </div>
                            <p class="text-sm text-gray-900 dark:text-gray-100 pl-7">
                                {{ $utilityUsage->rental->tenant->name }}
                            </p>
                        </div>
                        <div>
                            <div class="flex items-center text-sm mb-1">
                                <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium text-gray-500 dark:text-gray-400">Billing Month</span>
                            </div>
                            <p class="text-sm text-gray-900 dark:text-gray-100 pl-7">
                                {{ Carbon\Carbon::parse($utilityUsage->billing_month)->format('F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('utility-usages.update', $utilityUsage) }}" method="POST" enctype="multipart/form-data" 
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 relative">
                    @csrf
                    @method('PUT')
                    <div id="form-overlay" class="hidden absolute inset-0 bg-gray-100/70 dark:bg-gray-900/70 z-10">
                        <div class="h-full w-full flex items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="water_usage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Water Usage (units)
                                </label>
                                <div class="relative mt-1 rounded-lg shadow-sm">
                                    <input type="number" name="water_usage" id="water_usage" required
                                        min="0" step="0.01" value="{{ old('water_usage', $utilityUsage->water_usage) }}"
                                        class="block w-full pl-10 pr-12 border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2.5">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">units</span>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Rate: ฿{{ number_format($utilityUsage->water_price, 2) }}/unit
                                </div>
                            </div>

                            <div>
                                <label for="electric_usage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Electric Usage (units)
                                </label>
                                <div class="relative mt-1 rounded-lg shadow-sm">
                                    <input type="number" name="electric_usage" id="electric_usage" required
                                        min="0" step="0.01" value="{{ old('electric_usage', $utilityUsage->electric_usage) }}"
                                        class="block w-full pl-10 pr-12 border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg py-2.5">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">units</span>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Rate: ฿{{ number_format($utilityUsage->electric_price, 2) }}/unit
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Water Calculation</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($utilityUsage->water_usage, 2) }} units × ฿{{ number_format($utilityUsage->water_price, 2) }} = 
                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                            ฿{{ number_format($utilityUsage->water_usage * $utilityUsage->water_price, 2) }}
                                        </span>
                                    </p>
                                </div>

                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Electric Calculation</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($utilityUsage->electric_usage, 2) }} units × ฿{{ number_format($utilityUsage->electric_price, 2) }} = 
                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                            ฿{{ number_format($utilityUsage->electric_usage * $utilityUsage->electric_price, 2) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Meter Images -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Meter Images</h3>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400" id="upload_status">Images disabled</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="toggle_images" class="sr-only peer">
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
                                        @if($utilityUsage->water_meter_image_start)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($utilityUsage->water_meter_image_start) }}" class="w-32 h-20 object-cover rounded shadow" alt="Current water meter start image">
                                            </div>
                                        @endif
                                        <input type="file" name="water_meter_image_start" id="water_meter_image_start" accept="image/*"
                                            class="mt-1 block w-full text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-100 dark:hover:file:bg-gray-600"
                                            onchange="previewImage(event, 'water_meter_image_start_preview')">
                                        <img id="water_meter_image_start_preview" class="mt-2 hidden w-32 h-20 object-cover rounded shadow" alt="New preview">
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
                                        @if($utilityUsage->water_meter_image_end)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($utilityUsage->water_meter_image_end) }}" class="w-32 h-20 object-cover rounded shadow" alt="Current water meter end image">
                                            </div>
                                        @endif
                                        <input type="file" name="water_meter_image_end" id="water_meter_image_end" accept="image/*"
                                            class="mt-1 block w-full text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-100 dark:hover:file:bg-gray-600"
                                            onchange="previewImage(event, 'water_meter_image_end_preview')">
                                        <img id="water_meter_image_end_preview" class="mt-2 hidden w-32 h-20 object-cover rounded shadow" alt="New preview">
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
                                        @if($utilityUsage->electric_meter_image_start)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($utilityUsage->electric_meter_image_start) }}" class="w-32 h-20 object-cover rounded shadow" alt="Current electric meter start image">
                                            </div>
                                        @endif
                                        <input type="file" name="electric_meter_image_start" id="electric_meter_image_start" accept="image/*"
                                            class="mt-1 block w-full text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-100 dark:hover:file:bg-gray-600"
                                            onchange="previewImage(event, 'electric_meter_image_start_preview')">
                                        <img id="electric_meter_image_start_preview" class="mt-2 hidden w-32 h-20 object-cover rounded shadow" alt="New preview">
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
                                        @if($utilityUsage->electric_meter_image_end)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($utilityUsage->electric_meter_image_end) }}" class="w-32 h-20 object-cover rounded shadow" alt="Current electric meter end image">
                                            </div>
                                        @endif
                                        <input type="file" name="electric_meter_image_end" id="electric_meter_image_end" accept="image/*"
                                            class="mt-1 block w-full text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-200 hover:file:bg-gray-100 dark:hover:file:bg-gray-600"
                                            onchange="previewImage(event, 'electric_meter_image_end_preview')">
                                        <img id="electric_meter_image_end_preview" class="mt-2 hidden w-32 h-20 object-cover rounded shadow" alt="New preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                        <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                            <button type="submit" id="submitBtn"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-all disabled:opacity-50">
                                Update Reading
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
                    
                    // Handle file inputs and previews with smooth transition
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
                    const toggle = document.getElementById('toggle_images');
                    toggle.addEventListener('change', function() {
                        toggleImageInputs(this.checked);
                        // Save preference to localStorage
                        localStorage.setItem('imageUploadEnabled', this.checked);
                    });

                    // Set initial state (disabled by default)
                    toggle.checked = false;
                    toggleImageInputs(false);

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
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr('input[type="date"]', {
        dateFormat: 'Y-m-d',
        defaultDate: '{{ date("Y-m-d") }}',
        prevArrow: '<svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>',
        nextArrow: '<svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>',
        locale: {
            weekdays: {
                shorthand: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                longhand: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
            // Add animation when date changes
            instance.element.classList.add('ring-2', 'ring-indigo-500');
            setTimeout(() => instance.element.classList.remove('ring-2', 'ring-indigo-500'), 200);
        }
    });
</script>
@endpush

