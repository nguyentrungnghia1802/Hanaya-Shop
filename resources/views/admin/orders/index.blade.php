@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Orders') }}
    </h2>
@endsection

@section('content')

    {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="max-h-60 overflow-y-auto bg-white shadow rounded-lg border border-gray-200">
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
    </div> --}}


{{-- <!-- Notification Button -->
@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Orders') }}
    </h2>
@endsection

@section('content')
    <!-- Success message notification -->
    <div id="successMsg"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Order updated successfully!
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border-b">#{{ $item->id }}</td>
                                        <td class="px-4 py-2 border-b">{{ $item->user->name }}</td>
                                        <td class="px-4 py-2 border-b">{{ number_format($item->total_price) }}₫</td>
                                        <td class="px-4 py-2 border-b">{{ $item->created_at }}</td>
                                        <td class="px-4 py-2 border-b">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($item->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($item->status === 'shipped') bg-green-100 text-green-800
                                                @elseif($item->status === 'canceled') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 border-b space-x-2">
                                            <!-- View Details Button -->
                                            <a href="{{ route('admin.order.show', $item->id) }}"
                                                class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                View Details
                                            </a>

                                            @if ($item->status === 'pending')
                                                <!-- Cancel Button -->
                                                <a href="{{ route('order.cancel', $item->id) }}"
                                                    class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete">
                                                    Cancel
                                                </a>

                                                <!-- Confirm Button -->
                                                <form action="{{ route('admin.order.confirm', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">
                                                        Confirm
                                                    </button>
                                                </form>
                                            @elseif ($item->status === 'shipped')
                                                <!-- Disabled Shipped Button -->
                                                <button type="button"
                                                    class="inline-block px-3 py-1 bg-gray-300 text-gray-500 text-xs font-medium rounded cursor-not-allowed"
                                                    disabled>
                                                    Shipped
                                                </button>
                                            @elseif ($item->status === 'canceled')
                                                <!-- Disabled Canceled Button -->
                                                <button type="button"
                                                    class="inline-block px-3 py-1 bg-gray-300 text-gray-500 text-xs font-medium rounded cursor-not-allowed"
                                                    disabled>
                                                    Canceled
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

{{-- <!-- Notification Button -->
<div class="z-[9999]" style="position: fixed; top: 1.5rem; right: 1.5rem;">

    <button id="notificationBell"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition relative">
        Thông báo

        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>
</div> --}}
  {{-- <!-- Bell Icon -->
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
</div> --}}


<!-- Notification Dropdown -->
{{-- <div id="notificationDropdown"
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
</div> --}}




    
    <!-- Notification messages for successful operations -->
    <div id="successMsg"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Order confirm successfully!
    </div>
    <div id="successMsg-delete"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Order deleted successfully!
    </div>
    <div id="successMsg-edit"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Order updated successfully!
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Search input --}}
                    <form id="categorySearchForm" class="flex gap-2 mb-4 max-w-sm">
                        <input type="text" id="searchCategoryInput" placeholder="Search order..."
                            class="border px-3 py-2 rounded w-full" autocomplete="off">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 rounded">Search</button>
                    </form>

                    <!-- Add category button -->
                    <a href="#"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mb-[20px] inline-block transition duration-200">
                        Add
                    </a>

                    <!-- Category list table -->
                    <div class="overflow-x-auto">
                    <table class="min-w-full table-auto border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                            <tr>
                                    <th class="px-2 sm:px-4 py-2 border-b">#</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">User</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">Total price</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">Order at</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">Status</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">Action</th>
                                    
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @foreach ($order as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 border-b">{{ $item->id }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item->user_id }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item->total_price }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item->created_at }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item->status }}</td>
                                    <td class="px-4 py-2 border-b space-x-2">
                                        <!-- Edit button -->
                                        <a href="#"
                                            class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">
                                            Edit
                                        </a>
                                        @if ($item->status === 'pending')
                                            <!-- Delete button -->
                                            <a href="{{ route('order.cancel', $item->id) }}"
                                                class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                                >
                                                Cancel
                                            </a>
                                        @else
                                            <!-- Disabled button -->
                                            <button type="button"
                                                class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
                                                disabled>
                                                Cancelled
                                            </button>
                                        @endif

                                        <!-- View full detail button (redirects to detail page) -->
                                        <a href="{{ route('admin.order.show', $item->id) }}"
                                            class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                            View Details
                                        </a>

                                        <!-- Confirm button -->
@if ($item->status === 'shipped')
    <button type="button"
        class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
        disabled>
        Shipped
    </button>
@elseif ($item->status === 'canceled')
    <button type="button"
        class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
        disabled>
        Canceled
    </button>
@else
    <form action="{{ route('admin.order.confirm', $item->id) }}" method="POST" class="inline">
        @csrf
        @method('PUT')
        <button type="submit"
            class="inline-block px-3 py-1 bg-gray-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
            Confirm
        </button>
    </form>
@endif


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    {{-- Pagination links --}}
                    <div class="mt-6 flex justify-center">
                        {{-- {{ $categories->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- 
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
</script> --}}


@endsection