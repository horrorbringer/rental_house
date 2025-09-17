@extends('layouts.dashboard')
@section('content')

<div class="{{$notInitializeUtility ? '' : 'max-w-7xl mx-auto sm:px-6 lg:px-8 py-8'}} ">

    <!-- Breadcrumb -->
    <nav class="mb-3 flex" aria-label="Breadcrumb">
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
                    <span class="text-gray-700 dark:text-gray-300 ml-1 md:ml-2">Add rental</span>
                </div>
            </li>
        </ol>
    </nav>

    <form action="{{ route('rooms.store-quick-add-rental', $room) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="{{$notInitializeUtility ? 'grid grid-cols-1 md:grid-cols-2 gap-6' : ''}} bg-white-800 dark:bg-gray-800 px-4 py-5 shadow sm:rounded-lg sm:p-6 ">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-600 dark:text-white">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- deposit --}}
                    <div>
                        <label for="deposit" class="block text-sm font-medium text-gray-600 dark:text-white">Deposit Amount <span class="text-red-500">*</span></label>
                        <input type="number" name="deposit" id="deposit" value="{{ old('deposit') }}" required
                            class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('deposit')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                    <label for="phone" class="block text-sm font-medium text-gray-600 dark:text-white">Phone Number <span class="text-red-500">*</span></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">+855 </span>
                        </div>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            pattern="[0-9-]{10,12}"
                            placeholder="12-345-6789"
                            title="Please enter a valid Cambodian phone number"
                            maxlength="12"
                            oninput="formatPhoneNumber(this)">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Format: 12-345-6789 (no leading zero)</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    </div>

                    {{-- start date --}}
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-600 dark:text-white">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                            class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600 dark:text-white">Email (Optional)</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="id_card_number" class="block text-sm font-medium text-gray-600 dark:text-white">ID Card Number <span class="text-red-500">*</span></label>
                    <input type="text" name="id_card_number" id="id_card_number" value="{{ old('id_card_number') }}" required
                        class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('id_card_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="id_card_front" class="block text-sm font-medium text-gray-600 dark:text-white">ID Card (Front) <span class="text-red-500">*</span></label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                            <div class="space-y-2 text-center">
                                <div id="preview_front" class="hidden mb-4">
                                    <img src="" alt="ID Card Front Preview" class="mx-auto h-32 w-auto">
                                </div>
                                <div class="text-gray-400" id="icon_front">
                                    <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex text-sm text-gray-400">
                                    <label for="id_card_front" class="relative cursor-pointer rounded-md font-medium text-indigo-500 hover:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="id_card_front" name="id_card_front" type="file" accept="image/*" class="sr-only" onchange="previewImage(this, 'preview_front', 'icon_front')">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('id_card_front')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_card_back" class="block text-sm font-medium text-gray-300">ID Card (Back) <span class="text-red-500">*</span></label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                            <div class="space-y-2 text-center">
                                <div id="preview_back" class="hidden mb-4">
                                    <img src="" alt="ID Card Back Preview" class="mx-auto h-32 w-auto">
                                </div>
                                <div class="text-gray-400" id="icon_back">
                                    <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex text-sm text-gray-400">
                                    <label for="id_card_back" class="relative cursor-pointer rounded-md font-medium text-indigo-500 hover:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="id_card_back" name="id_card_back" type="file" accept="image/*" class="sr-only" onchange="previewImage(this, 'preview_back', 'icon_back')">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('id_card_back')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            @if($notInitializeUtility)
                <div class="bg-white-800 dark:bg-gray-800 px-4 shadow sm:rounded-lg">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="electric_usage" class="block text-sm font-medium text-gray-600 dark:text-white">Initial Electricity Reading (kWh) <span class="text-red-500">*</span></label>
                                <input type="number" name="electric_usage" id="electric_usage" value="{{ old('electric_usage') }}" required
                                    class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('electric_usage')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="water_usage" class="block text-sm font-medium text-gray-600 dark:text-white">Initial Water Reading (m³) <span class="text-red-500">*</span></label>
                                <input type="number" name="water_usage" id="water_usage" value="{{ old('water_usage') }}" required
                                    class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('water_usage')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    <div>
                        <label for="reading_date" class="block text-sm font-medium text-gray-600 dark:text-white">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="reading_date" id="reading_date" value="{{ old('reading_date') }}" required
                            class="mt-1 block w-full bg-gray-50 rounded-md dark:border-gray-700 dark:bg-gray-900 border-gray-300 dark:text-gray-300 text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('reading_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('rooms.index') }}"
                class="inline-flex justify-center rounded-md border border-gray-700 bg-gray-800 py-2 px-4 text-sm font-medium text-gray-300 shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Create Tenant
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input, previewId, iconId) {
        const preview = document.getElementById(previewId);
        const icon = document.getElementById(iconId);
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Phone number formatting
    function formatPhoneNumber(input) {
        // Remove all non-numeric characters
        let value = input.value.replace(/\D/g, '');

        // Trim leading zeros
        value = value.replace(/^0+/, '');

        // Limit to 10 digits (not counting dashes)
        value = value.substring(0, 10);

        // Add dashes
        if (value.length > 5) {
            value = value.substring(0, 2) + '-' + value.substring(2, 5) + '-' + value.substring(5);
        } else if (value.length > 2) {
            value = value.substring(0, 2) + '-' + value.substring(2);
        }

        // Validate and update input
        if (value.length >= 2) {
            const firstTwo = value.substring(0, 2);
            // Common Cambodian mobile prefixes
            if (['10', '11', '12', '14', '15', '16', '17', '18', '19', '61', '66', '67', '68', '69', '70', '77', '78', '79', '80', '81', '86', '87', '89', '90', '91', '92', '93', '95', '96', '97', '98', '99'].includes(firstTwo)) {
                input.setCustomValidity('');
            } else {
                input.setCustomValidity('Please enter a valid Cambodian mobile number');
            }
        }

        input.value = value;
    }

    // Add drag and drop functionality
    document.querySelectorAll('.border-dashed').forEach(dropZone => {
        const input = dropZone.querySelector('input[type="file"]');
        const previewId = input.getAttribute('onchange').match(/'([^']+)'/)[1];
        const iconId = input.getAttribute('onchange').match(/, '([^']+)'/)[1];

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
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
            dropZone.classList.add('border-indigo-500');
            dropZone.classList.add('bg-gray-800');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-indigo-500');
            dropZone.classList.remove('bg-gray-800');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const file = dt.files[0];

            input.files = dt.files;
            previewImage(input, previewId, iconId);
        }
    });
</script>
@endpush
