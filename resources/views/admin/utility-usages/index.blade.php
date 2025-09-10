@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    Utility Usages
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage monthly utility readings and generate invoices
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('utility-usages.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Reading
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Mobile Card View -->
                <div class="block md:hidden space-y-4">
                    @forelse($utilityUsages as $usage)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">
                                        {{ $usage->rental->room->building->name }} - Room {{ $usage->rental->room->room_number }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $usage->rental->tenant->name }}
                                    </p>
                                </div>
                                @if($usage->invoice)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $usage->invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($usage->invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($usage->invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 
                                           'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($usage->invoice->status) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        No Invoice
                                    </span>
                                @endif
                            </div>

                            <!-- Reading Date -->
                            <div class="mb-4">
                                <div class="text-sm font-medium">{{ Carbon\Carbon::parse($usage->reading_date)->format('M j, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ Carbon\Carbon::parse($usage->reading_date)->diffForHumans() }}</div>
                            </div>

                            <!-- Usage Details -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <!-- Water Usage -->
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    <div class="text-sm font-medium mb-1">Water Usage</div>
                                    <div class="text-lg font-semibold">{{ number_format($usage->water_usage, 2) }} m³</div>
                                    <div class="text-xs text-gray-500">Rate: ៛{{ number_format($usage->rental->room->water_fee, 0) }}/m³</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mt-1">
                                        ៛{{ number_format($usage->water_usage * $usage->rental->room->water_fee, 0) }}
                                    </div>
                                </div>

                                <!-- Electric Usage -->
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    <div class="text-sm font-medium mb-1">Electric Usage</div>
                                    <div class="text-lg font-semibold">{{ number_format($usage->electric_usage, 2) }} kWh</div>
                                    <div class="text-xs text-gray-500">Rate: ៛{{ number_format($usage->rental->room->electric_fee, 0) }}/kWh</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mt-1">
                                        ៛{{ number_format($usage->electric_usage * $usage->rental->room->electric_fee, 0) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Total Charges -->
                            {{-- <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg mb-4">
                                <div class="grid grid-cols-2 gap-2 text-sm mb-2">
                                    <div class="text-gray-600 dark:text-gray-400">Water:</div>
                                    <div class="text-right">៛{{ number_format($usage->water_usage * $usage->rental->room->water_rate, 0) }}</div>
                                    <div class="text-gray-600 dark:text-gray-400">Electric:</div>
                                    <div class="text-right">៛{{ number_format($usage->electric_usage * $usage->rental->room->electric_rate, 0) }}</div>
                                    <div class="text-gray-600 dark:text-gray-400">Rent:</div>
                                    <div class="text-right">៛{{ number_format($usage->rental->room->monthly_rent, 0) }}</div>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 mt-2">
                                    <div class="flex justify-between items-center">
                                        <div class="font-medium">Total:</div>
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                                            ៛{{ number_format(
                                                $usage->rental->room->monthly_rent +
                                                ($usage->water_usage * $usage->rental->room->water_rate) +
                                                ($usage->electric_usage * $usage->rental->room->electric_rate),
                                                0
                                            ) }}
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('utility-usages.edit', $usage) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <a href="{{ route('utility-usages.show', $usage) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 dark:bg-green-700 dark:text-green-100 dark:hover:bg-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mb-4 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-xl font-medium">No utility usage records found</p>
                            <p class="mt-1 text-sm text-gray-500">Start by adding a new utility reading</p>
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto relative shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4">
                                    Location & Tenant
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    Reading Date
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    Water Usage
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    Electric Usage
                                </th>
                                <th scope="col" class="px-6 py-4">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($utilityUsages as $usage)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $usage->rental->room->building->name }} - Room {{ $usage->rental->room->room_number }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $usage->rental->tenant->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">
                                            {{ Carbon\Carbon::parse($usage->reading_date)->format('M j, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ Carbon\Carbon::parse($usage->reading_date)->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">
                                            {{ number_format($usage->water_usage, 2) }} m³
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Rate: ៛{{ number_format($usage->rental->room->water_fee, 0) }}/m³
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">
                                            {{ number_format($usage->electric_usage, 2) }} kWh
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Rate: ៛{{ number_format($usage->rental->room->electric_fee, 0) }}/kWh
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('utility-usages.edit', $usage) }}" 
                                               class="px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 dark:bg-indigo-700 dark:text-indigo-100 dark:hover:bg-indigo-600 transition-colors duration-200">
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('utility-usages.show', $usage) }}" 
                                               class="px-3 py-1.5 bg-green-100 text-green-700 rounded-md hover:bg-green-200 dark:bg-green-700 dark:text-green-100 dark:hover:bg-green-600 transition-colors duration-200">
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <p class="text-xl font-medium">No utility usage records found</p>
                                            <p class="mt-1 text-sm text-gray-500">Start by adding a new utility reading</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $utilityUsages->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
