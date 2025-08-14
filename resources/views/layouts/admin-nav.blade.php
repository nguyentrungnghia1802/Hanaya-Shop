<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <img src="{{ asset(config('constants.logo_path')) }}" alt="Logo" class="h-9 w-auto">

                    <a href="{{ route('admin.dashboard') }}">
                        <p class="ml-2.5 font-semibold"> HANAYA SHOP </p>

                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.product')" :active="request()->routeIs('admin.product*')">
                        {{ __('admin.products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.category')" :active="request()->routeIs('admin.category*')">
                        {{ __('admin.categories') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.user')" :active="request()->routeIs('admin.user*')">
                        {{ __('admin.users') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.order')" :active="request()->routeIs('admin.order*')">
                        {{ __('admin.orders') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.post.index')" :active="request()->routeIs('admin.post*')">
                        {{ __('admin.posts') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <x-language-switcher />
                    
                    <!-- Bell Icon -->
                    <div class="relative">
                        <button id="notificationBell" class="relative focus:outline-none bg-white rounded-full p-2 shadow-lg hover:shadow-xl transition-shadow">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 hover:text-gray-800 transition" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>

                            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
 
                        </button>

                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown"
                             class="hidden absolute top-full right-0 mt-1 w-72 sm:w-80 max-h-72 overflow-y-auto bg-white shadow-lg rounded-lg border border-gray-200 z-50">
                           @forelse (auth()->user()->unreadNotifications as $notification)
                                <div class="notification-item p-3 sm:p-4 border-b border-gray-100 hover:bg-gray-50 transition cursor-pointer"
                                     data-id="{{ $notification->id }}">
                                    <div class="text-gray-800 mb-1 text-sm">
                                        {{ $notification->data['message'] ?? 'New order received' }}
                                    </div>
                                    <a href="{{ route('admin.order.show', ['id' => $notification->data['order_id'], 'notification_id' => $notification->id]) }}"
                                       class="text-sm text-blue-600 hover:text-blue-800">
                                        {{ __('admin.view_details') }} →
                                    </a>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-gray-500 text-sm text-center">{{ __('admin.no_notifications') }}</div>
                            @endforelse

                        </div>
                    </div>

                    <!-- User Dropdown -->
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
                            {{ __('admin.profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard*')">
                            {{ __('admin.admin_dashboard') }} (Admin)
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                            {{ __('admin.user_dashboard') }} (User)
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('admin.log_out') }}
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
                </div>
            </div>
            
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <!-- Language Switcher Mobile -->
                <div class="mr-2">
                    <x-language-switcher />
                </div>
                
                <!-- Notification Bell Mobile -->
                <div class="relative mr-2">
                    <button id="notificationBellMobile" class="relative focus:outline-none bg-white rounded-full p-2 shadow-lg hover:shadow-xl transition-shadow">
                        <svg class="w-5 h-5 text-gray-600 hover:text-gray-800 transition" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>

                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Mobile Notification Dropdown -->
                    <div id="notificationDropdownMobile"
                         class="hidden absolute top-full right-0 mt-1 w-72 max-h-72 overflow-y-auto bg-white shadow-lg rounded-lg border border-gray-200 z-50">
                        @forelse (auth()->user()->unreadNotifications as $notification)
                            <div class="p-3 border-b border-gray-100 hover:bg-gray-50 transition">
                                <div class="text-gray-800 mb-1 text-sm">
                                    {{ $notification->data['message'] ?? 'New order received' }}
                                </div>
                                <a href="{{ route('admin.order.show', $notification->data['order_id']) }}"
                                   class="text-sm text-blue-600 hover:text-blue-800">
                                    {{ __('admin.view_details') }} →
                                </a>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-gray-500 text-sm text-center">{{ __('admin.no_notifications') }}</div>
                        @endforelse
                    </div>
                </div>
                
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
            <x-responsive-nav-link :href="route('admin.product')" :active="request()->routeIs('admin.product*')">
                {{ __('admin.products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.category')" :active="request()->routeIs('admin.category*')">
                {{ __('admin.categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.user')" :active="request()->routeIs('admin.user*')">
                {{ __('admin.users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.order')" :active="request()->routeIs('admin.order*')">
                {{ __('admin.orders') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.post.index')" :active="request()->routeIs('admin.post*')">
                {{ __('admin.posts') }}
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
                    {{ __('admin.profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard*')">
                    {{ __('admin.admin_dashboard') }} (Admin)
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                    {{ __('admin.user_dashboard') }} (User)
                </x-responsive-nav-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out border-l-4 border-transparent hover:border-gray-300">
                        {{ __('admin.log_out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Desktop notification bell
        const bell = document.getElementById('notificationBell');
        const dropdown = document.getElementById('notificationDropdown');
        const badge = document.querySelector('#notificationBell span');
        
        // Mobile notification bell
        const bellMobile = document.getElementById('notificationBellMobile');
        const dropdownMobile = document.getElementById('notificationDropdownMobile');

        // Desktop notification bell click handler
        if (bell && dropdown) {
            bell.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
                // Hide mobile dropdown if open
                if (dropdownMobile && !dropdownMobile.classList.contains('hidden')) {
                    dropdownMobile.classList.add('hidden');
                }
            });
        }

        // Mobile notification bell click handler
        if (bellMobile && dropdownMobile) {
            bellMobile.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdownMobile.classList.toggle('hidden');
                // Hide desktop dropdown if open
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            // Close desktop dropdown
            if (dropdown && !dropdown.contains(e.target) && bell && !bell.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
            
            // Close mobile dropdown
            if (dropdownMobile && !dropdownMobile.contains(e.target) && bellMobile && !bellMobile.contains(e.target)) {
                dropdownMobile.classList.add('hidden');
            }
        });

        document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function () {
            const notificationId = this.dataset.id;

            // Giảm số lượng badge
            if (badge) {
                let count = parseInt(badge.textContent.trim());
                if (count > 1) {
                    badge.textContent = count - 1;
                } else {
                    badge.remove();
                }
            }

            // Gửi request tới controller
            fetch('/admin/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: notificationId })
            });
        });
    });
    });
</script>