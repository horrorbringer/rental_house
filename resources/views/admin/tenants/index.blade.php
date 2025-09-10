@extends('layouts.dashboard')

@section('title', 'Tenants')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-gray-100 sm:truncate sm:text-3xl sm:tracking-tight">Tenants</h2>
        <a href="{{ route('tenants.create') }}"
           class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Add New Tenant
        </a>
    </div>

    <!-- Tenants Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($tenants as $tenant)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden hover:ring-2 hover:ring-indigo-500 transition duration-150">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $tenant->name }}</h3>
                        <span class="inline-flex items-center rounded-full bg-indigo-100 dark:bg-indigo-400/10 px-2 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 ring-1 ring-inset ring-indigo-500/20 dark:ring-indigo-400/30">
                            {{ $tenant->rentals_count }} {{ Str::plural('rental', $tenant->rentals_count) }}
                        </span>
                    </div>
                    
                    <dl class="mt-4 space-y-2">
                        <div class="flex items-center text-sm">
                            <dt class="flex-shrink-0 text-gray-500 dark:text-gray-400">
                                <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </dt>
                            <dd class="text-gray-600 dark:text-gray-300">{{ $tenant->phone }}</dd>
                        </div>
                        
                        @if($tenant->email)
                        <div class="flex items-center text-sm">
                            <dt class="flex-shrink-0 text-gray-500 dark:text-gray-400">
                                <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </dt>
                            <dd class="text-gray-600 dark:text-gray-300">{{ $tenant->email }}</dd>
                        </div>
                        @endif
                        
                        <div class="flex items-center text-sm">
                            <dt class="flex-shrink-0 text-gray-500 dark:text-gray-400">
                                <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </dt>
                            <dd class="text-gray-600 dark:text-gray-300">{{ $tenant->id_card_number }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:px-6">
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('tenants.show', $tenant) }}"
                           class="inline-flex items-center px-2.5 py-1.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 focus:outline-none focus:text-indigo-700 dark:focus:text-indigo-200">
                            View
                        </a>
                        <a href="{{ route('tenants.edit', $tenant) }}"
                           class="inline-flex items-center px-2.5 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus:outline-none focus:text-blue-700 dark:focus:text-blue-200">
                            Edit
                        </a>
                        <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-2.5 py-1.5 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-500 dark:hover:text-red-300 focus:outline-none focus:text-red-700 dark:focus:text-red-200"
                                    onclick="return confirm('Are you sure you want to delete this tenant?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
                    <div class="mx-auto w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No tenants found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding a new tenant.</p>
                    <div class="mt-6">
                        <a href="{{ route('tenants.create') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Tenant
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tenants->links() }}
    </div>
</div>
@endsection
