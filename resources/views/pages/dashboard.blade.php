<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- Slider: sử dụng component slider đã tạo -->
        <section class="mb-8">
            <x-home.slider />
        </section>

        <!-- Nội dung Dashboard chính -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    <p>{{ __("Chào mừng đến với Hanaya Shop Dashboard!") }}</p>
                </div>
            </div>
        </div>

        <!-- Phần quản lý tài khoản: đổi thông tin và log out -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-end gap-4">
                    @auth
                        <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Đổi thông tin
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                Log Out
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
