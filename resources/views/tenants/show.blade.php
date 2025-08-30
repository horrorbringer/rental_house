@extends('layouts.dashboard')

@section('title', 'Tenant Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-100">Tenant Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-400">Personal details and rental history.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tenants.edit', $tenant) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Edit
                </a>
                <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        onclick="return confirm('Are you sure you want to delete this tenant?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        <div class="border-t border-gray-700 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-400">Full name</dt>
                    <dd class="mt-1 text-sm text-gray-200">{{ $tenant->name }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-400">Phone number</dt>
                    <dd class="mt-1 text-sm text-gray-200">{{ $tenant->phone }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-400">Email address</dt>
                    <dd class="mt-1 text-sm text-gray-200">{{ $tenant->email ?? '-' }}</dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-400">ID Cards</dt>
                    <dd class="mt-2 flex gap-4">
                        @if($tenant->id_card_front_path)
                            <div>
                                <p class="text-sm text-gray-400 mb-2">Front</p>
                                <img src="{{ Storage::url($tenant->id_card_front_path) }}" alt="ID Card Front" class="h-48 w-auto">
                            </div>
                        @endif
                        @if($tenant->id_card_back_path)
                            <div>
                                <p class="text-sm text-gray-400 mb-2">Back</p>
                                <img src="{{ Storage::url($tenant->id_card_back_path) }}" alt="ID Card Back" class="h-48 w-auto">
                            </div>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Rental History -->
    <div class="mt-8 bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-100">Rental History</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-400">Current and past rentals.</p>
        </div>
        <div class="border-t border-gray-700">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Building</th>
                        <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($tenant->rentals as $rental)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $rental->room->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $rental->room->building->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $rental->start_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $rental->end_date ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $rental->end_date ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $rental->end_date ? 'Past' : 'Active' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
