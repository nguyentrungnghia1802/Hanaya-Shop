<nav x-data="NavigationComponent()" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <img src="{{ asset(config('constants.logo_path')) }}" alt="Logo" class="h-9 w-auto">

                    <a href="{{ route('admin.dashboard') }}">
                        <p style="margin-left: 10px"> HANAYA SHOP </p>

                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.product')" :active="request()->routeIs('admin.product*')">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.category')" :active="request()->routeIs('admin.category*')">
                        {{ __('Categories') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.user')" :active="request()->routeIs('admin.user*')">
                        {{ __('Users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.order')" :active="request()->routeIs('admin.order*')">
                        {{ __('Orders') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.post.index')" :active="request()->routeIs('admin.post*')">
                        {{ __('Posts') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }} (admin)</div>

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
                        <x-dropdown-link :href="route('admin.profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard*')">
                            {{ __('Admin Dashboard') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                            {{ __('User Dashboard') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="getHamburgerIconClass()" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="getCloseIconClass()" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="getResponsiveMenuClass()" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.product')" :active="request()->routeIs('admin.product*')">
                {{ __('Products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.category')" :active="request()->routeIs('admin.category*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.user')" :active="request()->routeIs('admin.user*')">
                {{ __('Users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.order')" :active="request()->routeIs('admin.order*')">
                {{ __('Orders') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.post.index')" :active="request()->routeIs('admin.post*')">
                {{ __('Posts') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard*')">
                    {{ __('Admin Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                    {{ __('User Dashboard') }}
                </x-responsive-nav-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
  <!-- Bell Icon -->
<div class="z-[9999]" style="position: fixed; top: 1.3rem; right: 3rem;">
    <button id="notificationBell" class="relative focus:outline-none">
        <svg class="w-6 h-6 text-gray-600 hover:text-gray-800 transition" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-0.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full animate-ping">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>
</div>

<div id="notificationDropdown"
     class="hidden max-h-72 overflow-y-auto bg-white shadow-lg rounded-lg border border-gray-200 z-50" style="position: fixed; top: 3rem; right: 5rem;">
    @forelse (auth()->user()->unreadNotifications as $notification)
        <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition">
            <div class="text-gray-800 mb-1">
                {{ $notification->data['message'] }}
            </div>
            <a href="{{ route('admin.order.show', $notification->data['order_id']) }}"
               class="text-sm text-blue-600 hover:underline">
                → Xem chi tiết đơn hàng
            </a>
        </div>
    @empty
        <div class="p-4 text-gray-500 text-sm text-center">Không có thông báo mới.</div>
    @endforelse
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bell = document.getElementById('notificationBell');
        const dropdown = document.getElementById('notificationDropdown');

        bell.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        // Ẩn dropdown khi click ra ngoài
        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && !bell.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>