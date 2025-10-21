<x-app-layout>
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-6 sm:py-12">
        <!-- Tiêu đề -->
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Sản phẩm Soap Flower</h2>

        <!-- Bộ lọc & Tìm kiếm -->
        <div class="mb-8">
            @php
                $currentSort = request()->get('sort');
                $keyword = request()->get('q');
            @endphp

            <form method="GET" action="{{ route('soapFlower') }}" class="flex flex-wrap items-center gap-4">
                <input type="text" name="q" value="{{ $keyword }}" placeholder="Tìm sản phẩm..." class="px-4 py-2 rounded border focus:outline-none focus:ring w-64">
                <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Tìm kiếm</button>
                <span class="text-xl font-bold text-white">Sắp xếp theo:</span>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('soapFlower', array_merge(request()->except('sort'), ['sort' => 'desc', 'q' => $keyword])) }}" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600 {{ $currentSort === 'desc' ? 'font-bold' : '' }}">Giá Cao - Thấp</a>
                    <a href="{{ route('soapFlower', array_merge(request()->except('sort'), ['sort' => 'asc', 'q' => $keyword])) }}" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600 {{ $currentSort === 'asc' ? 'font-bold' : '' }}">Giá Thấp - Cao</a>
                    <a href="{{ route('soapFlower', array_merge(request()->except('sort'), ['sort' => 'sale', 'q' => $keyword])) }}" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600 {{ $currentSort === 'sale' ? 'font-bold' : '' }}">Khuyến Mãi Hot</a>
                    <a href="{{ route('soapFlower', array_merge(request()->except('sort'), ['sort' => 'views', 'q' => $keyword])) }}" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600 {{ $currentSort === 'views' ? 'font-bold' : '' }}">Xem nhiều</a>
                </div>
            </form>
        </div>

        <!-- Lưới sản phẩm -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @if ($products->count() > 0)
                @foreach ($products as $productItem)
                    <div class="bg-white rounded shadow hover:shadow-xl overflow-hidden transition flex flex-col">
                        <div class="relative">
                            <img src="{{ asset('images/' . $productItem->image_url) }}" class="w-full h-32 sm:h-48 object-cover"
                                alt="{{ $productItem->name }}">
                            <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                Giảm {{ rand(5, 20) }}%
                            </span>
                        </div>
                        <div class="p-2 sm:p-4 flex-1 flex flex-col justify-between">
                            <h3 class="text-xs sm:text-sm font-semibold">{{ $productItem->name }}</h3>
                            <p class="text-xs text-gray-500 mb-1">@if($productItem->category) <span class="font-semibold text-pink-600">{{ $productItem->category->name }}</span> @endif</p>
                            <p class="text-xs text-gray-600 mb-1">{{ $productItem->descriptions }}</p>
                            <p class="text-pink-600 font-bold text-base sm:text-lg mt-1">
                                {{ number_format($productItem->price, 0, ',', '.') }}₫
                            </p>
                            <p class="text-xs text-gray-600">Khuyến mãi thêm đến <span
                                    class="text-red-500 font-semibold">500.000₫</span></p>
                            <div class="flex items-center justify-between mt-2">
                                <div class="text-yellow-500 text-xs sm:text-sm">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <button class="text-gray-500 hover:text-red-600">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <a href="{{ route('soapFlower.show', $productItem->id) }}"
                                class="mt-2 sm:mt-4 block text-center bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded w-full">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 flex flex-col items-center justify-center py-12">
                    <img src="{{ asset('fixed_resources/not_founded.jpg') }}" alt="Không tìm thấy" class="w-32 h-32 mb-4 opacity-70">
                    <p class="text-lg text-gray-500 font-semibold">Không tìm thấy sản phẩm phù hợp.</p>
                </div>
            @endif
        </div>

        <!-- Phân trang -->
        <div class="flex justify-center mt-6 sm:mt-10">
            {{ $products->links() }}
        </div>

        <!-- Nút Liên hệ & Lên đầu trang -->
        <div class="fixed bottom-2 right-2 sm:bottom-4 sm:right-4 flex flex-col gap-2 sm:gap-3 z-50">
            <a href="tel:0123456789"
                class="bg-pink-600 text-white px-4 py-2 rounded-full shadow hover:bg-pink-700 flex items-center gap-2">
                <i class="fas fa-headset"></i> Liên hệ
            </a>
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                class="bg-gray-700 text-white px-4 py-2 rounded-full shadow hover:bg-gray-600">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>
    </div>
</x-app-layout>
