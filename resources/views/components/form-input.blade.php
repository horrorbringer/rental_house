{{-- Form Input Component --}}
@props(['label', 'name', 'type' => 'text', 'value' => '', 'required' => false])

<div class="space-y-1">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    @if($type === 'textarea')
        <textarea 
            id="{{ $name }}"
            name="{{ $name }}"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error($name) border-red-300 dark:border-red-600 text-red-900 dark:text-red-300 placeholder-red-300 dark:placeholder-red-600 focus:border-red-500 dark:focus:border-red-500 focus:ring-red-500 dark:focus:ring-red-500 @enderror"
            {{ $required ? 'required' : '' }}
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'file')
        <input 
            type="file"
            id="{{ $name }}"
            name="{{ $name }}"
            class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                dark:file:bg-indigo-900 dark:file:text-indigo-300
                hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800"
            {{ $required ? 'required' : '' }}
        />
    @else
        <input 
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error($name) border-red-300 dark:border-red-600 text-red-900 dark:text-red-300 placeholder-red-300 dark:placeholder-red-600 focus:border-red-500 dark:focus:border-red-500 focus:ring-red-500 dark:focus:ring-red-500 @enderror"
            {{ $required ? 'required' : '' }}
        />
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
    @enderror
</div>
