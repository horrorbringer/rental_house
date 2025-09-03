@extends('layouts.dashboard')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        @apply bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700/50 shadow-lg transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-600;
    }
    .stat-icon {
        @apply p-3 rounded-full;
    }
    .stat-icon-primary {
        @apply bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400;
    }
    .stat-icon-success {
        @apply bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400;
    }
    .stat-icon-warning {
        @apply bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400;
    }
    .stat-icon-info {
        @apply bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400;
    }
    .activity-item {
        @apply relative pb-8 last:pb-0;
    }
    .activity-item:not(:last-child):before {
        content: '';
        @apply absolute left-4 top-8 bottom-0 w-px bg-gray-200 dark:bg-gray-700;
    }
    .dashboard-action {
        @apply flex items-center gap-3 px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-600 transition-all duration-200 group;
    }
    .dashboard-action-icon {
        @apply w-10 h-10 rounded-lg flex items-center justify-center bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 group-hover:bg-primary-50 dark:group-hover:bg-primary-900/50 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200;
    }
    .quick-stat {
        @apply flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200/50 dark:border-gray-700/50;
    }
    .quick-stat-icon {
        @apply w-12 h-12 rounded-lg flex items-center justify-center text-2xl font-semibold;
    }
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        Welcome back, {{ Auth::user()->name }}!
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Here's what's happening with your properties today.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-3">
                    <a href="{{ route('buildings.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Property
                    </a>
                    <a href="{{ route('rentals.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        New Rental
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('payments.index') }}" class="dashboard-action">
                <span class="dashboard-action-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </span>
                <div>
                    <span class="font-medium text-gray-900 dark:text-white">Record Payment</span>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Process new payments</p>
                </div>
            </a>

            <a href="{{ route('invoices.index') }}" class="dashboard-action">
                <span class="dashboard-action-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </span>
                <div>
                    <span class="font-medium text-gray-900 dark:text-white">Generate Invoice</span>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Create new invoices</p>
                </div>
            </a>

            <a href="{{ route('utility-usages.index') }}" class="dashboard-action">

                <span class="dashboard-action-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div>
                    <span class="font-medium text-gray-900 dark:text-white">Record Usage</span>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Update utility readings</p>
                </div>
            </a>

            <a href="{{ route('tenants.create') }}" class="dashboard-action">
                <span class="dashboard-action-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </span>
                <div>
                    <span class="font-medium text-gray-900 dark:text-white">Add Tenant</span>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Register new tenants</p>
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
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($monthlyRevenue, 0) }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <div class="inline-flex items-baseline">
                        <span class="text-2xl font-semibold text-green-600 dark:text-green-400">
                            ${{ number_format($totalRevenuePaid, 0) }}
                        </span>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">collected</span>
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
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $buildings }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('buildings.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                        View all properties →
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
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $rooms }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200">
                        {{ $vacantRooms }} Available
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                        {{ $occupiedRooms }} Occupied
                    </span>
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
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $occupancyRate }}%</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="relative w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-500"
                             style="width: {{ $occupancyRate }}%; background-color: {{ $occupancyRate >= 80 ? '#059669' : ($occupancyRate >= 50 ? '#0EA5E9' : '#EF4444') }}">
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Overall occupancy status
                    </p>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Recent Activity -->
            <div class="xl:col-span-2">
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                        <div class="flex gap-2">
                            <button class="px-3 py-1 text-sm rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                All
                            </button>
                            <button class="px-3 py-1 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">
                                Tenants
                            </button>
                            <button class="px-3 py-1 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">
                                Payments
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $activity['type'] === 'tenant' ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400' }}">
                                            @if($activity['type'] === 'tenant')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $activity['message'] }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $activity['subject'] }}
                                            @if($activity['type'] === 'payment')
                                                <span class="ml-1 text-green-600 dark:text-green-400">${{ number_format($activity['amount'], 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                            {{ $activity['date']->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Upcoming Payments -->
            <div class="space-y-6">
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Payments</h3>
                        <a href="{{ route('invoices.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                            View All
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($upcomingPayments as $invoice)
                            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $invoice->rental->tenant->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Room {{ $invoice->rental->room->room_number }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            ${{ number_format($invoice->total_amount, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Due {{ $invoice->due_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                @if($invoice->due_date->isPast())
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                            Overdue
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No upcoming payments</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="quick-stat">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Today</p>
                            <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $upcomingPayments->where('due_date', today())->count() }}
                            </p>
                        </div>
                        <div class="quick-stat-icon bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400">
                            {{ $upcomingPayments->where('due_date', today())->count() }}
                        </div>
                    </div>

                    <div class="quick-stat">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue</p>
                            <p class="mt-1 text-xl font-semibold text-red-600 dark:text-red-400">
                                {{ $upcomingPayments->where('due_date', '<', today())->count() }}
                            </p>
                        </div>
                        <div class="quick-stat-icon bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                            {{ $upcomingPayments->where('due_date', '<', today())->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
