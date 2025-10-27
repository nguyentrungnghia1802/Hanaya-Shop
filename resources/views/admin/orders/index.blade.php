@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Orders') }}
    </h2>
@endsection

@section('content')

    <!-- Thông báo lỗi -->
    <x-alert />

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
                                    <th class="px-2 sm:px-4 py-2 border-b">Payment status</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800">
                                @foreach ($order as $item)
                                    @php
                                        $matchedPayment = $payment->firstWhere('order_id', $item->id);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-2 border-b">{{ $item->id }}</td>
                                        <td class="px-4 py-2 border-b">{{ $item->user_id }}</td>
                                        <td class="px-4 py-2 border-b">{{ $item->total_price }}</td>
                                        <td class="px-4 py-2 border-b">{{ $item->created_at }}</td>
                                        <td class="px-4 py-2 border-b">{{ $item->status }}</td>
                                        <td class="px-4 py-2 border-b">{{ $matchedPayment->payment_status }}</td>
                                        <td class="px-4 py-2 border-b space-x-2">

                                            @if ($item->status === 'pending')
                                                <!-- Cancel button -->
                                                <form action="{{ route('admin.orders.cancel', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Disabled Cancel button -->
                                                <button type="button"
                                                    class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
                                                    disabled>
                                                    Cancel
                                                </button>
                                            @endif

                                            <!-- View full detail button -->
                                            <a href="{{ route('admin.order.show', $item->id) }}"
                                                class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                View Details
                                            </a>

                                            <!-- Confirm button -->
                                            @if ($item->status === 'pending')
                                                <form action="{{ route('admin.order.confirm', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-gray-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                        Confirm
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button"
                                                    class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
                                                    disabled>
                                                    Confirm
                                                </button>
                                            @endif

                                            <!-- Shipped button -->
                                            @if ($item->status === 'processing')

                                                <form action="{{ route('admin.order.shipped', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                        Shipped
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button"
                                                    class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
                                                    disabled>
                                                    Shipped
                                                </button>
                                            @endif

                                            <!-- Paid button -->
                                            {{-- @php
                                                $matchedPayment = $payment->firstWhere('order_id', $item->id);
                                            @endphp --}}
                                            @if (($item->status === 'processing' || $item->status === 'shipped') && $matchedPayment->payment_status === 'pending')
                                                <form action="{{ route('admin.order.paid', $item->id) }}" method="POST" class="inline">
                                                     @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-pink-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                        Paid
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button"
                                                    class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
                                                    disabled>
                                                    Paid
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (nếu có) --}}
                    <div class="mt-6 flex justify-center">
                        {{-- {{ $categories->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
