<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-pink-600 drop-shadow">{{ __('auth.log_in') }}</h1>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('auth.email')" />
            <x-text-input id="email" class="block mt-1 w-full text-xs sm:text-base" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" :placeholder="__('auth.email_placeholder')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth.password')" />
            <x-text-input id="password" class="block mt-1 w-full text-xs sm:text-base"
                            type="password"
                            name="password"
                            required autocomplete="current-password" :placeholder="__('auth.password_placeholder')" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('auth.remember_me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('auth.forgot_password') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('auth.log_in') }}
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                {{ __('auth.dont_have_account') }}
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md">
                    {{ __('auth.register') }}
                </a>
            </p>
        </div>

        <!-- Test Account Section -->
        <div class="mt-6 border-t border-gray-200 pt-6">
            <div class="text-center">
                <button type="button" 
                        onclick="toggleTestAccounts()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('auth.test_account_button') }}
                </button>
            </div>
            
            <div id="testAccountsPanel" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="text-center mb-3">
                    <h3 class="text-sm font-semibold text-blue-800 mb-1">
                        {{ __('auth.test_account_experience_title') }}
                    </h3>
                    <p class="text-xs text-blue-600">
                        {{ __('auth.test_account_experience_description') }}
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                    @for($i = 0; $i <= 9; $i++)
                        <div class="flex items-center justify-between p-2 bg-white rounded border text-xs">
                            <span class="font-mono text-gray-700">testuser{{ $i }}@gmail.com</span>
                            <button type="button" 
                                    onclick="fillTestAccount('testuser{{ $i }}@gmail.com', '123456789')"
                                    class="ml-2 px-2 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition">
                                Use
                            </button>
                        </div>
                    @endfor
                </div>
                
                <div class="text-center space-y-1">
                    <p class="text-xs text-blue-600 font-medium">
                        {{ __('auth.test_account_free_note') }}
                    </p>
                    <p class="text-xs text-gray-600">
                        {!! str_replace(':password', '<span class="font-mono font-semibold">123456789</span>', __('auth.test_account_password_note')) !!}
                    </p>
                </div>
            </div>
        </div>
    </form>

    <script>
        function toggleTestAccounts() {
            const panel = document.getElementById('testAccountsPanel');
            if (panel.classList.contains('hidden')) {
                panel.classList.remove('hidden');
                panel.classList.add('animate-fadeIn');
            } else {
                panel.classList.add('hidden');
                panel.classList.remove('animate-fadeIn');
            }
        }

        function fillTestAccount(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            
            // Optional: Show a brief success message
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '✓';
            button.classList.add('bg-green-500');
            button.classList.remove('bg-blue-500');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-500');
                button.classList.add('bg-blue-500');
            }, 1000);
        }
    </script>

    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-guest-layout>
