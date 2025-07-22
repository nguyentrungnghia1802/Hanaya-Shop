{{-- resources/views/page/productDetail.blade.php --}}
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

        <!-- Breadcrumb Navigation -->
        <nav class="flex text-sm mb-4 text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('user.products.index') }}" class="hover:text-gray-900">Products</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <!-- Tiêu đề -->
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 text-center">Product Details</h1>

        <!-- Success notification -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow text-center">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Product detailed information -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Product images -->
                <div class="space-y-4">
                    <div class="aspect-square w-full bg-gray-100 rounded-lg overflow-hidden">
                        <img src="{{ $product->image_url ? asset('images/products/' . $product->image_url) : asset('images/no-image.png') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    <!-- Thumbnails could go here if needed -->
                </div>
                
                <!-- Product information -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
                        @if($product->category)
                        <p class="text-pink-600 font-medium mb-2">{{ $product->category->name }}</p>
                        @endif
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>{{ $product->view_count ?? 0 }} views
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-box mr-1"></i>{{ $product->stock_quantity }} in stock
                            </span>
                        </div>
                    </div>

                    <div class="prose max-w-none">
                        <p class="text-gray-700 leading-relaxed">{{ $product->descriptions }}</p>
                    </div>

                    <!-- Giá sản phẩm -->
                    <div class="border-t border-b border-gray-200 py-4">
                        @if($product->discount_percent > 0)
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3">
                                <span class="text-3xl sm:text-4xl font-bold text-red-600">
                                    {{ number_format($product->discounted_price, 0, ',', '.') }} USD
                                </span>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm font-medium">
                                    -{{ $product->discount_percent }}%
                                </span>
                            </div>
                            <p class="text-lg text-gray-500 line-through">
                                {{ number_format($product->price, 0, ',', '.') }} USD
                            </p>
                        </div>
                        @else
                        <p class="text-3xl sm:text-4xl font-bold text-pink-600">
                            {{ number_format($product->price, 0, ',', '.') }} USD
                        </p>
                        @endif
                    </div>

                    <!-- Chọn số lượng -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label for="quantity" class="block mb-3 font-medium text-gray-700">Chọn số lượng:</label>
                        <div class="flex items-center space-x-3 mb-4">
                            <button id="decrease" class="flex items-center justify-center w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full transition-colors">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input id="quantity" type="number" value="1" min="1" max="{{ $product->stock_quantity }}" 
                                   class="w-20 text-center border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-pink-500" />
                            <button id="increase" class="flex items-center justify-center w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full transition-colors">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                            <span class="text-sm text-gray-600">/ {{ $product->stock_quantity }} có sẵn</span>
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="space-y-3">
                        <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="quantity" id="form-quantity" value="1">
                            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </form>

                        <form action="{{ route('cart.buyNow') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
                            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center">
                                <i class="fas fa-bolt mr-2"></i>Buy Now
                            </button>
                        </form>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-shipping-fast mr-2 text-green-500"></i>
                                Miễn phí vận chuyển
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-undo mr-2 text-blue-500"></i>
                                Đổi trả trong 7 ngày
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-shield-alt mr-2 text-purple-500"></i>
                                Bảo hành chính hãng
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-headset mr-2 text-pink-500"></i>
                                Hỗ trợ 24/7
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar products -->
        <div class="mt-12 sm:mt-16">
            <h3 class="text-xl sm:text-2xl font-bold mb-6 text-center">Similar Products</h3>
            <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($relatedProducts as $item)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-all duration-300 transform hover:scale-105">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : asset('images/no-image.png') }}" 
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" 
                                 alt="{{ $item->name }}"
                                 loading="lazy">
                        </div>
                        <div class="p-3 sm:p-4">
                            <h4 class="text-sm sm:text-base font-semibold text-gray-800 line-clamp-2 mb-2">{{ $item->name }}</h4>
                            @if($item->discount_percent > 0)
                            <div class="space-y-1">
                                <p class="text-pink-600 font-bold text-lg">
                                    {{ number_format($item->discounted_price, 0, ',', '.') }} USD
                                </p>
                                <p class="text-sm text-gray-500 line-through">
                                    {{ number_format($item->price, 0, ',', '.') }} USD
                                </p>
                            </div>
                            @else
                            <p class="text-pink-600 font-bold text-lg">{{ number_format($item->price, 0, ',', '.') }} USD</p>
                            @endif
                            <a href="{{ route('user.products.show', $item->id) }}" 
                               class="inline-block mt-3 w-full text-center bg-pink-500 hover:bg-pink-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        const quantityInput = document.getElementById('quantity');
        const formQuantityInput = document.getElementById('form-quantity');
        const buyNowQuantityInput = document.getElementById('buy-now-quantity');
        const maxStock = {{ $product->stock_quantity }};

        // Cập nhật số lượng trong tất cả forms
        function updateQuantity() {
            const currentValue = quantityInput.value;
            formQuantityInput.value = currentValue;
            buyNowQuantityInput.value = currentValue;
        }

        // Tăng số lượng
        document.getElementById('increase').addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
                updateQuantity();
            }
        });

        // Giảm số lượng
        document.getElementById('decrease').addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateQuantity();
            }
        });

        // Xử lý thay đổi trực tiếp trong input
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                value = 1;
            } else if (value > maxStock) {
                value = maxStock;
            }
            this.value = value;
            updateQuantity();
        });

        // Đồng bộ số lượng khi submit form
        document.getElementById('add-to-cart-form').addEventListener('submit', function () {
            updateQuantity();
        });
    </script>

</x-app-layout>
