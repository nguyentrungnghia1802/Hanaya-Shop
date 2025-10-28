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
                    <p><span class="font-medium">Customer:</span> {{ $order->user->name }}</p>
                    <p><span class="font-medium">Email:</span> {{ $order->user->email }}</p>
                    <p>
                        <span class="font-medium">Address:</span>
                        <span class="block text-sm text-gray-600">{{ $order->address->phone_number }}</span>
                        <span class="block text-sm text-gray-600">
                            {{ $order->address->address }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Order Information</h3>
                <div class="space-y-2 text-gray-700">
                    <p><span class="font-medium">Order Date:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-medium">Total Amount:</span> {{ number_format($order->total_price) }}₫</p>
                    <p>
                        <span class="font-medium">Status:</span> 
                        <span class="px-2 py-1 rounded text-sm font-medium inline-block
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
                
                <!-- Payment Information -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-3">Payment Information</h4>
                    @if($payment && count($payment) > 0)
                        @php $paymentInfo = $payment[0]; @endphp
                        <div class="space-y-2 text-gray-700">
                            <p>
                                <span class="font-medium">Payment Method:</span> 
                                {{ ucfirst(str_replace('_', ' ', $paymentInfo->payment_method)) }}
                            </p>
                            <p>
                                <span class="font-medium">Payment Status:</span> 
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    {{ $paymentInfo->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($paymentInfo->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($paymentInfo->payment_status) }}
                                </span>
                            </p>
                            @if($paymentInfo->transaction_id)
                                <p><span class="font-medium">Transaction ID:</span> {{ $paymentInfo->transaction_id }}</p>
                            @endif
                            <p><span class="font-medium">Payment Date:</span> {{ $paymentInfo->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No payment information available</p>
                    @endif
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

        <!-- Message -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Message</h3>
            <div class="space-y-4">
                <p >
                    {{ $order->message ?? 'No message provided' }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4 mt-6">
            {{-- Cancel --}}
            @if ($order->status === 'pending')
                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="inline-block px-5 py-2 bg-red-500 text-white text-base font-semibold rounded-lg hover:bg-gray-600 transition">
                        Cancel
                    </button>
                </form>
            @else
                <button type="button"
                        class="inline-block px-5 py-2 bg-gray-300 text-white text-base font-semibold rounded-lg cursor-not-allowed"
                        disabled>
                    Cancel
                </button>
            @endif

            {{-- Confirm --}}
            @if ($order->status === 'pending')
                <form action="{{ route('admin.order.confirm', $order->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="inline-block px-5 py-2 bg-gray-500 text-white text-base font-semibold rounded-lg hover:bg-gray-600 transition">
                        Confirm
                    </button>
                </form>
            @else
                <button type="button"
                        class="inline-block px-5 py-2 bg-gray-300 text-white text-base font-semibold rounded-lg cursor-not-allowed"
                        disabled>
                    Confirm
                </button>
            @endif

            {{-- Shipped --}}
            @if ($order->status === 'processing')
                <form action="{{ route('admin.order.shipped', $order->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="inline-block px-5 py-2 bg-green-500 text-white text-base font-semibold rounded-lg hover:bg-gray-600 transition">
                        Shipped
                    </button>
                </form>
            @else
                <button type="button"
                        class="inline-block px-5 py-2 bg-gray-300 text-white text-base font-semibold rounded-lg cursor-not-allowed"
                        disabled>
                    Shipped
                </button>
            @endif
            @php
                $matchedPayment = $payment->first();
            @endphp
            {{-- Paid --}}
            @if (($order->status === 'processing' || $order->status === 'shipped') && $matchedPayment && $matchedPayment->payment_status === 'pending')
                <form action="{{ route('admin.order.paid', $order->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="inline-block px-5 py-2 bg-pink-500 text-white text-base font-semibold rounded-lg hover:bg-pink-600 transition">
                        Mark as Paid
                    </button>
                </form>
            @else
                <button type="button"
                        class="inline-block px-5 py-2 bg-gray-300 text-white text-base font-semibold rounded-lg cursor-not-allowed"
                        disabled>
                        @if($matchedPayment && $matchedPayment->payment_status === 'completed')
                            Already Paid
                        @else
                            Mark as Paid
                        @endif
                </button>
            @endif
            
            {{-- Cancel --}}
            @if ($order->status !== 'completed' && $order->status !== 'cancelled')
                <form action="{{ route('admin.order.cancel', $order->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to cancel this order?')"
                            class="inline-block px-5 py-2 bg-red-500 text-white text-base font-semibold rounded-lg hover:bg-red-600 transition">
                        Cancel Order
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
