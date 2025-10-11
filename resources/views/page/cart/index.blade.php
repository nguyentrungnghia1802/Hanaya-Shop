{{-- filepath: resources/views/cart/index.blade.php --}}
<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h2 class="text-2xl font-bold mb-6">Giỏ hàng của bạn</h2>
        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif
        @if(count($cart) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Ảnh</th>
                        <th class="py-2 px-4 border-b">Tên sản phẩm</th>
                        <th class="py-2 px-4 border-b">Giá</th>
                        <th class="py-2 px-4 border-b">Số lượng</th>
                        <th class="py-2 px-4 border-b">Thành tiền</th>
                        <th class="py-2 px-4 border-b"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                    @php $total += $item['price'] * $item['quantity']; @endphp
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="py-2 px-4 border-b">{{ $item['name'] }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($item['price'], 0, ',', '.') }}₫</td>
                        <td class="py-2 px-4 border-b">{{ $item['quantity'] }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('cart.remove', $id) }}" class="text-red-600 hover:underline">Xóa</a>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right font-bold py-2 px-4">Tổng cộng:</td>
                        <td colspan="2" class="font-bold py-2 px-4">{{ number_format($total, 0, ',', '.') }}₫</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            <a href="#" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded shadow">Thanh toán</a>
        </div>
        @else
        <p>Giỏ hàng của bạn đang trống.</p>
        @endif
    </div>
</x-app-layout>