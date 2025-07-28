@php
    $total = 0;
    if (isset($selectedItems) && is_array($selectedItems)) {
        foreach ($selectedItems as $item) {
            $total += $item['subtotal'] ?? 0;
        }
    }
    $defaultMethod = isset($paymentMethods) && count($paymentMethods) > 0 ? ucfirst(str_replace('_', ' ', $paymentMethods[0])) : 'Không có phương thức';

@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thanh toán') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-12 px-2 sm:px-4 space-y-8 sm:space-y-10">
        
        {{-- Địa chỉ nhận hàng --}}
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Địa chỉ nhận hàng</h3>
            <h4 class="text-gray-700 leading-relaxed mb-4">
                <span class="block font-medium">Lê Đức Anh Tài</span>
                <span class="block text-sm text-gray-600">(+84) 374169035</span>
                <span class="block text-sm text-gray-600">
                    Số 15, Ngách 415 Ngõ 192 Lê Trọng Tấn,<br>
                    Phường Định Công, Quận Hoàng Mai, Hà Nội
                </span>
            </h4>
            <button class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700 transition">
                Thay đổi
            </button>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 overflow-x-auto">
            @if(count($selectedItems) > 0)
            <table class="min-w-full text-xs sm:text-sm">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Ảnh</th>
                        <th class="py-2 px-4 border-b">Tên sản phẩm</th>
                        <th class="py-2 px-4 border-b">Giá</th>
                        <th class="py-2 px-4 border-b">Số lượng</th>
                        <th class="py-2 px-4 border-b">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($selectedItems as $item)
                        @php $total += $item['subtotal']; @endphp
                        <tr>
                            <td class="py-2 px-4 border-b">
                                <img src="{{ $item['image'] }}" class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded">
                            </td>
                            <td class="py-2 px-4 border-b">{{ $item['name'] }}</td>
                            <td class="py-2 px-4 border-b">{{ number_format($item['price'], 0, ',', '.') }} USD</td>
                            <td class="py-2 px-4 border-b">{{ $item['quantity'] }}</td>
                            <td class="py-2 px-4 border-b">{{ number_format($item['subtotal'], 0, ',', '.') }} USD</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right font-bold py-2 px-4">Tổng cộng:</td>
                        <td class="font-bold py-2 px-4">{{ number_format($total, 0, ',', '.') }} USD</td>
                    </tr>
                </tbody>
            </table>
            @else
                <p>Không có sản phẩm nào được chọn để thanh toán.</p>
            @endif
        </div>

        {{-- Voucher --}}
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Voucher</h3>
            <button class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700 transition">
                Chọn voucher
            </button>
        </div>

        {{-- Lời nhắn--}}
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Lời nhắn:</h3>
            <textarea class="w-full p-2 border rounded text-xs sm:text-sm" rows="4" placeholder="Nhập lời nhắn của bạn..."></textarea>
        </div>

        {{-- Thanh toán--}}
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Phương thức thanh toán:</h3>
                    <h4 class="text-gray-700" id="current-method">{{ $defaultMethod }}</h4>
                </div>
                <button id="change-method-btn" type="button" class="bg-pink-600 text-white px-3 sm:px-4 py-2 rounded hover:bg-pink-700 transition whitespace-nowrap w-full sm:w-auto">
    Thay đổi
</button>
            </div>

            <div>
                <div class="flex items-center justify-between flex-wrap gap-2 mb-4 text-xs sm:text-base">
                    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Tổng tiền hàng:</h3>
                    <p class="text-xl font-bold text-pink-600">{{ number_format($total, 0, ',', '.') }}₫</p>
                </div>
                <div class="flex items-center justify-between flex-wrap gap-2 mb-4 text-xs sm:text-base">
                    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Phí vận chuyển:</h3>
                    <p class="text-xl font-bold text-pink-600">8 USD</p>
                </div>
               <div class="flex items-center justify-between flex-wrap gap-2 mb-4 text-xs sm:text-base">
                    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-2">Tổng thanh toán:</h3>
                    <p class="text-xl font-bold text-pink-800">{{ number_format($total + 8, 0, ',', '.') }} USD</p>
               </div class="flex items-center justify-between flex-wrap gap-2 mb-4">
            </div>

           <form method="POST" action="{{ route('checkout.store') }}">
    @csrf
    <input type="hidden" name="payment_method" id="payment_method_input">

    <input type="hidden" name="selected_items_json" value='@json($selectedItems)'>
    
    <button type="submit" class="bg-orange-600 text-white px-3 sm:px-4 py-2 rounded w-full sm:w-auto">
        Đặt hàng
    </button>
</form>




        </div>
  </div>

<!-- Danh sách phương thức thanh toán dưới dạng modal đẹp và giữa màn hình -->
<div id="method-selection"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">Chọn phương thức thanh toán</h3>
        <div class="flex flex-wrap justify-center gap-3">
            @foreach($paymentMethods as $method)
                <button type="button"
                    class="method-option px-4 py-2 border rounded-lg hover:bg-pink-50 transition text-sm sm:text-base"
                    data-method="{{ $method }}">
                    {{ ucfirst(str_replace('_', ' ', $method)) }}
                </button>
            @endforeach
        </div>
        <div class="mt-4 text-center">
            <button id="close-method-selection"
                class="mt-4 text-sm text-gray-500 hover:underline hover:text-gray-700">Hủy</button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('change-method-btn');
        const methodBox = document.getElementById('method-selection');
        const currentMethod = document.getElementById('current-method');
        const closeBtn = document.getElementById('close-method-selection');

        // Mở modal
        btn.addEventListener('click', () => {
            methodBox.classList.remove('hidden');
        });

        // Đóng modal khi chọn phương thức
        document.querySelectorAll('.method-option').forEach(button => {
            button.addEventListener('click', () => {
                const selected = button.dataset.method;
                document.getElementById('payment_method_input').value = selected;
                currentMethod.textContent = selected.charAt(0).toUpperCase() + selected.slice(1).replaceAll('_', ' ');
                methodBox.classList.add('hidden');
            });
        });

        // Đóng modal khi nhấn nút hủy
        closeBtn.addEventListener('click', () => {
            methodBox.classList.add('hidden');
        });
    });
</script>

</x-app-layout>
