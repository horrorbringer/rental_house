@extends('layouts.guest')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Hanuman:wght@100..900&display=swap');
    .font-hanuman {
        font-family: 'Hanuman', serif;
    }
    .gallery-thumb {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .gallery-thumb:hover {
        opacity: 0.8;
    }
    .gallery-thumb.active {
        border: 2px solid #4f46e5;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8">
        <!-- Image Gallery -->
        <div class="aspect-w-16 aspect-h-9 mb-8 lg:mb-0">
            <div class="relative">
                <!-- Main Image -->
                <div class="overflow-hidden rounded-lg">
                    <img id="mainImage" src="{{ $room->image ? Storage::url($room->image) : asset('images/room-placeholder.jpg') }}" 
                         alt="Room {{ $room->room_number }}" 
                         class="w-full h-96 object-cover">
                </div>
                
                <!-- Thumbnails -->
                <div class="mt-4 grid grid-cols-4 gap-4">
                    @foreach($room->images as $image)
                    <div class="gallery-thumb rounded-lg overflow-hidden">
                        <img src="{{ Storage::url($image->path) }}" 
                             alt="Room view" 
                             class="w-full h-20 object-cover cursor-pointer"
                             onclick="updateMainImage('{{ Storage::url($image->path) }}')">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Room Details -->
        <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white font-hanuman">
                    បន្ទប់លេខ {{ $room->room_number }}
                </h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $room->status === 'vacant' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-hanuman">
                    {{ $room->status === 'vacant' ? 'ទំនេរ' : 'ជួលរួច' }}
                </span>
            </div>

            <div class="mt-4">
                <h2 class="sr-only">Room information</h2>
                <p class="text-3xl tracking-tight text-gray-900 dark:text-white font-hanuman">
                    ${{ number_format($room->monthly_rent, 2) }}/ខែ
                </p>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white font-hanuman">អគារ</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-300 font-hanuman">
                    {{ $room->building->name }}
                </p>
            </div>

            <!-- Room Specifications -->
            <div class="mt-8">
                <div class="grid grid-cols-2 gap-4">
                    <div class="border rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white font-hanuman">ទំហំបន្ទប់</h4>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $room->width }}m × {{ $room->length }}m</p>
                    </div>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white font-hanuman">ចំនួនមនុស្សអតិបរមា</h4>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $room->capacity }} នាក់</p>
                    </div>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white font-hanuman">ថ្លៃទឹក</h4>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">${{ number_format($room->water_fee, 2) }}/m³</p>
                    </div>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white font-hanuman">ថ្លៃភ្លើង</h4>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">${{ number_format($room->electric_fee, 2) }}/kWh</p>
                    </div>
                </div>
            </div>

            <!-- Contact/Book Button -->
            <div class="mt-10 space-y-4">
                @if($room->status === 'vacant')
                    <button type="button" 
                            onclick="showBookingForm()"
                            class="w-full bg-indigo-600 text-white px-8 py-4 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] font-hanuman text-lg shadow-lg hover:shadow-indigo-500/30">
                        ជួល​បន្ទប់
                    </button>
                    <!-- Contact Options -->
                    <div class="grid grid-cols-2 gap-4">
                        <a href="tel:{{ config('app.contact_phone', '+855 12 345 678') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors duration-300 font-hanuman">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            ទូរស័ព្ទ
                        </a>
                        <a href="https://t.me/{{ config('app.telegram_username', 'your_telegram') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors duration-300 font-hanuman">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                            </svg>
                            Telegram
                        </a>
                    </div>
                @else
                    <div class="w-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 px-8 py-4 rounded-lg font-medium text-center font-hanuman">
                        បន្ទប់នេះត្រូវបានជួលរួចហើយ
                    </div>
                    <!-- Contact for Waitlist -->
                    <div class="grid grid-cols-2 gap-4">
                        <a href="tel:{{ config('app.contact_phone', '+855 12 345 678') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors duration-300 font-hanuman">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            ទូរស័ព្ទសាកសួរ
                        </a>
                        <a href="https://t.me/{{ config('app.telegram_username', 'your_telegram') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors duration-300 font-hanuman">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                            </svg>
                            សារ Telegram
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Rooms -->
    @if($similarRooms->count() > 0)
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 font-hanuman">បន្ទប់ផ្សេងទៀតនៅក្នុងអគារនេះ</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($similarRooms as $relatedRoom)
            <div class="group bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                <div class="aspect-w-16 aspect-h-9">
                    <img src="{{ $relatedRoom->image ? Storage::url($relatedRoom->image) : asset('images/room-placeholder.jpg') }}" 
                         alt="Room {{ $relatedRoom->room_number }}" 
                         class="object-cover w-full h-full">
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white font-hanuman">
                            បន្ទប់លេខ {{ $relatedRoom->room_number }}
                        </h3>
                        <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full font-hanuman">
                            ទំនេរ
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 font-hanuman">
                            ${{ number_format($relatedRoom->monthly_rent, 2) }}/ខែ
                        </span>
                        <a href="{{ route('tenant.rooms.show', $relatedRoom) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 font-hanuman">
                            មើលបន្ថែម
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="hideBookingForm()" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white font-hanuman">
                        ចុះឈ្មោះជួលបន្ទប់
                    </h3>
                    <form action="{{ route('tenants.register') }}" method="POST" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">ឈ្មោះ</label>
                            <input type="text" name="name" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">លេខទូរស័ព្ទ</label>
                            <input type="tel" name="phone" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">អ៊ីមែល</label>
                            <input type="email" name="email" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">រូបថតអត្តសញ្ញាណប័ណ្ណ (ខាងមុខ)</label>
                                <input type="file" name="id_card_front" required accept="image/*"
                                       class="mt-1 block w-full">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">រូបថតអត្តសញ្ញាណប័ណ្ណ (ខាងក្រោយ)</label>
                                <input type="file" name="id_card_back" required accept="image/*"
                                       class="mt-1 block w-full">
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-6">
                            <button type="submit" 
                                    class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-hanuman">
                                ដាក់ពាក្យស្នើសុំ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize image gallery functionality
    function updateMainImage(src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(thumb => {
            if (thumb.querySelector('img').src === src) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    }
    
    // Make updateMainImage available globally
    window.updateMainImage = updateMainImage;

    // Booking form functions
    window.showBookingForm = function() {
        const modal = document.getElementById('bookingModal');
        if (modal) {
            modal.classList.remove('hidden');
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }
    };

    window.hideBookingForm = function() {
        const modal = document.getElementById('bookingModal');
        if (modal) {
            modal.classList.add('hidden');
            // Restore body scrolling
            document.body.style.overflow = '';
        }
    };

    // Close modal when clicking outside
    const modal = document.getElementById('bookingModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideBookingForm();
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideBookingForm();
            }
        });
    }
});
</script>
@endpush
