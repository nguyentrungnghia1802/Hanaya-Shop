<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('auth.update_password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('auth.update_password_description') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 sm:mt-6 space-y-4 sm:space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('auth.current_password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full text-xs sm:text-base" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('auth.new_password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full text-xs sm:text-base" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('auth.confirm_password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full text-xs sm:text-base" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('auth.save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('auth.saved') }}</p>
            @endif
        </div>
    </form>
</section>
