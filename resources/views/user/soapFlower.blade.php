<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Sản phẩm Soap Flower</h2>

            {{-- Nếu có sản phẩm đang được xem chi tiết --}}
            @isset($product)
                <div class="mb-12 p-6 bg-white rounded shadow-lg">
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h3>
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover my-4 rounded">
                    <p class="text-gray-700 mb-2">{{ $product->descriptions }}</p>
                    <p class="text-pink-600 font-bold text-xl">
                        {{ number_format($product->price, 0, ',', '.') }}₫
                    </p>
                    <div class="mt-4">
                        <a href="{{ route(name: 'soapFlower') }}" class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                            ← Quay lại danh sách
                        </a>
                    </div>
                </div>
            @endisset

            {{-- Danh sách sản phẩm --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($products as $productItem)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                        <img src="{{ $productItem->image_url }}" alt="{{ $productItem->name }}" class="w-full h-56 object-cover">
                        <div class="p-5">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $productItem->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($productItem->descriptions, 80) }}</p>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-lg font-bold text-pink-600">{{ number_format($productItem->price, 0, ',', '.') }}₫</span>
                                @if($productItem->stock_quantity > 0)
                                    <span class="text-green-600 text-sm">Còn hàng</span>
                                @else
                                    <span class="text-red-500 text-sm">Hết hàng</span>
                                @endif
                            </div>
                            <a href="{{ route('product.show', $productItem->id) }}"
                               class="block w-full text-center bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded transition duration-200">
                               Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Phân trang --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
