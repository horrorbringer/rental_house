<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" x-data="{ photoPreview: null }">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                <img class="h-16 w-16 object-cover rounded-full ring-2 ring-indigo-500 dark:ring-indigo-400" 
                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff" 
                     alt="{{ $user->name }}">
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Profile Photo</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Your photo will be shown in the navigation menu and your profile.</p>
            </div>
        </div>

        <!-- Name -->
        <div class="relative">
            <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 dark:text-gray-300" />
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <x-text-input id="name" name="name" type="text" 
                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                    :value="old('name', $user->name)" 
                    required autofocus autocomplete="name" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div class="relative">
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 dark:text-gray-300" />
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <x-text-input id="email" name="email" type="email" 
                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                    :value="old('email', $user->email)" 
                    required autocomplete="username" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="flex items-center p-4 mb-4 text-yellow-800 rounded-lg bg-yellow-50 dark:bg-yellow-900/50 dark:text-yellow-300" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3 text-sm font-medium">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="underline hover:text-yellow-600 dark:hover:text-yellow-400 font-semibold ml-1">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-green-900/50 dark:text-green-300" role="alert">
                            <svg class="flex-shrink-0 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3 text-sm font-medium">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" 
                class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="flex items-center text-sm text-green-600 dark:text-green-400 space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ __('Saved successfully.') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
