<x-guest-layout>
    <div class="text-center">
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('auth.verification_success_title') }}</h1>
        
        <p class="text-gray-600 mb-6">
            {{ __('auth.verification_success_message') }}
        </p>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
            <p class="text-sm text-green-800">
                <strong>{{ __('auth.welcome') }}!</strong> {{ __('auth.account_ready') }}
            </p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                {{ __('auth.go_to_dashboard') }}
            </a>

            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    {{ __('auth.continue_shopping') }}
                </a>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                {{ __('auth.verification_completed_at') }} {{ now()->format('Y-m-d H:i:s') }}
            </p>
        </div>
    </div>
</x-guest-layout>
