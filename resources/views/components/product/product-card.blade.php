{{-- resources/views/components/product/product-card.blade.php --}}
@props(['product'])

<div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
    <div class="p-5">
        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
        <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($product->descriptions, 80) }}</p>
        <div class="flex items-center justify-between mb-4">
            <span class="text-lg font-bold text-pink-600">${{ number_format($product->price, 2, '.', ',') }}</span>
            @if($product->stock_quantity > 0)
                <span class="text-green-600 text-sm">{{ __('product.in_stock') }}</span>
            @else
                <span class="text-red-500 text-sm">{{ __('product.out_of_stock') }}</span>
            @endif
        </div>
        <a href="{{ route('product.show', $product->id) }}"
           class="block w-full text-center bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded transition duration-200">
           {{ __('product.view_details') }}
        </a>
    </div>
</div>
