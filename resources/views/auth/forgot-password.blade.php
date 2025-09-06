<x-guest-layout>
    <div class="text-center mb-6">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2.262a2 2 0 01.586-1.414l8.828-8.828A6 6 0 0117 9z">
                </path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('auth.forgot_password') }}</h1>
        <div class="text-sm text-gray-600">
            {{ __('auth.forgot_password_text') }}
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Display any general errors -->
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-300 rounded-md">
            <div class="text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('auth.email')" />
            <div class="mt-2">
                <x-text-input id="email"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                    type="email" name="email" :value="old('email')" required autofocus :placeholder="__('auth.email_placeholder')" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="space-y-4">
            <x-primary-button
                class="w-full justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                {{ __('auth.send_reset_link') }}
            </x-primary-button>

            <div class="text-center">
                <a class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 rounded-md"
                    href="{{ route('login') }}">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('auth.back_to_login') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
