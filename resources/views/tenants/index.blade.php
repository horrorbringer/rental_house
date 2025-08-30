@extends('layouts.dashboard')

@section('title', 'Tenants')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold leading-7 text-gray-100 sm:truncate sm:text-3xl sm:tracking-tight">Tenants</h2>
        <a href="{{ route('tenants.create') }}"
           class="inline-flex items-center rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
            Add New Tenant
        </a>
    </div>

    <!-- Tenants List -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead>
                <tr>
                    <th class="py-3.5 px-4 text-left text-sm font-semibold text-gray-200">Name</th>
                    <th class="py-3.5 px-4 text-left text-sm font-semibold text-gray-200">Phone</th>
                    <th class="py-3.5 px-4 text-left text-sm font-semibold text-gray-200">Email</th>
                    <th class="py-3.5 px-4 text-left text-sm font-semibold text-gray-200">Active Rentals</th>
                    <th class="py-3.5 px-4 text-left text-sm font-semibold text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($tenants as $tenant)
                <tr>
                    <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-300">{{ $tenant->name }}</td>
                    <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-300">{{ $tenant->phone }}</td>
                    <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-300">{{ $tenant->email ?? '-' }}</td>
                    <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-300">{{ $tenant->rentals_count }}</td>
                    <td class="whitespace-nowrap py-4 px-4 text-sm text-gray-300 flex gap-2">
                        <a href="{{ route('tenants.show', $tenant) }}"
                           class="text-indigo-400 hover:text-indigo-300">View</a>
                        <a href="{{ route('tenants.edit', $tenant) }}"
                           class="text-blue-400 hover:text-blue-300">Edit</a>
                        <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300"
                                onclick="return confirm('Are you sure you want to delete this tenant?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tenants->links() }}
    </div>
</div>
@endsection
