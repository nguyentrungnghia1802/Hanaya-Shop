<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('auth.create_account') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('auth.create_account_description') }}</p>
    </div>

    <!-- Gmail Requirement Notice -->
    <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-blue-800">
                    {{ __('auth.gmail_requirement_title') }}
                </h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>{{ __('auth.gmail_requirement_description') }}</p>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-xs sm:text-sm">
                        <li>{{ __('auth.gmail_for_order_updates') }}</li>
                        <li>{{ __('auth.gmail_for_password_recovery') }}</li>
                        <li>{{ __('auth.gmail_for_account_security') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('auth.name')" />
            <x-text-input id="name" class="block mt-1 w-full text-xs sm:text-base" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" :placeholder="__('auth.name_placeholder')" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('auth.email')" />
            <div class="mt-1 relative">
                <x-text-input id="email" class="block w-full pr-12 text-xs sm:text-base" type="email" name="email" :value="old('email')" required autocomplete="username" :placeholder="__('auth.email_placeholder')" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <p class="mt-1 text-xs text-gray-600">{{ __('auth.gmail_required_note') }}</p>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.password')" />
            <x-text-input id="password" class="block mt-1 w-full text-xs sm:text-base"
                            type="password"
                            name="password"
                            required autocomplete="new-password" :placeholder="__('auth.password_placeholder')" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('auth.confirm_password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full text-xs sm:text-base"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" :placeholder="__('auth.confirm_password_placeholder')" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('auth.already_have_account') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('auth.register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
