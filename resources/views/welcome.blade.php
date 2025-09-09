@extends('layouts.guest')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Hanuman:wght@100..900&display=swap');
    .font-hanuman {
        font-family: 'Hanuman', serif;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="space-y-16 lg:space-y-24">
        <!-- Hero Section -->
        <div class="relative text-center rounded-3xl overflow-hidden shadow-2xl transition-shadow duration-300 hover:shadow-indigo-500/20">
            <div class="absolute inset-0 bg-cover bg-center transform transition-transform duration-700 hover:scale-105" 
                 style="background-image: url('{{ asset('images/house-7124141_1280.jpg') }}');">
            </div>
            <div class="relative px-6 py-20 sm:py-32 lg:px-8 bg-gradient-to-b from-gray-900/80 to-gray-900/40">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl font-hanuman">
                    សូមស្វាគមន៍មកកាន់ មុង្គលរេស៊ីដិន
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-300 font-hanuman">
                    ជ្រើសរើសបន្ទប់ជួលដែលល្អបំផុតសម្រាប់អ្នក
                </p>
            </div>
        </div>

        <!-- Search Section -->
        <div id="search-section" class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                <form action="{{ route('tenant.rooms.search') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">តម្លៃអប្បបរមា</label>
                            <input type="number" name="min_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">តម្លៃអតិបរមា</label>
                            <input type="number" name="max_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 font-hanuman">អគារ</label>
                        <select name="building" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">ជ្រើសរើសអគារ</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-hanuman">
                            ស្វែងរក
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Buildings Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl font-hanuman">
                    អគាររបស់យើង
                </h2>
                <p class="mt-4 text-lg leading-8 text-gray-600 dark:text-gray-400 font-hanuman">
                    ជ្រើសរើសអគារដែលអ្នកចង់ស្នាក់នៅ
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($buildings as $building)
                <div class="group relative overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl">
                    <div class="aspect-w-16 aspect-h-9">
                        @php
                            $image = $building->images()->first();
                        @endphp
                        <img src="{{ $image ? Storage::url($image->path) : asset('images/building-placeholder.jpg') }}" 
                             alt="{{ $building->name }}" 
                             class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 p-6 text-white">
                        <h3 class="text-xl font-bold font-hanuman">{{ $building->name }}</h3>
                        <p class="mt-2 text-sm text-gray-300 font-hanuman">{{ $building->address }}</p>
                        <a href="{{ route('tenant.rooms.index', ['building' => $building->id]) }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 font-hanuman">
                            មើលបន្ថែម
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Featured Rooms Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl font-hanuman">
                    បន្ទប់ដែលនៅទំនេរ
                </h2>
                <p class="mt-4 text-lg leading-8 text-gray-600 dark:text-gray-400 font-hanuman">
                    បន្ទប់ដែលអាចជួលបាន
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($availableRooms as $room)
                <div class="group bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ $room->image ? Storage::url($room->image) : asset('images/room-placeholder.jpg') }}" 
                             alt="Room {{ $room->room_number }}" 
                             class="object-cover w-full h-full">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white font-hanuman">
                                បន្ទប់លេខ {{ $room->room_number }}
                            </h3>
                            <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full font-hanuman">
                                ទំនេរ
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 font-hanuman">
                            {{ $room->building->name }}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 font-hanuman">
                                ${{ number_format($room->monthly_rent, 2) }}/ខែ
                            </span>
                            <a href="{{ route('tenant.rooms.show', $room) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 font-hanuman">
                                មើលបន្ថែម
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Theme toggle functionality
        const themeToggleBtn = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlElement.classList.add('dark');
        } else {
            htmlElement.classList.remove('dark');
        }

        themeToggleBtn?.addEventListener('click', () => {
            htmlElement.classList.toggle('dark');
            localStorage.theme = htmlElement.classList.contains('dark') ? 'dark' : 'light';
        });

        // Room card hover animation
        document.querySelectorAll('.room-card').forEach(card => {
            card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-10px)');
            card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
        });
    });
</script>
@endsection
