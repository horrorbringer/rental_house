@extends('layouts.guest')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-8">
                <!-- Room Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Room {{ $room->room_number }}</h1>
                        <p class="text-xl text-gray-600 dark:text-gray-400 mt-2">{{ $room->building->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">฿{{ number_format($room->monthly_rent) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">per month</p>
                        <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            Available Now
                        </span>
                    </div>
                </div>

                <!-- Room Image -->
                <div class="mb-8 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                    @if($room->image_path)
                        <img src="{{ Storage::url($room->image_path) }}" alt="Room {{ $room->room_number }}" class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Room Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Room Details</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-600 dark:text-gray-400">Monthly Rent</dt>
                                <dd class="text-gray-900 dark:text-gray-100">฿{{ number_format($room->monthly_rent) }}</dd>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-600 dark:text-gray-400">Water Fee</dt>
                                <dd class="text-gray-900 dark:text-gray-100">฿{{ number_format($room->water_fee) }}/unit</dd>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-600 dark:text-gray-400">Electric Fee</dt>
                                <dd class="text-gray-900 dark:text-gray-100">฿{{ number_format($room->electric_fee) }}/unit</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Building Information</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-600 dark:text-gray-400">Building Name</dt>
                                <dd class="text-gray-900 dark:text-gray-100">{{ $room->building->name }}</dd>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                                <dt class="text-gray-600 dark:text-gray-400">Address</dt>
                                <dd class="text-gray-900 dark:text-gray-100">{{ $room->building->address }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Interested in this room?</h3>
                    <form action="{{ route('tenant.register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                                <input type="tel" name="phone" id="phone" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email (Optional)</label>
                                <input type="email" name="email" id="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="move_in_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Move-in Date</label>
                                <input type="date" name="move_in_date" id="move_in_date" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Card Images</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-1">
                                    <div>
                                        <label for="id_card_front" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Front Side</label>
                                        <input type="file" name="id_card_front" id="id_card_front" accept="image/*" required
                                            class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700
                                                dark:file:bg-indigo-900 dark:file:text-indigo-300
                                                hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                                    </div>
                                    <div>
                                        <label for="id_card_back" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Back Side</label>
                                        <input type="file" name="id_card_back" id="id_card_back" accept="image/*" required
                                            class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700
                                                dark:file:bg-indigo-900 dark:file:text-indigo-300
                                                hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-3 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Submit Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
