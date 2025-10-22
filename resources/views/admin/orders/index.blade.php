@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Orders') }}
    </h2>
@endsection

@section('content')

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
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
    </div>


    
    <!-- Notification messages for successful operations -->
    <div id="successMsg"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Order added successfully!
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
                        <input type="text" id="searchCategoryInput" placeholder="Search category..."
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

                                        <!-- Delete button -->
                                        <button type="button"
                                            class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                            >
                                            Cancel
                                        </button>

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

@endsection