<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('order.index') }}" class="text-white hover:text-pink-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold">📋 Order Details</h1>
                            <p class="text-pink-100 mt-2">Order #{{ $order->id }} -
                                {{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Order Status Progress -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Order Status</h3>

                <div class="flex items-center justify-between">
                    @php
                        $statuses = [
                            'pending' => ['label' => 'Processing', 'step' => 1],
                            'confirmed' => ['label' => 'Confirmed', 'step' => 2],
                            'shipped' => ['label' => 'Delivered', 'step' => 3],
                        ];
                        $currentStep = $statuses[$order->status]['step'] ?? 0;
                    @endphp

                    <!-- Step 1: Processing -->
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 1 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }} relative">
                            @if ($currentStep >= 1)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <span>1</span>
                            @endif
                        </div>
                        <p
                            class="text-sm font-medium mt-2 text-center {{ $currentStep >= 1 ? 'text-blue-600' : 'text-gray-500' }}">
                            Processing</p>
                    </div>

                    <!-- Connection Line 1-2 -->
                    <div class="flex-1 h-0.5 {{ $currentStep >= 2 ? 'bg-blue-500' : 'bg-gray-200' }} mx-4"></div>

                    <!-- Step 2: Confirmed -->
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 2 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if ($currentStep >= 2)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <span>2</span>
                            @endif
                        </div>
                        <p
                            class="text-sm font-medium mt-2 text-center {{ $currentStep >= 2 ? 'text-green-600' : 'text-gray-500' }}">
                            Confirmed</p>
                    </div>

                    <!-- Connection Line 2-3 -->
                    <div class="flex-1 h-0.5 {{ $currentStep >= 3 ? 'bg-green-500' : 'bg-gray-200' }} mx-4"></div>

                    <!-- Step 3: Shipped -->
                    <div class="flex flex-col items-center flex-1">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 3 ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if ($currentStep >= 3)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z">
                                    </path>
                                    <path
                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707L16 7.586A1 1 0 0015.414 7H14z">
                                    </path>
                                </svg>
                            @else
                                <span>3</span>
                            @endif
                        </div>
                        <p
                            class="text-sm font-medium mt-2 text-center {{ $currentStep >= 3 ? 'text-purple-600' : 'text-gray-500' }}">
                            Delivered</p>
                    </div>
                </div>

                @if ($order->status === 'canceled')
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Order Canceled</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>This order has been canceled and any payment has been refunded.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Details -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Order Information
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-semibold text-gray-900">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Order Date:</span>
                            <span
                                class="font-semibold text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-semibold text-gray-900">${{ number_format($order->total_price) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-gray-600">Status:</span>
                            @if ($order->status === 'pending')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Processing
                                </span>
                            @elseif($order->status === 'confirmed')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Confirmed
                                </span>
                            @elseif($order->status === 'shipped')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Delivered
                                </span>
                            @elseif($order->status === 'canceled')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Canceled
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Customer Information
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Full Name:</span>
                            <span class="font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-semibold text-gray-900">{{ $order->user->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">Phone Number:</span>
                            <span class="font-semibold text-gray-900">{{ $order->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-start py-3">
                            <span class="text-gray-600">Address:</span>
                            <span
                                class="font-semibold text-gray-900 text-right max-w-xs">{{ $order->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Product List
                    </h3>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($order->orderDetail as $detail)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-6">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <a href="{{ route('user.products.show', $detail->product_id) }}"
                                        class="block group">
                                        <div
                                            class="w-20 h-20 bg-gradient-to-br from-pink-100 to-purple-100 rounded-xl flex items-center justify-center">
                                            @if ($detail->product && $detail->product->image_url)
                                                <img src="{{ asset('images/products/' . $detail->product->image_url) }}"
                                                    alt="{{ $detail->product->name }}"
                                                    class="w-16 h-16 object-cover rounded-lg group-hover:scale-105 transition-transform duration-200">
                                            @else
                                                <svg class="w-8 h-8 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>
                                    </a>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('user.products.show', $detail->product_id) }}"
                                        class="block group">
                                        <h4
                                            class="text-lg font-semibold text-gray-900 mb-1 group-hover:text-pink-600 transition-colors">
                                            {{ $detail->product->name ?? 'Product Not Found' }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">
                                            {{ $detail->product->description ?? 'No description available' }}</p>
                                    </a>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span>Quantity: {{ $detail->quantity }}</span>
                                        <span>•</span>
                                        <span>Unit Price: ${{ number_format($detail->price) }}</span>
                                    </div>
                                </div>

                                <!-- Price and Actions -->
                                <div class="flex-shrink-0">
                                    <div class="text-right space-y-3">
                                        <div>
                                            <p class="text-lg font-bold text-gray-900">
                                                ${{ number_format($detail->price * $detail->quantity) }}</p>
                                            <p class="text-sm text-gray-500">Total</p>
                                        </div>
                                        <!-- Review Action -->
                                        @if ($detail->can_review)
                                            <a href="{{ route('review.create', ['product_id' => $detail->product_id, 'order_id' => $order->id]) }}"
                                                class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-lg transition-colors duration-200 mt-2">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                                Write Review
                                            </a>
                                        @elseif($detail->has_review)
                                            <div class="flex items-center text-xs text-green-600 mt-2">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Reviewed</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Total -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="space-y-3">
                        <!-- Subtotal -->
                        <div class="flex justify-between items-center">
                            <span class="text-base text-gray-700">Subtotal:</span>
                            <span
                                class="text-base font-medium text-gray-900">${{ number_format($order->total_price - config('constants.checkout.shipping_fee', 8)) }}</span>
                        </div>

                        <!-- Shipping Fee -->
                        <div class="flex justify-between items-center">
                            <span class="text-base text-gray-700">Shipping Fee:</span>
                            <span
                                class="text-base font-medium text-gray-900">${{ number_format(config('constants.checkout.shipping_fee', 8)) }}</span>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-300 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Order Total:</span>
                                <span
                                    class="text-2xl font-bold text-purple-600">${{ number_format($order->total_price) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                @if ($order->status === 'shipped')
                    <button type="button" disabled
                        class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-500 font-semibold rounded-lg cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Order Delivered
                    </button>
                @elseif ($order->status === 'canceled')
                    <button type="button" disabled
                        class="inline-flex items-center px-6 py-3 bg-red-100 text-red-800 font-semibold rounded-lg cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Order Canceled
                    </button>
                @else
                    <a href="{{ route('order.cancel', $order->id) }}" data-confirm-cancel
                        class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Order
                    </a>
                @endif

                <a href="{{ route('user.products.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Continue Shopping
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script src="{{ asset('js/order.js') }}" defer></script>
@endpush
