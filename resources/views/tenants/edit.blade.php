@extends('layouts.dashboard')

@section('title', 'Edit Tenant')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('tenants.update', $tenant) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-gray-800 px-4 py-5 shadow sm:rounded-lg sm:p-6">
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-300">Phone Number</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">+855</span>
                        </div>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="pl-12 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Email (Optional)</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $tenant->email) }}"
                        class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="id_card_front" class="block text-sm font-medium text-gray-300">ID Card (Front)</label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                            <div class="space-y-2 text-center">
                                <div id="preview_front" class="{{ $tenant->id_card_front_path ? '' : 'hidden' }} mb-4">
                                    <img src="{{ $tenant->id_card_front_path ? Storage::url($tenant->id_card_front_path) : '' }}"
                                         alt="ID Card Front Preview"
                                         class="mx-auto h-32 w-auto">
                                </div>
                                <div class="text-gray-400 {{ $tenant->id_card_front_path ? 'hidden' : '' }}" id="icon_front">
                                    <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex text-sm text-gray-400">
                                    <label for="id_card_front" class="relative cursor-pointer rounded-md font-medium text-indigo-500 hover:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload new file</span>
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
                        <label for="id_card_back" class="block text-sm font-medium text-gray-300">ID Card (Back)</label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                            <div class="space-y-2 text-center">
                                <div id="preview_back" class="{{ $tenant->id_card_back_path ? '' : 'hidden' }} mb-4">
                                    <img src="{{ $tenant->id_card_back_path ? Storage::url($tenant->id_card_back_path) : '' }}"
                                         alt="ID Card Back Preview"
                                         class="mx-auto h-32 w-auto">
                                </div>
                                <div class="text-gray-400 {{ $tenant->id_card_back_path ? 'hidden' : '' }}" id="icon_back">
                                    <svg class="mx-auto h-12 w-12" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex text-sm text-gray-400">
                                    <label for="id_card_back" class="relative cursor-pointer rounded-md font-medium text-indigo-500 hover:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload new file</span>
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
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tenants.index') }}"
                class="inline-flex justify-center rounded-md border border-gray-700 bg-gray-800 py-2 px-4 text-sm font-medium text-gray-300 shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Update Tenant
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

        // Add dashes
        if (value.length > 7) {
            value = value.substring(0, 3) + '-' + value.substring(3, 7) + '-' + value.substring(7);
        } else if (value.length > 3) {
            value = value.substring(0, 3) + '-' + value.substring(3);
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

    // Format initial phone number
    window.addEventListener('load', function() {
        const phoneInput = document.getElementById('phone');
        formatPhoneNumber(phoneInput);
    });
</script>
@endpush
