@props(['categoryData', 'title' => 'Products by Category'])

@if(isset($categoryData) && count($categoryData) > 0)
<div class="mb-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">{{ $title }}</h2>
        <p class="text-center text-gray-600 mb-8">Discover the latest products from featured categories</p>
        
        @foreach($categoryData as $category)
        <div class="mb-10">
            <!-- Category Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $category['name'] }}</h3>
                    <span class="ml-3 bg-pink-100 text-pink-600 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $category['products']->count() }} products
                    </span>
                </div>
                <a href="{{ route('user.products.index', ['category_name' => $category['slug']]) }}" 
                   class="text-pink-600 hover:text-pink-700 font-semibold flex items-center">
                    View All
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($category['products'] as $product)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group flex flex-col h-full">
                    <div class="relative">
                        <div class="aspect-square w-full bg-gray-100 overflow-hidden">
                            <img 
                                src="{{ asset('images/products/' . ($product->image_url ?? 'default-product.jpg')) }}" 
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        </div>
                        
                        @if($product->discount_percent > 0)
                        <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-lg text-sm font-bold">
                            -{{ $product->discount_percent }}%
                        </div>
                        @endif
                        
                        @if($product->stock_quantity <= 5)
                        <div class="absolute top-2 right-2 bg-orange-500 text-white px-2 py-1 rounded-lg text-xs">
                            Low Stock
                        </div>
                        @endif

                        <!-- Quick Action Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="flex gap-2">
                                <a href="{{ route('user.products.show', $product->id) }}" 
                                   class="bg-white text-gray-800 p-2 rounded-full hover:bg-pink-500 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @auth
                                    <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="quantity" id="form-quantity" value="1">
                                        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center" title="Add to Cart">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center" title="Sign in to add to cart">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 flex flex-col flex-1">
                        <!-- Fixed height title area -->
                        <div class="h-14 mb-2">
                            <h4 class="font-semibold text-lg line-clamp-2 text-gray-800">{{ $product->name }}</h4>
                        </div>
                        
                        <!-- Fixed height description -->
                        <div class="h-10 mb-3">
                            <p class="text-gray-600 text-sm line-clamp-2">{{ strip_tags($product->descriptions) }}</p>
                        </div>
                        
                        @if($product->category)
                        <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full mb-3">
                            {{ $product->category->name }}
                        </span>
                        @endif
                        
                        <!-- Fixed height price area -->
                        <div class="h-8 mb-3">
                            @if($product->discount_percent > 0)
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
                        
                        <!-- Meta Info -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                            <span><i class="fas fa-eye mr-1"></i>{{ $product->view_count ?? 0 }}</span>
                            <span><i class="fas fa-box mr-1"></i>{{ $product->stock_quantity }}</span>
                        </div>
                        
                        <!-- Action Buttons - Push to bottom -->
                        <div class="mt-auto space-y-2">
                            <a href="{{ route('user.products.show', $product->id) }}" 
                               class="w-full bg-pink-500 hover:bg-pink-600 text-white text-center py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fas fa-eye mr-2"></i>View Details
                            </a>
                            @auth
                                <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="quantity" id="form-quantity" value="1">
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg transition-colors flex items-center justify-center" title="Add to Cart">
                                        <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors flex items-center justify-center" title="Sign in to add to cart">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In to Buy
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/category-products.js') }}" defer></script>
@endpush

@endif
