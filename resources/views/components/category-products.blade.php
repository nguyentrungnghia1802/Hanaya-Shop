@props(['categoryData', 'title' => 'Products by Category'])

@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow text-center">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif
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
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
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
                                <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="quantity" id="form-quantity" value="1">
                                    <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-colors duration-300 flex items-center justify-center" title="Add to Cart">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <h4 class="font-semibold text-lg mb-2 line-clamp-2 text-gray-800">{{ $product->name }}</h4>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ strip_tags($product->descriptions) }}</p>
                        
                        @if($product->category)
                        <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full mb-3">
                            {{ $product->category->name }}
                        </span>
                        @endif
                        
                        <!-- Price -->
                        <div class="mb-3">
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
                            <span class="text-lg font-bold text-gray-900">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            @endif
                        </div>
                        
                        <!-- Meta Info -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                            <span><i class="fas fa-eye mr-1"></i>{{ $product->view_count ?? 0 }}</span>
                            <span><i class="fas fa-box mr-1"></i>{{ $product->stock_quantity }}</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <a href="{{ route('user.products.show', $product->id) }}" 
                           class="w-full bg-pink-500 hover:bg-pink-600 text-white text-center py-2 px-4 rounded-lg transition-colors flex items-center justify-center mb-2">
                            <i class="fas fa-eye mr-2"></i>View Details
                        </a>
                        <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="quantity" id="form-quantity" value="1">
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg transition-colors flex items-center justify-center" title="Add to Cart">
                                <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('/cart/add/' + productId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Product added to cart successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert('Product added to cart successfully!');
            }
            
            // Update cart count if element exists
            const cartCount = document.querySelector('.cart-count');
            if (cartCount && data.cart_count) {
                cartCount.textContent = data.cart_count;
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'An error occurred while adding the product'
                });
            } else {
                alert(data.message || 'An error occurred while adding the product');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while adding the product'
            });
        } else {
            alert('An error occurred while adding the product');
        }
    });
}
</script>
@endif
