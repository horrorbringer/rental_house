@props(['header' => null])

<div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
    @if($header)
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ $header }}
            </h3>
        </div>
    @endif
    
    <div class="px-4 py-5 sm:p-6">
        {{ $slot }}
    </div>
</div>
