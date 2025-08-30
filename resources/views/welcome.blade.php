@extends('layouts.guest')

@section('content')
    <!-- Main Content -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    <div class="space-y-16 lg:space-y-24">
        <!-- Hero Section -->
        <div class="relative text-center rounded-3xl overflow-hidden shadow-2xl transition-shadow duration-300 hover:shadow-indigo-500/20">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 bg-cover bg-center transform transition-transform duration-700 hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=2670&auto=format&fit=crop');">
            </div>
            <div class="relative px-6 py-20 sm:py-32 lg:px-8 bg-gradient-to-b from-gray-900/80 to-gray-900/40 dark:from-gray-900/90 dark:to-gray-900/70">
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white drop-shadow-lg">
                    Find Your Perfect Home
                </h1>
                <p class="mt-4 text-lg text-gray-200 drop-shadow-md max-w-2xl mx-auto">
                    Discover comfortable and affordable rental rooms in prime locations.
                </p>
                <div class="mt-8 space-x-4">
                    <a href="#search-section" class="inline-flex items-center px-8 py-4 border border-transparent text-base font-medium rounded-full text-indigo-700 bg-white hover:bg-gray-200 transition duration-200 shadow-xl">
                        Search Rooms
                    </a>
                    <a href="{{ route('rooms.search') }}" class="inline-flex items-center px-8 py-4 border border-white text-base font-medium rounded-full text-white hover:bg-white/10 transition duration-200">
                        View All Rooms
                    </a>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div id="search-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl hover:scale-[1.02]">
                <form action="{{ route('rooms.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="group">
                        <label for="building" class="block text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">Location</label>
                        <select id="building" name="building" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                            <option value="">Any Location</option>
                            <option value="building-a">Building A</option>
                            <option value="building-b">Building B</option>
                        </select>
                    </div>
                    <div class="group">
                        <label for="price_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">Price Range</label>
                        <div class="mt-1 grid grid-cols-2 gap-2">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-400">$</span>
                                <input type="number" name="price_min" id="price_min" placeholder="Min" class="pl-7 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 dark:text-gray-400">$</span>
                                <input type="number" name="price_max" id="price_max" placeholder="Max" class="pl-7 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                            </div>
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium py-2 px-4 rounded-lg shadow-lg hover:shadow-indigo-500/50 transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Search Rooms
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Featured Rooms Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold tracking-tight bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent inline-block">
                    Featured Rooms
                </h2>
                <div class="mt-4 flex justify-center">
                    <span class="h-1 w-20 bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 rounded-full"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredRooms ?? [] as $room)
                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-lg overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/20 border border-gray-200 dark:border-gray-700">
                    <div class="relative overflow-hidden">
                        <img src="{{ $room->image_url ?? 'https://placehold.co/600x400/1E40AF/FFFFFF?text=Room+Photo' }}"
                             alt="Room Photo"
                             class="w-full h-48 object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute top-4 right-4">
                            <span class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white font-semibold rounded-full text-sm shadow-lg">
                                Available Now
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300">
                                    {{ $room->building->name ?? 'Building' }} - Room {{ $room->number ?? '101' }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $room->description ?? 'Comfortable room with modern amenities' }}
                                </p>
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 bg-clip-text text-transparent">
                                ${{ number_format($room->price ?? 0) }}
                            </span>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="flex items-center">
                                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Floor {{ $room->floor ?? '1' }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                    </svg>
                                    {{ $room->size ?? '20' }} m²
                                </span>
                            </div>
                            <a href="{{ route('rooms.public.show', $room->id ?? 1) }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggleBtn = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        // Check for saved theme preference, otherwise use system preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlElement.classList.add('dark');
        } else {
            htmlElement.classList.remove('dark');
        }

        // Toggle theme
        themeToggleBtn.addEventListener('click', () => {
            htmlElement.classList.toggle('dark');

            // Save preference
            if (htmlElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
        });

        // Add hover animation to cards
        document.querySelectorAll('.room-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    });

    // Remove the old products data as it's not needed anymore
    // const productsData = [
        {
            id: 1,
            name: "Sculptural Lounge Chair",
            description: "An elegant blend of comfort and modern art.",
            price: "1,200",
            image: "https://placehold.co/600x400/1E40AF/FFFFFF?text=Lounge+Chair",
        },
        {
            id: 2,
            name: "Minimalist Coffee Table",
            description: "A functional centerpiece with a clean, simple design.",
            price: "550",
            image: "https://placehold.co/600x400/3B82F6/FFFFFF?text=Coffee+Table",
        },
        {
            id: 3,
            name: "Geometric Bookshelf",
            description: "An innovative storage solution with a unique aesthetic.",
            price: "950",
            image: "https://placehold.co/600x400/93C5FD/000000?text=Bookshelf",
        },
        {
            id: 4,
            name: "Ornate Floor Lamp",
            description: "A statement piece that provides ambient lighting.",
            price: "320",
            image: "https://placehold.co/600x400/9333EA/FFFFFF?text=Floor+Lamp",
        },
        {
            id: 5,
            name: "Modular Sofa Set",
            description: "Customize your living space with our flexible sofa modules.",
            price: "3,500",
            image: "https://placehold.co/600x400/3730A3/FFFFFF?text=Sofa+Set",
        },
        {
            id: 6,
            name: "Dining Set with Stone Top",
            description: "Perfect for entertaining, with a durable, elegant stone finish.",
            price: "1,800",
            image: "https://placehold.co/600x400/6D28D9/FFFFFF?text=Dining+Set",
        }
    ];

    document.addEventListener('DOMContentLoaded', () => {
        const productsContainer = document.getElementById('products-container');

        if (productsContainer) {
            productsData.forEach(product => {
                // Create the product card element
                const card = document.createElement('div');
                card.classList.add('bg-white', 'dark:bg-gray-800', 'rounded-2xl', 'shadow-lg', 'overflow-hidden', 'transition-transform', 'transform', 'hover:scale-105', 'duration-300');

                // Create the image element
                const img = document.createElement('img');
                img.classList.add('w-full', 'h-48', 'object-cover');
                img.src = product.image;
                img.alt = product.name;

                // Create the content container
                const contentDiv = document.createElement('div');
                contentDiv.classList.add('p-6');

                // Create the product name heading
                const nameHeading = document.createElement('h3');
                nameHeading.classList.add('text-xl', 'font-semibold', 'text-gray-900', 'dark:text-gray-100');
                nameHeading.textContent = product.name;

                // Create the description paragraph
                const descriptionPara = document.createElement('p');
                descriptionPara.classList.add('mt-1', 'text-sm', 'text-gray-500', 'dark:text-gray-400');
                descriptionPara.textContent = product.description;

                // Create the price element
                const pricePara = document.createElement('p');
                pricePara.classList.add('mt-2', 'text-2xl', 'font-bold', 'text-indigo-600', 'dark:text-indigo-400');
                pricePara.innerHTML = `
                    $${product.price}<span class="text-base font-normal text-gray-500 dark:text-gray-400"></span>
                `;

                // Append elements to the content container
                contentDiv.appendChild(nameHeading);
                contentDiv.appendChild(descriptionPara);
                contentDiv.appendChild(pricePara);

                // Append image and content to the card
                card.appendChild(img);
                card.appendChild(contentDiv);

                // Append the card to the main container
                productsContainer.appendChild(card);
            });
        }
    });
</script>
@endsection
