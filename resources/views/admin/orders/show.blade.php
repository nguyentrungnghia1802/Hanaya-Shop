@extends('layouts.admin')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Order Details') }}
    </h2>
@endsection

@section('content')
    <div class="py-10 max-w-5xl mx-auto px-6 space-y-8">
        <!-- Grid 2 columns: Customer Information + Order Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Customer Information</h3>
                <div class="space-y-2 text-gray-700">
                    <p>
                        <span class="font-medium">Customer:</span>
                        {{ $order->user->name }}
                    </p>
                    <p>
                        <span class="font-medium">Email:</span>
                        {{ $order->user->email }}
                    </p>
                    <p>
                        <span class="font-medium">Address:</span>
                        <span class="block text-sm text-gray-600">(+84) 374169035</span>
                        <span class="block text-sm text-gray-600">
                            No. 15, Alley 415 Lane 192 Le Trong Tan,<br>
                            Dinh Cong Ward, Hoang Mai District, Hanoi
                        </span>
                    </p>
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Order Information</h3>
                <div class="space-y-2 text-gray-700">
                    <p>
                        <span class="font-medium">Order Date:</span>
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p>
                        <span class="font-medium">Total Amount:</span>
                        {{ number_format($order->total_price) }}₫
                    </p>
                    <p>
                        <span class="font-medium">Status:</span>
                        {{ $order->status }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Product List</h3>
            <div class="space-y-4">
                @foreach ($order->orderDetail as $detail)
                    <a href="{{ route('product.show', $detail->product->id) }}"
                       class="block hover:shadow-md hover:bg-gray-100 transition duration-200 ease-in-out rounded-lg">
                        <div class="flex flex-col md:flex-row md:items-center justify-between bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">
                                    {{ $detail->product->name ?? 'Product has been deleted' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Quantity: {{ $detail->quantity }}
                                </p>
                            </div>
                            <div class="mt-2 md:mt-0 text-right">
                                <p class="text-sm text-gray-600">
                                    Unit Price: {{ number_format($detail->price) }}₫
                                </p>
                                <p class="text-sm font-medium text-gray-900">
                                    Total: {{ number_format($detail->price * $detail->quantity) }}₫
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4 mt-6">
            @if ($order->status === 'completed')
                <!-- Disabled Button -->
                <button type="button"
                    class="bg-gray-400 text-black font-semibold py-2 px-6 rounded-lg shadow cursor-not-allowed"
                    disabled>
                    Delivered
                </button>
            @elseif ($order->status === 'cancelled')
                <!-- Disabled Button -->
                <button type="button"
                    class="bg-gray-400 text-black font-semibold py-2 px-6 rounded-lg shadow cursor-not-allowed"
                    disabled>
                    Canceled
                </button>
            @elseif ($order->status === 'processing')
                <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
                        Shipped
                </button>
            @else
                <!-- Cancel Order Button -->
                <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
                        Cancel Order
                    </button>
                </form>
                
                <!-- Confirm Order Button -->
                <form method="POST" action="{{ route('admin.order.confirm', $order->id) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
                        Confirm Order
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
