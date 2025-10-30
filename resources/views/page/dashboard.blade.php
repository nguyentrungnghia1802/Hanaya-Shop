<x-app-layout>
    <!-- Flash Messages -->
    <x-alert />

    <div class="min-h-screen bg-gray-50 transition-colors duration-300">
        <!-- Banner Section -->
        <section class="w-full">
            <x-home.banner-slider :banners="$banners" />
        </section>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">

            <!-- Categories Section -->
            <section>
                <x-home.categories :categories="$categories" />
            </section>

            <!-- Latest Posts Section -->
            <section>
                <x-home.latest-posts :posts="$latestPosts" />
            </section>

            <!-- Products by Category Section -->
            @if (isset($latestByCategory) && count($latestByCategory) > 0)
                <section>
                    <x-category-products :categoryData="$latestByCategory" title="Latest Products by Category" />
                </section>
            @endif

            <!-- Best Seller Section -->
            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-colors duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div>
                    </div>
                    <a href="{{ route('user.products.index', ['sort' => 'bestseller']) }}"
                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium flex items-center transition-colors duration-300">
                    </a>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center mb-4">
                    <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                        </path>
                    </svg>
                    Best Seller Products
                </h3>


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($topSeller as $product)
                        <div
                            class="bg-gray-50 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 group flex flex-col h-full">
                            <div class="relative">
                                <div class="aspect-square w-full bg-white overflow-hidden">
                                    <img src="{{ asset('images/products/' . ($product->image_url ?? 'default-product.jpg')) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                @if ($product->discount_percent > 0)
                                    <div
                                        class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-lg text-sm font-bold">
                                        -{{ $product->discount_percent }}%
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2 bg-yellow-500 text-white p-1 rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                </div>

                                <!-- Quick Action Overlay -->
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="flex gap-2">
                                        <a href="{{ route('user.products.show', $product->id) }}"
                                            class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors"
                                            title="Quick View">
                                            <i class="fas fa-eye w-4 h-4"></i>
                                        </a>
                                        @if ($product->stock_quantity > 0)
                                            <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}"
                                                method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="quantity" id="form-quantity" value="1">
                                                <button type="submit"
                                                    class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center"
                                                    title="Add to Cart">
                                                    <i class="fas fa-shopping-cart mr-2"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button disabled
                                                class="w-full bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg shadow-lg cursor-not-allowed flex items-center justify-center"
                                                title="Out of Stock">
                                                <i class="fas fa-ban mr-2"></i>Out of Stock
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 flex flex-col flex-1">
                                <!-- Fixed height title area -->
                                <div class="h-14 mb-2">
                                    <h4 class="font-semibold text-lg line-clamp-2">{{ $product->name }}</h4>
                                </div>

                                <!-- Fixed height price area -->
                                <div class="h-10 mb-2">
                                    @if ($product->discount_percent > 0)
                                        <div class="flex items-center">
                                            <span class="text-lg font-bold text-red-600">
                                                ${{ number_format($product->discounted_price ?? $product->price, 2) }}
                                            </span>
                                            <span class="text-sm text-gray-500 line-through ml-2">
                                                ${{ number_format($product->price, 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-lg font-bold text-gray-900 block">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                    <span><i class="fas fa-shopping-cart mr-1"></i>{{ $product->total_sold ?? 0 }}
                                        sold</span>
                                    <span><i class="fas fa-eye mr-1"></i>{{ $product->view_count ?? 0 }}</span>
                                </div>

                                <!-- Button pushes to bottom -->
                                <div class="mt-auto">
                                    <a href="{{ route('user.products.show', $product->id) }}"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white text-center py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                                        <i class="fas fa-eye mr-2"></i>View Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Sale Products & Latest Products Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Sale Products -->
                <section class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                            Sale Products
                        </h3>
                        <a href="{{ route('user.products.index', ['sort' => 'sale']) }}"
                            class="text-orange-600 hover:text-orange-800 font-medium flex items-center">
                            View All
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($onSale->take(4) as $product)
                            <div
                                class="bg-gray-50 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 group flex flex-col h-full">
                                <div class="relative">
                                    <div class="aspect-w-4 aspect-h-3 w-full bg-white overflow-hidden">
                                        <img src="{{ asset('images/products/' . ($product->image_url ?? 'default-product.jpg')) }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    @if ($product->discount_percent > 0)
                                        <div
                                            class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                                            -{{ $product->discount_percent }}%
                                        </div>
                                    @endif

                                    <!-- Quick Action Overlay -->
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex gap-2">
                                            <a href="{{ route('user.products.show', $product->id) }}"
                                                class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors"
                                                title="Quick View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($product->stock_quantity > 0)
                                                <form id="add-to-cart-form"
                                                    action="{{ route('cart.add', $product->id) }}" method="POST"
                                                    class="w-full">
                                                    @csrf
                                                    <input type="hidden" name="quantity" id="form-quantity"
                                                        value="1">
                                                    <button type="submit"
                                                        class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center"
                                                        title="Add to Cart">
                                                        <i class="fas fa-shopping-cart mr-2"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button disabled
                                                    class="w-full bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg shadow-lg cursor-not-allowed flex items-center justify-center"
                                                    title="Out of Stock">
                                                    <i class="fas fa-ban mr-2"></i>Out of Stock
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 flex flex-col flex-1">
                                    <!-- Fixed height title area -->
                                    <div class="h-10 mb-2">
                                        <h4 class="font-semibold text-sm line-clamp-2">{{ $product->name }}</h4>
                                    </div>

                                    <!-- Fixed height price area -->
                                    <div class="h-8 mb-2">
                                        @if ($product->discount_percent > 0)
                                            <div class="flex items-center">
                                                <span class="text-sm font-bold text-red-600">
                                                    ${{ number_format($product->discounted_price ?? $product->price, 2) }}
                                                </span>
                                                <span class="text-xs text-gray-500 line-through ml-2">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-sm font-bold text-gray-900 block">
                                                ${{ number_format($product->price, 2) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Stats and button at bottom -->
                                    <div class="mt-auto">
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span><i
                                                    class="fas fa-eye mr-1"></i>{{ $product->view_count ?? 0 }}</span>
                                            <span><i class="fas fa-box mr-1"></i>{{ $product->stock_quantity }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Latest Products -->
                <section class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Latest Products
                        </h3>
                        <a href="{{ route('user.products.index', ['sort' => 'latest']) }}"
                            class="text-green-600 hover:text-green-800 font-medium flex items-center">
                            View All
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($latest->take(4) as $product)
                            <div
                                class="bg-gray-50 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 group flex flex-col h-full">
                                <div class="relative">
                                    <div class="aspect-w-4 aspect-h-3 w-full bg-white overflow-hidden">
                                        <img src="{{ asset('images/products/' . ($product->image_url ?? 'default-product.jpg')) }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    @if ($product->discount_percent > 0)
                                        <div
                                            class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                                            -{{ $product->discount_percent }}%
                                        </div>
                                    @endif

                                    <!-- Quick Action Overlay -->
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex gap-2">
                                            <a href="{{ route('user.products.show', $product->id) }}"
                                                class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors"
                                                title="Quick View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($product->stock_quantity > 0)
                                                <form id="add-to-cart-form"
                                                    action="{{ route('cart.add', $product->id) }}" method="POST"
                                                    class="w-full">
                                                    @csrf
                                                    <input type="hidden" name="quantity" id="form-quantity"
                                                        value="1">
                                                    <button type="submit"
                                                        class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center"
                                                        title="Add to Cart">
                                                        <i class="fas fa-shopping-cart mr-2"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button disabled
                                                    class="w-full bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg shadow-lg cursor-not-allowed flex items-center justify-center"
                                                    title="Out of Stock">
                                                    <i class="fas fa-ban mr-2"></i>Out of Stock
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 flex flex-col flex-1">
                                    <!-- Fixed height title area -->
                                    <div class="h-10 mb-2">
                                        <h4 class="font-semibold text-sm line-clamp-2">{{ $product->name }}</h4>
                                    </div>

                                    <!-- Fixed height price area -->
                                    <div class="h-8 mb-2">
                                        @if ($product->discount_percent > 0)
                                            <div class="flex items-center">
                                                <span class="text-sm font-bold text-red-600">
                                                    ${{ number_format($product->discounted_price ?? $product->price, 2) }}
                                                </span>
                                                <span class="text-xs text-gray-500 line-through ml-2">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-sm font-bold text-gray-900 block">
                                                ${{ number_format($product->price, 2) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Stats at bottom -->
                                    <div class="mt-auto">
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span><i
                                                    class="fas fa-eye mr-1"></i>{{ $product->view_count ?? 0 }}</span>
                                            <span><i class="fas fa-box mr-1"></i>{{ $product->stock_quantity }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <!-- Most Viewed Products -->
            <section class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Most Viewed Products
                    </h3>
                    <a href="{{ route('user.products.index', ['sort' => 'views']) }}"
                        class="text-purple-600 hover:text-purple-800 font-medium flex items-center">
                        View All
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($mostViewed->take(4) as $product)
                        <div
                            class="bg-gray-50 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 group flex flex-col h-full">
                            <div class="relative">
                                <div class="aspect-square w-full bg-white overflow-hidden">
                                    <img src="{{ asset('images/products/' . ($product->image_url ?? 'default-product.jpg')) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                @if ($product->discount_percent > 0)
                                    <div
                                        class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-lg text-sm font-bold">
                                        -{{ $product->discount_percent }}%
                                    </div>
                                @endif

                                <!-- Quick Action Overlay -->
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div class="flex gap-2">
                                        <a href="{{ route('user.products.show', $product->id) }}"
                                            class="bg-white text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors"
                                            title="Quick View">
                                            <i class="fas fa-eye w-4 h-4"></i>
                                        </a>
                                        @if ($product->stock_quantity > 0)
                                            <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}"
                                                method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="quantity" id="form-quantity" value="1">
                                                <button type="submit"
                                                    class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center"
                                                    title="Add to Cart">
                                                    <i class="fas fa-shopping-cart mr-2"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button disabled
                                                class="w-full bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg shadow-lg cursor-not-allowed flex items-center justify-center"
                                                title="Out of Stock">
                                                <i class="fas fa-ban mr-2"></i>Out of Stock
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 flex flex-col flex-1">
                                <!-- Fixed height title area -->
                                <div class="h-14 mb-2">
                                    <h4 class="font-semibold text-lg line-clamp-2">{{ $product->name }}</h4>
                                </div>

                                <!-- Fixed height price area -->
                                <div class="h-10 mb-2">
                                    @if ($product->discount_percent > 0)
                                        <div class="flex items-center">
                                            <span class="text-lg font-bold text-red-600">
                                                ${{ number_format($product->discounted_price ?? $product->price, 2) }}
                                            </span>
                                            <span class="text-sm text-gray-500 line-through ml-2">
                                                ${{ number_format($product->price, 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-lg font-bold text-gray-900 block">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                    <span><i class="fas fa-eye mr-1"></i>{{ $product->view_count ?? 0 }} views</span>
                                    <span><i class="fas fa-box mr-1"></i>{{ $product->stock_quantity }}</span>
                                </div>

                                <!-- Button pushes to bottom -->
                                <div class="mt-auto">
                                    <a href="{{ route('user.products.show', $product->id) }}"
                                        class="w-full bg-purple-500 hover:bg-purple-600 text-white text-center py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Features Section -->
            <section class="bg-gradient-to-r from-blue-500 to-teal-600 rounded-lg shadow-lg p-8 text-white">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-bold mb-4">Why Choose Hanaya Shop?</h3>
                    <p class="text-xl text-blue-100">We are committed to bringing you the highest quality products</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold mb-2">Quality Guaranteed</h4>
                        <p class="text-blue-100">All products undergo strict quality control testing</p>
                    </div>

                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold mb-2">Fast Delivery</h4>
                        <p class="text-blue-100">Guaranteed delivery within 24-48h in city center</p>
                    </div>

                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-semibold mb-2">24/7 Support</h4>
                        <p class="text-blue-100">Our support team is always ready to help you anytime</p>
                    </div>
                </div>
            </section>
        </div>
    </div>

</x-app-layout>

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}" defer></script>
@endpush
