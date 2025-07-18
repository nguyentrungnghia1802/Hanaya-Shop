{{-- resources/views/page/productDetail.blade.php --}}
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10">

        <!-- Tiêu đề -->
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Chi tiết sản phẩm</h2>

        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Thông tin chi tiết sản phẩm -->
        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col md:flex-row gap-6">
            <div class="md:w-1/2">
                <img src="{{ asset('images/' . $product->image_url) }}" alt="{{ $product->name }}" class="rounded w-full h-auto object-cover">
            </div>
            <div class="md:w-1/2">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">{{ $product->name }}</h3>
                <p class="text-gray-700 mb-4">{{ $product->descriptions }}</p>
                <p class="text-2xl font-bold text-red-500 mb-4">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                <div id="quantity-form" class="mt-4 p-4 bg-gray-100 rounded shadow w-fit">
                    <label for="quantity" class="block mb-2 font-medium text-gray-700">Chọn số lượng:</label>
                    <div class="flex items-center space-x-2">
                        <button id="decrease" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">-</button>
                        <input id="quantity" type="number" value="1" min="1" class="w-16 text-center border rounded px-2 py-1" />
                        <button id="increase" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">+</button>
                    </div>
                </div>
                <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="quantity" id="form-quantity" value="1">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded shadow">
                        Thêm vào giỏ
                    </button>
                </form>

                <form action="{{ route('cart.buyNow') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded shadow">Mua ngay</button>
                </form>




            </div>
        </div>

        <!-- Đề xuất sản phẩm khác -->
        <div class="mt-12">
            <h3 class="text-xl font-bold mb-4">Sản phẩm tương tự</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $item)
                    <div class="bg-white rounded shadow hover:shadow-xl overflow-hidden transition">
                        <img src="{{ $item->image_url }}" class="w-full h-40 object-cover" alt="{{ $item->name }}">
                        <div class="p-4">
                            <h4 class="text-sm font-semibold">{{ $item->name }}</h4>
                            <p class="text-pink-600 font-bold">{{ number_format($item->price, 0, ',', '.') }}₫</p>
                            <a href="{{ route('soapFlower.show', $item->id) }}" class="text-sm text-pink-500 hover:underline mt-2 inline-block">Xem chi tiết</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
<script>
    const quantityInput = document.getElementById('quantity');
    const formQuantityInput = document.getElementById('form-quantity');

    // Tăng số lượng
    document.getElementById('increase').addEventListener('click', () => {
        quantityInput.value = parseInt(quantityInput.value) + 1;
    });

    // Giảm số lượng
    document.getElementById('decrease').addEventListener('click', () => {
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    });

    // Đồng bộ số lượng khi submit
    document.getElementById('add-to-cart-form').addEventListener('submit', function () {
        formQuantityInput.value = quantityInput.value;
    });

    
</script>

</x-app-layout>
