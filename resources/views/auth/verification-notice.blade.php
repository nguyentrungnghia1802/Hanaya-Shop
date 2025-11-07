<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 text-center">
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ __('auth.verify_email_title') }}</h1>
        
        <p class="mb-4">
            {{ __('auth.verify_email_sent') }}
        </p>
        
        <p class="mb-6 text-pink-600 font-medium">
            {{ session('pending_registration.email') }}
        </p>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800">
                <strong>{{ __('auth.important') }}:</strong> {{ __('auth.verify_email_instructions') }}
            </p>
        </div>

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                    {{ __('auth.resend_verification_email') }}
                </button>
            </form>

            <div class="text-center">
                <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    {{ __('auth.register_different_email') }}
                </a>
            </div>

            <!-- Support Contact -->
            <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-600 mb-2">{{ __('auth.need_help') }}</p>
                @php
                    $userEmail = session('pending_registration.email', '');
                    $supportSubject = __('auth.support_email_subject');
                    $supportBody = __('auth.support_email_body', ['email' => $userEmail]);
                @endphp
                <a href="mailto:support@hanayashop.com?subject={{ urlencode($supportSubject) }}&body={{ urlencode($supportBody) }}" 
                   class="inline-flex items-center text-sm text-pink-600 hover:text-pink-700 font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('auth.contact_support') }}
                </a>
            </div>
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 text-center">
            {{ __('auth.verification_link_resent') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 text-center">
            {{ $errors->first() }}
        </div>
    @endif
</x-guest-layout>
