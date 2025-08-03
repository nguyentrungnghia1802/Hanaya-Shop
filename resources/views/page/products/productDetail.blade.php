{{-- resources/views/page/products/productDetail.blade.php --}}
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

        <!-- Thông báo lỗi -->
        <x-alert />

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
                </div>
                <!-- Product information -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
                        @if ($product->category)
                            <p class="text-pink-600 font-medium mb-2">{{ $product->category->name }}</p>
                        @endif
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>{{ $product->view_count ?? 0 }} views
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-box mr-1"></i>{{ $product->stock_quantity }} in stock
                            </span>
                            <span class="flex items-center">
                                <x-star-rating :rating="$averageRating" size="sm" />
                                <span class="ml-1">({{ $totalReviews }})</span>
                            </span>
                        </div>
                    </div>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 leading-relaxed">{{ $product->descriptions }}</p>
                    </div>
                    <!-- Giá sản phẩm -->
                    <div class="border-t border-b border-gray-200 py-4">
                        @if ($product->discount_percent > 0)
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
                            <button id="decrease"
                                class="flex items-center justify-center w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full transition-colors">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input id="quantity" type="number" value="1" min="1"
                                max="{{ $product->stock_quantity }}" data-max-stock="{{ $product->stock_quantity }}"
                                class="w-20 text-center border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-pink-500" />
                            <button id="increase"
                                class="flex items-center justify-center w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full transition-colors">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                            <span class="text-sm text-gray-600">/ {{ $product->stock_quantity }} available</span>
                        </div>
                    </div>
                    <!-- Action buttons -->
                    <div class="space-y-3">
                        @auth
                            @if ($product->stock_quantity > 0)
                                <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="quantity" id="form-quantity" value="1">
                                    <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center">
                                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                                    </button>
                                </form>
                                <form action="{{ route('cart.buyNow', $product->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" id="buy-now-quantity" value="1">
                                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center">
                                        <i class="fas fa-bolt mr-2"></i>Buy Now
                                    </button>
                                </form>
                            @else
                                <button 
                                    type="button" 
                                    class="w-full bg-pink-400 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center cursor-not-allowed opacity-60" 
                                    disabled>
                                    
                                    <i class="fas fa-shopping-cart mr-2"></i>Out of stock
                                </button>
                                <button 
                                    type="button" 
                                    class="w-full bg-pink-400 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center cursor-not-allowed opacity-60" 
                                    disabled>
                                    
                                    <i class="fas fa-shopping-cart mr-2"></i>Out of stock
                                </button>

                            @endif



                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="fas fa-lock text-yellow-500 mr-2"></i>
                                    <span class="text-yellow-700 font-medium">Sign in required</span>
                                </div>
                                <p class="text-sm text-yellow-600 mb-3">Please sign in to add products to cart and make
                                    purchases</p>
                                <div class="space-y-2">
                                    <a href="{{ route('login') }}"
                                        class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
                                        <i class="fas fa-user-plus mr-2"></i>Create Account
                                    </a>
                                </div>
                            </div>
                        @endauth
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

        <!-- Product Reviews Section -->
        <div class="mt-12 sm:mt-16">
            <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Customer Reviews</h3>
                    <div class="flex items-center space-x-2">
                        <x-star-rating :rating="$averageRating" size="lg" show-text />
                        <span class="text-sm text-gray-600">({{ $totalReviews }} reviews)</span>
                    </div>
                </div>

                @if ($reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach ($reviews as $review)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                                            <span class="text-pink-600 font-semibold text-sm">
                                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $review->user->name }}</h4>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <x-star-rating :rating="$review->rating" size="sm" />
                                                    <span class="text-xs text-gray-500">
                                                        {{ $review->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($review->comment)
                                            <div
                                                class="text-gray-700 text-sm leading-relaxed prose prose-sm max-w-none">
                                                {!! $review->comment !!}
                                            </div>
                                        @endif
                                        <div class="flex justify-center md:justify-start">
                                            <div class="w-full max-w-sm">
                                                <img src="{{ asset('images/reviews/' . ($review->image_path ?? 'base.jpg')) }}"
                                                    alt="{{ $review->user->name }}"
                                                    class="w-full h-64 object-cover rounded-lg shadow-md border">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($reviews->hasPages())
                        <div class="mt-6 flex justify-center">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-gray-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h4>
                        <p class="text-gray-600">Be the first to review this product!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Similar products -->
        <div class="mt-12 sm:mt-16">
            <h3 class="text-xl sm:text-2xl font-bold mb-6 text-center">Similar Products</h3>
            <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($relatedProducts as $item)
                    <div
                        class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-all duration-300 transform hover:scale-105 flex flex-col h-full">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            <img src="{{ $item->image_url ? asset('images/products/' . $item->image_url) : asset('images/no-image.png') }}"
                                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                alt="{{ $item->name }}" loading="lazy">
                        </div>
                        <div class="p-3 sm:p-4 flex flex-col flex-1">
                            <!-- Fixed height title area -->
                            <div class="h-12 mb-2">
                                <h4 class="text-sm sm:text-base font-semibold text-gray-800 line-clamp-2">
                                    {{ $item->name }}</h4>
                            </div>

                            <!-- Fixed height price area -->
                            <div class="h-12 mb-3">
                                @if ($item->discount_percent > 0)
                                    <div class="space-y-1">
                                        <p class="text-pink-600 font-bold text-lg">
                                            {{ number_format($item->discounted_price, 0, ',', '.') }} USD
                                        </p>
                                        <p class="text-sm text-gray-500 line-through">
                                            {{ number_format($item->price, 0, ',', '.') }} USD
                                        </p>
                                    </div>
                                @else
                                    <p class="text-pink-600 font-bold text-lg">
                                        {{ number_format($item->price, 0, ',', '.') }} USD</p>
                                @endif
                            </div>

                            <!-- Button pushes to bottom -->
                            <div class="mt-auto">
                                <a href="{{ route('user.products.show', $item->id) }}"
                                    class="block w-full text-center bg-pink-500 hover:bg-pink-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/product-detail.js') }}" defer></script>
    @endpush

</x-app-layout>
