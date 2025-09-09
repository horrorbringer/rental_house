@extends('layouts.dashboard')
@push('styles')
<style>
    .stat-card {
        @apply bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 hover:border-primary-200 dark:hover:border-primary-700;
    }
    .stat-card {
        @apply bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700/50 shadow-lg transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-xl;
    }
    .stat-icon {
        @apply p-3 rounded-full transition-transform duration-200 hover:scale-110;
    }
    .stat-icon-primary {
        @apply bg-primary-100/80 dark:bg-primary-900/80 text-primary-600 dark:text-primary-400;
    }
    .stat-icon-success {
        @apply bg-emerald-100/80 dark:bg-emerald-900/80 text-emerald-600 dark:text-emerald-400;
    }
    .stat-icon-warning {
        @apply bg-amber-100/80 dark:bg-amber-900/80 text-amber-600 dark:text-amber-400;
    }
    .stat-icon-info {
        @apply bg-blue-100/80 dark:bg-blue-900/80 text-blue-600 dark:text-blue-400;
    }
    .activity-item {
        @apply relative pb-8 last:pb-0;
    }
    .activity-item:not(:last-child):before {
        content: '';
        @apply absolute left-4 top-8 bottom-0 w-px bg-gray-200 dark:bg-gray-700;
    }
    .dashboard-action {
        @apply flex items-center gap-4 px-6 py-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-700 transition-all duration-300 group hover:-translate-y-1 hover:shadow-lg;
    }
        .dashboard-action-icon {
        @apply w-14 h-14 rounded-xl flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 text-gray-600 dark:text-gray-300 group-hover:scale-110 group-hover:from-primary-50 group-hover:to-primary-100 dark:group-hover:from-primary-900/40 dark:group-hover:to-primary-800/40 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-all duration-300;
    }
    .quick-stat {
        @apply flex items-center justify-between p-5 rounded-2xl bg-gradient-to-br from-gray-50 to-white dark:from-gray-800/50 dark:to-gray-700/50 border border-gray-100 dark:border-gray-700/50 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300;
    }
    .quick-stat-icon {
        @apply w-14 h-14 rounded-xl flex items-center justify-center text-2xl font-semibold transition-transform duration-200 hover:scale-110;
    }
    .stat-value {
        @apply text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-primary-400 dark:from-primary-400 dark:to-primary-300;
    }
    .progress-bar {
        @apply relative w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden;
    }
    .progress-bar-fill {
        @apply absolute top-0 left-0 h-full rounded-full transition-all duration-1000 ease-out;
    }
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
    <div class="p-6">
        <!-- Welcome Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        Good morning, {{ Auth::user()->name }}!
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, F j, Y') }} · Today's overview
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" class="flex items-center px-4 py-2 bg-primary-50 text-primary-700 rounded-lg hover:bg-primary-100 dark:bg-primary-900/50 dark:text-primary-300 dark:hover:bg-primary-900 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh Data
                    </button>
                    <div class="inline-flex rounded-lg shadow-sm">
                        <a href="{{ route('buildings.create') }}" 
                           class="inline-flex items-center px-4 py-2 rounded-l-lg border border-primary-600 bg-primary-600 text-white hover:bg-primary-700 hover:border-primary-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            New Property
                        </a>
                        <a href="{{ route('rentals.create') }}" 
                           class="inline-flex items-center px-4 py-2 rounded-r-lg border border-l-0 border-primary-600 bg-primary-600 text-white hover:bg-primary-700 hover:border-primary-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Add Tenant
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="stat-card group">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-icon stat-icon-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        vs last month
                        <span class="ml-2 text-emerald-500 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </span>
                        <span class="font-medium text-emerald-500 dark:text-emerald-400">12%</span>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Revenue</p>
                <div class="flex items-baseline">
                    <span class="stat-value">₱123,456</span>
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">this month</span>
                </div>
            </div>

            <!-- Active Tenants -->
            <div class="stat-card group">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-icon stat-icon-success">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                        Active
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Tenants</p>
                <div class="flex items-baseline">
                    <span class="stat-value">48</span>
                    <span class="ml-2 text-sm text-emerald-500 dark:text-emerald-400">+3 new this month</span>
                </div>
            </div>

            <!-- Available Rooms -->
            <div class="stat-card group">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-icon stat-icon-info">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                            Available
                        </span>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Available Rooms</p>
                <div class="flex items-baseline">
                    <span class="stat-value">12</span>
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">of 60 total</span>
                </div>
                <div class="mt-3">
                    <div class="progress-bar">
                        <div class="progress-bar-fill bg-blue-500" style="width: 20%;"></div>
                    </div>
                </div>
            </div>

            <!-- Pending Invoices -->
            <div class="stat-card group">
                <div class="flex items-center justify-between mb-4">
                    <div class="stat-icon stat-icon-warning">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                        Pending
                    </span>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Pending Invoices</p>
                <div class="flex items-baseline">
                    <span class="stat-value">15</span>
                    <span class="ml-2 text-sm text-amber-500 dark:text-amber-400">₱45,678 total</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Record Payment -->
            <a href="{{ route('utility-usages.index') }}" class="group relative overflow-hidden dashboard-action bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700/90">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/0 to-emerald-400/0 group-hover:from-emerald-400/5 group-hover:to-emerald-400/10 dark:group-hover:from-emerald-400/10 dark:group-hover:to-emerald-400/20 transition-all duration-300"></div>
                <span class="dashboard-action-icon bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div>
                    <span class="font-semibold text-gray-900 dark:text-white text-lg">Record Usage</span>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update utility readings</p>
                </div>
                <div class="absolute bottom-4 right-4 opacity-0 transform translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('tenants.create') }}" class="group relative overflow-hidden dashboard-action bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700/90">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/0 to-blue-400/0 group-hover:from-blue-400/5 group-hover:to-blue-400/10 dark:group-hover:from-blue-400/10 dark:group-hover:to-blue-400/20 transition-all duration-300"></div>
                <span class="dashboard-action-icon bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </span>
                <div>
                    <span class="font-semibold text-gray-900 dark:text-white text-lg">Add Tenant</span>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Register new tenants</p>
                </div>
                <div class="absolute bottom-4 right-4 opacity-0 transform translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Revenue Card -->
            <div class="stat-card">
                <div class="flex items-center">
                    <div class="stat-icon-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Revenue</p>
                        <p class="mt-1 stat-value text-4xl">
                            ៛{{ number_format($monthlyRevenue, 0) }}
                        </p>
                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Expected monthly revenue
                        </div>
                    </div>
                </div>
            </div>

            <!-- Properties Card -->
            <div class="stat-card">
                <div class="flex items-center">
                    <div class="stat-icon-warning">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Properties</p>
                        <p class="mt-1 stat-value">{{ $buildings }}</p>
                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Total managed properties
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('buildings.index') }}" 
                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-colors duration-200">
                        <span>View all properties</span>
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Rooms Card -->
            <div class="stat-card">
                <div class="flex items-center">
                    <div class="stat-icon-success">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Rooms</p>
                        <p class="mt-1 stat-value">{{ $rooms }}</p>
                        <div class="mt-3 flex items-center gap-3">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $vacantRooms }} Available</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $occupiedRooms }} Occupied</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Occupancy Rate Card -->
            <div class="stat-card">
                <div class="flex items-center">
                    <div class="stat-icon-info">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Occupancy Rate</p>
                        <p class="mt-1 stat-value">{{ $occupancyRate }}%</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="progress-bar">
                        <div class="progress-bar-fill"
                             style="width: {{ $occupancyRate }}%; 
                                    background: linear-gradient(to right, 
                                        {{ $occupancyRate >= 80 ? '#059669, #34d399' : 
                                           ($occupancyRate >= 50 ? '#0EA5E9, #60a5fa' : '#EF4444, #f87171') }})">
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="font-medium text-gray-500 dark:text-gray-400">
                            {{ $occupancyRate >= 80 ? 'Excellent' : ($occupancyRate >= 50 ? 'Good' : 'Need attention') }}
                        </span>
                        <span class="font-medium {{ 
                            $occupancyRate >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 
                            ($occupancyRate >= 50 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400') 
                        }}">{{ $occupancyRate }}% occupied</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
                    <!-- Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Recent Activity -->
            <div class="xl:col-span-2">
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                        <div class="flex gap-2">
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $activity['color'] === 'blue' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600' }}">
                                        @if($activity['type'] === 'tenant')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $activity['message'] }}: {{ $activity['subject'] }}
                                    </p>
                                    @if($activity['type'] === 'tenant')
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Room {{ $activity['room'] }}, {{ $activity['building'] }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $activity['date']->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <div class="stat-card">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Stats</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="quick-stat">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                Total Buildings
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">
                                    {{ $buildings }}
                                </p>
                            </div>
                            <div class="quick-stat-icon bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400">
                                {{ $buildings }}
                            </div>
                        </div>

                        <div class="quick-stat">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                Total Rooms
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">
                                    {{ $rooms }}
                                </p>
                            </div>
                            <div class="quick-stat-icon bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                                {{ $rooms }}
                            </div>
                        </div>

                        <div class="quick-stat">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                Available Rooms
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">
                                    {{ $vacantRooms }}
                                </p>
                            </div>
                            <div class="quick-stat-icon bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                                {{ $vacantRooms }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
