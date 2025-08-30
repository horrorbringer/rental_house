@extends('layouts.guest')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-8">
                <!-- Room Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-100">{{ $room->name }}</h1>
                        <p class="text-xl text-gray-400 mt-2">{{ $room->building->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-indigo-400">${{ number_format($room->price, 2) }}/month</p>
                        <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            Available Now
                        </span>
                    </div>
                </div>

                <!-- Room Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-200 mb-4">Room Details</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <dt class="text-gray-400">Floor Number</dt>
                                <dd class="text-gray-200">{{ $room->floor }}</dd>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <dt class="text-gray-400">Room Type</dt>
                                <dd class="text-gray-200">{{ $room->type }}</dd>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <dt class="text-gray-400">Size</dt>
                                <dd class="text-gray-200">{{ $room->size }} sqm</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-gray-200 mb-4">Building Amenities</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <dt class="text-gray-400">Address</dt>
                                <dd class="text-gray-200">{{ $room->building->address }}</dd>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-700">
                                <dt class="text-gray-400">Contact</dt>
                                <dd class="text-gray-200">{{ $room->building->phone }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-900 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-200 mb-4">Interested in this room?</h3>
                    <form action="{{ route('tenants.register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300">Full Name</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-300">Phone Number</label>
                                <input type="tel" name="phone" id="phone" required
                                    class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-300">Email (Optional)</label>
                                <input type="email" name="email" id="email"
                                    class="mt-1 block w-full rounded-md border-gray-700 bg-gray-900 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="id_card_front" class="block text-sm font-medium text-gray-300">ID Card (Front)</label>
                                <input type="file" name="id_card_front" id="id_card_front" accept="image/*"
                                    class="mt-1 block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                            </div>

                            <div>
                                <label for="id_card_back" class="block text-sm font-medium text-gray-300">ID Card (Back)</label>
                                <input type="file" name="id_card_back" id="id_card_back" accept="image/*"
                                    class="mt-1 block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-3 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Register Interest
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
