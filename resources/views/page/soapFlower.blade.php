<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Tiêu đề -->
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Sản phẩm Soap Flower</h2>

        <!-- Bộ lọc -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Sắp xếp theo</h1>
            <div class="flex space-x-2">
                <button class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600">Giá Cao - Thấp</button>
                <button class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600">Giá Thấp - Cao</button>
                <button class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600">Khuyến Mãi Hot</button>
                <button class="bg-pink-600 text-white px-4 py-2 rounded">Xem nhiều</button>
            </div>
        </div>

        <!-- Lưới sản phẩm -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $productItem)
                <div class="bg-white rounded shadow hover:shadow-xl overflow-hidden transition">
                    <div class="relative">
                        <img src="{{ $productItem->image_url }}" class="w-full h-48 object-cover" alt="{{ $productItem->name }}">
                        <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded">Giảm {{ rand(5,20) }}%</span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold">{{ $productItem->name }}</h3>
                        <p class="text-pink-600 font-bold text-lg mt-1">{{ number_format($productItem->price, 0, ',', '.') }}₫</p>
                        <p class="text-xs text-gray-600">Khuyến mãi thêm đến <span class="text-red-500 font-semibold">500.000₫</span></p>
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-yellow-500 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                            </div>
                            <button class="text-gray-500 hover:text-red-600"><i class="far fa-heart"></i></button>
                        </div>
                        <a href="{{ route('product.show', $productItem->id) }}" class="mt-4 block text-center bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded">Xem chi tiết</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Phân trang -->
        <div class="flex justify-center mt-10">
            {{ $products->links() }}
        </div>

        <!-- Nút Liên hệ & Lên đầu trang -->
        <div class="fixed bottom-4 right-4 flex flex-col gap-3">
            <a href="tel:0123456789" class="bg-pink-600 text-white px-4 py-2 rounded-full shadow hover:bg-pink-700 flex items-center gap-2">
                <i class="fas fa-headset"></i> Liên hệ
            </a>
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="bg-gray-700 text-white px-4 py-2 rounded-full shadow hover:bg-gray-600">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>
    </div>
</x-app-layout>
