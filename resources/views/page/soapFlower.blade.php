<x-app-layout>
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-6 sm:py-12">
        <!-- Tiêu đề -->
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Sản phẩm Soap Flower</h2>

        <!-- Bộ lọc -->
        <div class="mb-8">
            @php
                $currentSort = request()->get('sort');
            @endphp

            <div class="flex flex-wrap items-center gap-4">
                <h1 class="text-xl font-bold text-white">Sắp xếp theo:</h1>

                <div class="flex flex-wrap gap-2">
                    <x-nav-link :href="route('soapFlower', ['sort' => 'desc'])" :active="$currentSort === 'desc'"
                        class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600">
                        Giá Cao - Thấp
                    </x-nav-link>

                    <x-nav-link :href="route('soapFlower', ['sort' => 'asc'])" :active="$currentSort === 'asc'"
                        class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600">
                        Giá Thấp - Cao
                    </x-nav-link>

                    <x-nav-link :href="route('soapFlower', ['sort' => 'sale'])" :active="$currentSort === 'sale'"
                        class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600">
                        Khuyến Mãi Hot
                    </x-nav-link>

                    <x-nav-link :href="route('soapFlower', ['sort' => 'views'])" :active="$currentSort === 'views'"
                        class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-pink-600">
                        Xem nhiều
                    </x-nav-link>
                </div>
            </div>
        </div>

        <!-- Lưới sản phẩm -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
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
