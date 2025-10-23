{{-- filepath: resources/views/cart/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <h1 class="text-3xl font-bold">🛒 Shopping Cart</h1>
                    <p class="text-pink-100 mt-2">Review your items and proceed to checkout</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert />

            @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
            @endif
            
            @if(count($cart) > 0)
                <!-- Cart Summary -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-6 0v-4m0-4v4m0-4h4m-4 0a2 2 0 00-2 2"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Cart Summary</h3>
                                <p class="text-gray-600">{{ count($cart) }} item{{ count($cart) > 1 ? 's' : '' }} in your cart</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500" title="Select all items">
                                <label for="select-all" class="text-sm font-medium text-gray-700">Select All</label>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="checkout-form" method="POST" action="{{ route('checkout.preview') }}">
                    @csrf
                    <input type="hidden" name="selected_items_json" id="selected_items_json">
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-8">
                        @foreach($cart as $id => $item)
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex items-start space-x-4">
                                    <!-- Checkbox -->
                                    <div class="flex items-center pt-2">
                                        <input type="checkbox" 
                                               name="cart_ids[]" 
                                               value="{{ $id }}" 
                                               class="cart-checkbox w-4 h-4 text-pink-600 bg-gray-100 border-gray-300 rounded focus:ring-pink-500" 
                                               data-price="{{ $item['price'] * $item['quantity'] }}"
                                               data-id="{{ $id }}"
                                               data-product-id="{{ $item['product_id'] }}">
                                    </div>

                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img src="{{ $item['image_url'] && file_exists(public_path('images/products/' . $item['image_url']))
                                            ? asset('images/products/' . $item['image_url'])
                                            : asset('images/no-image.png') }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item['name'] }}</h3>
                                                <p class="text-2xl font-bold text-pink-600">${{ number_format($item['price'], 0, ',', '.') }}</p>
                                                
                                                <!-- Mobile quantity controls -->
                                                <div class="flex items-center space-x-3 mt-4 sm:hidden">
                                                    <span class="text-sm font-medium text-gray-700">Quantity:</span>
                                                    <div class="flex items-center space-x-2">
                                                        <button type="button" class="btn-decrease w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center transition-colors" data-id="{{ $id }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                            </svg>
                                                        </button>
                                                        <input type="number" 
                                                               min="1" 
                                                               class="quantity-input w-16 text-center border border-gray-300 rounded-lg py-1 focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                                                               value="{{ $item['quantity'] }}" 
                                                               data-id="{{ $id }}" 
                                                               data-price="{{ $item['price'] }}"
                                                               data-total="{{ $item['price'] * $item['quantity'] }}"
                                                               data-stock="{{ $item['product_quantity'] }}">
                                                        <button type="button" class="btn-increase w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center transition-colors" data-id="{{ $id }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Desktop Controls -->
                                            <div class="hidden sm:flex items-center space-x-6">
                                                <!-- Quantity Controls -->
                                                <div class="flex items-center space-x-3">
                                                    <span class="text-sm font-medium text-gray-700">Qty:</span>
                                                    <div class="flex items-center space-x-2">

                                                        <input type="number" 
                                                               min="1" 
                                                               class="quantity-input w-16 text-center border border-gray-300 rounded-lg py-2 focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                                                               value="{{ $item['quantity'] }}" 
                                                               data-id="{{ $id }}" 
                                                               data-price="{{ $item['price'] }}"
                                                               data-total="{{ $item['price'] * $item['quantity'] }}"
                                                               data-stock="{{ $item['product_quantity'] }}">
                                                    </div>
                                                </div>

                                                <!-- Subtotal -->
                                                <div class="text-right">
                                                    <p class="text-sm text-gray-600">Subtotal</p>
                                                    <p class="item-total text-xl font-bold text-gray-900" data-id="{{ $id }}">
                                                        ${{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <!-- Remove Button -->
                                                <a href="{{ route('cart.remove', $id) }}" 
                                                   class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Mobile Subtotal and Remove -->
                                        <div class="flex items-center justify-between mt-4 sm:hidden">
                                            <div class="item-total text-xl font-bold text-gray-900" data-id="{{ $id }}">
                                                ${{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            </div>
                                            <a href="{{ route('cart.remove', $id) }}" 
                                               class="flex items-center space-x-1 text-red-600 hover:text-red-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span class="text-sm">Remove</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Cart Total & Checkout -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Order Summary</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-xl font-semibold text-gray-900">Total Amount:</span>
                                <span id="totalPrice" class="text-3xl font-bold text-pink-600">$0</span>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('user.products.index') }}" 
                                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-3 px-6 rounded-lg font-semibold transition-colors duration-200">
                                    Continue Shopping
                                </a>
                                <button type="submit" 
                                        class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white text-center py-3 px-6 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                                    Proceed to Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <!-- Empty Cart State -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-6 0v-4m0-4v4m0-4h4m-4 0a2 2 0 00-2 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500 mb-6">Add some beautiful flowers and gifts to get started!</p>
                    <a href="{{ route('user.products.index') }}" 
                       class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script src="{{ asset('js/cart.js') }}" defer></script>
@endpush
