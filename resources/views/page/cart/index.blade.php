{{-- filepath: resources/views/cart/index.blade.php --}}
<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h2 class="text-2xl font-bold mb-6">Giỏ hàng của bạn</h2>
        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif
        @if(count($cart) > 0)
        <form id="checkout-form" method="POST" action="{{ route('checkout.preview') }}">
            @csrf
            <input type="hidden" name="selected_items_json" id="selected_items_json">
            <div class="overflow-x-auto">
                <!-- Desktop Table -->
                <table class="min-w-full bg-white rounded shadow hidden md:table">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b text-center">
                                <input type="checkbox" id="select-all" title="Chọn tất cả">
                            </th>
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
                            <td class="py-2 px-4 border-b text-center">
                                 <input type="checkbox" name="cart_ids[]" value="{{ $id }}" 
                                     class="cart-checkbox" 
                                    data-price="{{ $item['price'] * $item['quantity'] }}"
                                    data-id="{{ $id }}">
                            </td>
                            <td class="py-2 px-4 border-b">
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                            </td>
                            <td class="py-2 px-4 border-b">{{ $item['name'] }}</td>
                            <td class="py-2 px-4 border-b">{{ number_format($item['price'], 0, ',', '.') }}₫</td>
                            <td class="py-2 px-4 border-b text-center">
                                <div class="flex items-center justify-center gap-2">
                                <button type="button" class="btn-decrease bg-gray-200 px-2 rounded" data-id="{{ $id }}">−</button>
                                <input type="number" min="1" class="quantity-input w-[80px] text-center border rounded" value="{{ $item['quantity'] }}" 
                                    data-id="{{ $id }}" 
                                    data-price="{{ $item['price'] }}"
                                    data-total="{{ $item['price'] * $item['quantity'] }}">
                                    <button type="button" class="btn-increase bg-gray-200 px-2 rounded" data-id="{{ $id }}">+</button>
                                </div>
                            </td>
                            <td class="py-2 px-4 border-b item-total" data-id="{{ $id }}">
                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫
                            </td>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ route('cart.remove', $id) }}" class="text-red-600 hover:underline">Xóa</a>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-right font-bold py-2 px-4">Tổng cộng:</td>
                            <td colspan="2" class="font-bold py-2 px-4" id="totalPrice">0₫</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Desktop Checkout Button -->
                <div class="mt-6 text-right hidden md:block">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded shadow">
                        Thanh toán
                    </button>
                </div>
                <!-- Mobile Card List -->
                <div class="md:hidden space-y-4">
                    <div class="bg-white rounded shadow p-4 flex items-center gap-3">
                        <input type="checkbox" id="select-all-mobile" title="Chọn tất cả">
                        <span class="font-semibold">Chọn tất cả</span>
                    </div>
                    @foreach($cart as $id => $item)
                    <div class="bg-white rounded shadow p-4 flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="cart_ids[]" value="{{ $id }}"
                                class="cart-checkbox"
                                data-price="{{ $item['price'] * $item['quantity'] }}"
                                data-id="{{ $id }}">
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded">
                            <div class="flex-1">
                                <div class="font-semibold">{{ $item['name'] }}</div>
                                <div class="text-pink-600 font-bold">{{ number_format($item['price'], 0, ',', '.') }}₫</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn-decrease bg-gray-200 px-2 rounded" data-id="{{ $id }}">−</button>
                                <input type="number" min="1" class="quantity-input w-[60px] text-center border rounded" value="{{ $item['quantity'] }}"
                                    data-id="{{ $id }}"
                                    data-price="{{ $item['price'] }}"
                                    data-total="{{ $item['price'] * $item['quantity'] }}">
                                <button type="button" class="btn-increase bg-gray-200 px-2 rounded" data-id="{{ $id }}">+</button>
                            </div>
                            <div class="item-total font-bold text-gray-700" data-id="{{ $id }}">
                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫
                            </div>
                            <a href="{{ route('cart.remove', $id) }}" class="text-red-600 hover:underline">Xóa</a>
                        </div>
                    </div>
                    @endforeach
                    <div class="bg-white rounded shadow p-4 flex justify-between items-center font-bold">
                        <span>Tổng cộng:</span>
                        <span id="totalPrice">0₫</span>
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded shadow">
                            Thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @else
        <p>Giỏ hàng của bạn đang trống.</p>
        @endif
    </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const totalPriceEl = document.getElementById('totalPrice');
    const selectAllCheckbox = document.getElementById('select-all');
    const selectAllMobile = document.getElementById('select-all-mobile');
    const checkoutForm = document.getElementById('checkout-form');

    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN').format(value) + '₫';
    }

    function updateTotal() {
        let total = 0;
        const checkedIds = new Set();
        document.querySelectorAll('.cart-checkbox:checked').forEach(cb => {
            const id = cb.dataset.id;
            if (checkedIds.has(id)) return;
            checkedIds.add(id);
            const qtyInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
            const price = parseFloat(qtyInput.dataset.price);
            const quantity = parseInt(qtyInput.value);
            total += price * quantity;
        });
        // Cập nhật cả tổng desktop và mobile
        document.querySelectorAll('#totalPrice').forEach(el => {
            el.textContent = formatCurrency(total);
        });
    }

    function updateCheckboxPrice(id, newTotal) {
        const cb = document.querySelector(`.cart-checkbox[data-id="${id}"]`);
        if (cb) {
            cb.dataset.price = newTotal;
        }
    }

    function updateItemTotal(id, quantity, price) {
        const totalCell = document.querySelector(`.item-total[data-id="${id}"]`);
        if (totalCell) {
            const total = quantity * price;
            totalCell.textContent = formatCurrency(total);
        }
    }

    // Tăng giảm số lượng
    document.querySelectorAll('.btn-increase').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            let quantity = parseInt(input.value);
            quantity++;
            input.value = quantity;
            updateCheckboxPrice(id, quantity * parseFloat(input.dataset.price));
            updateItemTotal(id, quantity, parseFloat(input.dataset.price));
            updateTotal();
        });
    });

    document.querySelectorAll('.btn-decrease').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            let quantity = parseInt(input.value);
            if (quantity > 1) {
                quantity--;
                input.value = quantity;
                updateCheckboxPrice(id, quantity * parseFloat(input.dataset.price));
                updateItemTotal(id, quantity, parseFloat(input.dataset.price));
                updateTotal();
            }
        });
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function () {
            const id = this.dataset.id;
            const quantity = parseInt(this.value);
            if (quantity >= 1) {
                updateCheckboxPrice(id, quantity * parseFloat(this.dataset.price));
                updateItemTotal(id, quantity, parseFloat(this.dataset.price));
                updateTotal();
            }
        });
    });

    // Checkbox chọn tất cả
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.cart-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            if (selectAllMobile) selectAllMobile.checked = isChecked;
            updateTotal();
        });
    }
    if (selectAllMobile) {
        selectAllMobile.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.cart-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            if (selectAllCheckbox) selectAllCheckbox.checked = isChecked;
            updateTotal();
        });
    }

    // Khi chọn 1 checkbox thì kiểm tra lại trạng thái chọn tất cả
    document.querySelectorAll('.cart-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const allCheckboxes = document.querySelectorAll('.cart-checkbox');
            const allChecked = [...allCheckboxes].every(c => c.checked);
            if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
            if (selectAllMobile) selectAllMobile.checked = allChecked;
            updateTotal();
        });
    });

    // Gửi form
    checkoutForm.addEventListener('submit', function (e) {
        const selectedItems = [];
        const checkedIds = new Set();
        document.querySelectorAll('.cart-checkbox:checked').forEach(cb => {
            const id = cb.dataset.id;
            if (checkedIds.has(id)) return; // Nếu đã lấy rồi thì bỏ qua
            checkedIds.add(id);
            // Tìm row cho desktop hoặc card cho mobile
            let row = cb.closest('tr');
            if (!row) {
                row = cb.closest('.bg-white');
            }
            const image = row.querySelector('img').getAttribute('src');
            // Desktop: tên ở td thứ 3, Mobile: lấy .font-semibold
            let name = '';
            const nameTd = row.querySelector('td:nth-child(3)');
            if (nameTd) {
                name = nameTd.textContent.trim();
            } else {
                const nameDiv = row.querySelector('.font-semibold');
                name = nameDiv ? nameDiv.textContent.trim() : '';
            }
            const price = parseFloat(row.querySelector('.quantity-input').dataset.price);
            const quantity = parseInt(row.querySelector('.quantity-input').value);
            const subtotal = price * quantity;
            selectedItems.push({
                id,
                image,
                name,
                price,
                quantity,
                subtotal
            });
        });
        document.getElementById('selected_items_json').value = JSON.stringify(selectedItems);
    });

    updateTotal();
});

</script>


</x-app-layout>