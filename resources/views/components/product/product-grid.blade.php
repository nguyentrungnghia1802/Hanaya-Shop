<!-- resources/views/components/product-grid.blade.php -->
<div class="py-8 bg-gray-100 dark:bg-[#0a0a0a] text-gray-800 dark:text-gray-100">
    <!-- Tiêu đề danh mục -->
    <h1 class="text-3xl font-bold text-center mb-6">{{ $title ?? 'Danh mục sản phẩm' }}</h1>

    <!-- Thanh chức năng (sắp xếp, lọc, số lượng hiển thị...) -->
    <div class="max-w-7xl mx-auto px-4 mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Hiển thị các tùy chọn sắp xếp, ví dụ: -->
        <div class="flex items-center gap-2">
            <label for="sort" class="font-semibold">Sắp xếp theo:</label>
            <select id="sort" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 bg-white dark:bg-[#1b1b18]">
                <option value="default">Mặc định</option>
                <option value="price-asc">Giá thấp đến cao</option>
                <option value="price-desc">Giá cao đến thấp</option>
            </select>
        </div>

        <!-- Hiển thị tùy chọn số lượng hiển thị, ví dụ: -->
        <div class="flex items-center gap-2">
            <label for="quantity" class="font-semibold">Số lượng:</label>
            <select id="quantity" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 bg-white dark:bg-[#1b1b18]">
                <option value="8">8</option>
                <option value="12">12</option>
                <option value="16">16</option>
            </select>
        </div>
    </div>

    <!-- Grid sản phẩm -->
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white dark:bg-[#1b1b18] rounded shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img 
                    src="{{ asset('images/' . $product['image']) }}" 
                    alt="{{ $product['name'] }}" 
                    class="w-full h-48 object-cover"
                >
                <div class="p-4 flex flex-col gap-2">
                    <h2 class="font-semibold text-lg">{{ $product['name'] }}</h2>
                    <p class="text-pink-500 font-bold text-xl">
                        ${{ number_format($product['price'], 2, '.', ',') }}
                    </p>
                    <button 
                        class="mt-auto bg-pink-500 text-white py-2 px-4 rounded hover:bg-pink-600 transition-colors"
                    >
                        ĐẶT HÀNG
                    </button>
                </div>
            </div>
        @empty
            <p class="col-span-4 text-center text-gray-500 dark:text-gray-300">
                Không có sản phẩm nào.
            </p>
        @endforelse
    </div>
</div>
