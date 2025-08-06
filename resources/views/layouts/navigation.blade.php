<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 transition-colors duration-300">
    <!-- Primary Navigation Menu -->

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <img src="{{ asset(config('constants.logo_path')) }}" alt="Logo" class="h-9 w-auto" loading="lazy"
                        fetchpriority="high">
                    <a href="{{ route('user.dashboard') }}">
                        <p class="ml-2.5 font-semibold"> HANAYA SHOP </p>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                        {{ __('common.home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('product.index')" :active="request()->routeIs('product*')">
                        {{ __('common.products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart*')">
                        {{ __('common.cart') }}
                    </x-nav-link>
                    <x-nav-link :href="route('order.index')" :active="request()->routeIs('order*')">
                        {{ __('common.orders') }}
                    </x-nav-link>
                    <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts*')">
                        Posts
                    </x-nav-link>
                    <x-nav-link :href="route('user.about')" :active="request()->routeIs('user.about')">
                        {{ __('common.about') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Language Switcher & Settings -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Language Switcher -->
                <x-language-switcher />

                <!-- Settings Dropdown -->
                @auth
                    <!-- Settings Dropdown for authenticated users -->
                    <div>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('common.profile') }}
                                </x-dropdown-link>
                                @if (Auth::user() && Auth::user()->role === 'admin')
                                    <x-dropdown-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                        {{ __('common.dashboard') }} (Admin)
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                                        {{ __('common.dashboard') }} (User)
                                    </x-dropdown-link>
                                @endif
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" data-logout-link>
                                        {{ __('common.logout') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <!-- Settings Dropdown for guests -->
                    <div>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ __('common.login') }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('register')">
                                    {{ __('common.register') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('login')">
                                    {{ __('common.login') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                {{ __('common.home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('product.index')" :active="request()->routeIs('product*')">
                {{ __('common.products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart*')">
                {{ __('common.cart') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('order.index')" :active="request()->routeIs('order*')">
                {{ __('common.orders') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts*')">
                Posts
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('user.about')" :active="request()->routeIs('user.about')">
                {{ __('common.about') }}
            </x-responsive-nav-link>

            <!-- Language Switcher for Mobile -->
            <div class="px-4 py-2">
                <div class="text-sm text-gray-600 mb-2">{{ __('common.language') }}</div>
                <div class="space-y-1">
                    <a href="{{ route('locale.set', 'en') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-md {{ app()->getLocale() === 'en' ? 'bg-pink-100 text-pink-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="w-6 text-center mr-3">🇺🇸</span>
                        {{ __('common.english') }}
                    </a>
                    <a href="{{ route('locale.set', 'vi') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-md {{ app()->getLocale() === 'vi' ? 'bg-pink-100 text-pink-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="w-6 text-center mr-3">🇻🇳</span>
                        {{ __('common.vietnamese') }}
                    </a>
                    <a href="{{ route('locale.set', 'ja') }}"
                        class="flex items-center px-3 py-2 text-sm rounded-md {{ app()->getLocale() === 'ja' ? 'bg-pink-100 text-pink-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="w-6 text-center mr-3">🇯🇵</span>
                        {{ __('common.japanese') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('common.profile') }}
                    </x-responsive-nav-link>
                    @if (Auth::user() && Auth::user()->role === 'admin')
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('common.dashboard') }} (Admin)
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                            {{ __('common.dashboard') }} (User)
                        </x-responsive-nav-link>
                    @endif
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" data-logout-link>
                            {{ __('common.logout') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <!-- Guest user responsive options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('common.login') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('common.register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>
