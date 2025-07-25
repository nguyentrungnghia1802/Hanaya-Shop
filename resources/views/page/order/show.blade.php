<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-6">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('order.index') }}" class="text-white hover:text-pink-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold">📦 Chi tiết đơn hàng #{{ $order->id }}</h1>
                            <p class="text-pink-100 mt-2">Thông tin chi tiết về đơn hàng của bạn</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Order Status Progress -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Trạng thái đơn hàng</h3>
                
                <div class="flex items-center justify-between">
                    @php
                        $statuses = [
                            'pending' => ['label' => 'Đang xử lý', 'step' => 1],
                            'confirmed' => ['label' => 'Đã xác nhận', 'step' => 2],
                            'shipped' => ['label' => 'Đã giao', 'step' => 3],
                        ];
                        $currentStep = $statuses[$order->status]['step'] ?? 0;
                    @endphp

                    <!-- Step 1: Processing -->
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 1 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }} relative">
                            @if($currentStep >= 1)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <span>1</span>
                            @endif
                            @if($currentStep > 1)
                                <div class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 w-full h-0.5 bg-blue-500"></div>
                            @endif
                        </div>
                        <p class="text-sm font-medium mt-2 text-center {{ $currentStep >= 1 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500' }}">Đang xử lý</p>
                    </div>

                    <!-- Connection Line 1-2 -->
                    <div class="flex-1 h-0.5 {{ $currentStep >= 2 ? 'bg-blue-500' : 'bg-gray-200' }} mx-4"></div>

                    <!-- Step 2: Confirmed -->
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 2 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if($currentStep >= 2)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <span>2</span>
                            @endif
                        </div>
                        <p class="text-sm font-medium mt-2 text-center {{ $currentStep >= 2 ? 'text-green-600 dark:text-green-400' : 'text-gray-500' }}">Đã xác nhận</p>
                    </div>

                    <!-- Connection Line 2-3 -->
                    <div class="flex-1 h-0.5 {{ $currentStep >= 3 ? 'bg-green-500' : 'bg-gray-200' }} mx-4"></div>

                    <!-- Step 3: Shipped -->
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 3 ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if($currentStep >= 3)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707L16 7.586A1 1 0 0015.414 7H14z"></path>
                                </svg>
                            @else
                                <span>3</span>
                            @endif
                        </div>
                        <p class="text-sm font-medium mt-2 text-center {{ $currentStep >= 3 ? 'text-purple-600 dark:text-purple-400' : 'text-gray-500' }}">Đã giao</p>
                    </div>
                </div>

                @if($order->status === 'canceled')
                    <div class="mt-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Đơn hàng đã bị hủy</h3>
                                <p class="text-sm text-red-700 dark:text-red-300 mt-1">Đơn hàng này đã được hủy bỏ.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Details -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Thông tin đơn hàng
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Mã đơn hàng:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Ngày đặt:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Tổng sản phẩm:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $order->orderDetail->count() }} sản phẩm</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-gray-600 dark:text-gray-400">Tổng tiền:</span>
                            <span class="text-2xl font-bold text-gradient bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">{{ number_format($order->total_price) }}₫</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Thông tin khách hàng
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Họ tên:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $order->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Email:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $order->user->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Số điện thoại:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $order->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-start py-3">
                            <span class="text-gray-600 dark:text-gray-400">Địa chỉ:</span>
                            <span class="font-semibold text-gray-900 dark:text-white text-right max-w-xs">{{ $order->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Danh sách sản phẩm
                    </h3>
                </div>

                <div class="divide-y dark:divide-gray-700">
                    @foreach ($order->orderDetail as $detail)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <a href="{{ route('product.show', $detail->product->id) }}" 
                               class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-6 group">
                                
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <div class="w-20 h-20 bg-gradient-to-br from-pink-100 to-purple-100 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                                        @if($detail->product && $detail->product->images && $detail->product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $detail->product->images->first()->image_path) }}" 
                                                 alt="{{ $detail->product->name }}"
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                        {{ $detail->product->name ?? 'Sản phẩm đã bị xoá' }}
                                    </h4>
                                    @if($detail->product)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $detail->product->description }}</p>
                                    @endif
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Số lượng: {{ $detail->quantity }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Price Info -->
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Đơn giá</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($detail->price) }}₫</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Thành tiền</p>
                                    <p class="text-xl font-bold text-gradient bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">
                                        {{ number_format($detail->price * $detail->quantity) }}₫
                                    </p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Order Total -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Tổng cộng:</span>
                        <span class="text-2xl font-bold text-gradient bg-gradient-to-r from-pink-500 to-purple-600 bg-clip-text text-transparent">
                            {{ number_format($order->total_price) }}₫
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                @if ($order->status === 'shipped')
                    <button type="button"
                        class="inline-flex items-center px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg shadow cursor-not-allowed opacity-75">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Đã giao
                    </button>
                @elseif ($order->status === 'canceled')
                    <button type="button"
                        class="inline-flex items-center px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg shadow cursor-not-allowed opacity-75">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Đã Hủy
                    </button>
                @else
                    <a href="{{ route('order.cancel', $order->id) }}"
                       class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                       onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Hủy đơn hàng
                    </a>
                @endif

                <a href="{{ route('user.products.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Tiếp tục mua sắm
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
