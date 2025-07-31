{{-- Product Card Component with Rating --}}
@props([
    'product',
    'showQuickView' => false,
    'cardClass' => 'bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transition-all duration-300 transform hover:scale-105 flex flex-col h-full'
])

<div class="{{ $cardClass }}">
    <!-- Product Image -->
    <div class="aspect-square bg-gray-100 overflow-hidden relative group">
        <img src="{{ $product->image_url ? asset('images/products/' . $product->image_url) : asset('images/no-image.png') }}" 
             class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" 
             alt="{{ $product->name }}"
             loading="lazy">
        
        <!-- Discount Badge -->
        @if($product->discount_percent > 0)
            <div class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-lg text-xs font-bold">
                -{{ $product->discount_percent }}%
            </div>
        @endif

        <!-- Quick View Button (if enabled) -->
        @if($showQuickView)
            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <button class="bg-white text-gray-800 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors duration-200">
                    Quick View
                </button>
            </div>
        @endif
    </div>

    <div class="p-3 sm:p-4 flex flex-col flex-1">
        <!-- Fixed height title area -->
        <div class="h-12 mb-2">
            <h4 class="text-sm sm:text-base font-semibold text-gray-800 line-clamp-2">{{ $product->name }}</h4>
        </div>

        <!-- Rating -->
        <div class="flex items-center space-x-2 mb-2">
            <x-star-rating :rating="$product->average_rating" size="sm" />
            <span class="text-xs text-gray-500">({{ $product->review_count }})</span>
        </div>
        
        <!-- Fixed height price area -->
        <div class="h-12 mb-3">
            @if($product->discount_percent > 0)
            <div class="space-y-1">
                <p class="text-pink-600 font-bold text-lg">
                    {{ number_format($product->discounted_price, 0, ',', '.') }} USD
                </p>
                <p class="text-sm text-gray-500 line-through">
                    {{ number_format($product->price, 0, ',', '.') }} USD
                </p>
            </div>
            @else
            <p class="text-pink-600 font-bold text-lg">{{ number_format($product->price, 0, ',', '.') }} USD</p>
            @endif
        </div>
        
        <!-- Button pushes to bottom -->
        <div class="mt-auto">
            <a href="{{ route('user.products.show', $product->id) }}" 
               class="block w-full text-center bg-pink-500 hover:bg-pink-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-300 text-sm">
                View Details
            </a>
        </div>
    </div>
</div>
