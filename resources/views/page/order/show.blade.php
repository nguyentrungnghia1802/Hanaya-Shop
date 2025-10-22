<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Chi tiết đơn hàng') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto px-6 space-y-8">
        <!-- Thông tin đơn hàng -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Thông tin đơn hàng</h3>
            <div class="space-y-2 text-gray-700">
                <p>
                    <span class="font-medium">Ngày đặt:</span>
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
                <p>
                    <span class="font-medium">Tổng tiền:</span>
                    {{ number_format($order->total_price) }}₫
                </p>
                <p>
                    <span class="font-medium">Trạng thái:</span>
                    {{ $order->status }}
                </p>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Danh sách sản phẩm</h3>

            <div class="space-y-4">
                @foreach ($order->orderDetail as $detail)
                    <a href="{{ route('product.show', $detail->product->id) }}"
                       class="block hover:shadow-md hover:bg-gray-100 transition duration-200 ease-in-out rounded-lg">
                        <div class="flex flex-col md:flex-row md:items-center justify-between bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">
                                    {{ $detail->product->name ?? 'Sản phẩm đã bị xoá' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Số lượng: {{ $detail->quantity }}
                                </p>
                            </div>
                            <div class="mt-2 md:mt-0 text-right">
                                <p class="text-sm text-gray-600">
                                    Đơn giá: {{ number_format($detail->price) }}₫
                                </p>
                                <p class="text-sm font-medium text-gray-900">
                                    Thành tiền: {{ number_format($detail->price * $detail->quantity) }}₫
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end mt-6">
    @if ($order->status === 'shipped')
        <button type="button"
            class="bg-gray-400 text-black font-semibold py-2 px-6 rounded-lg shadow cursor-not-allowed"
            disabled>
            Đã giao
        </button>
    @elseif ($order->status === 'canceled')
        <button type="button"
            class="bg-gray-400 text-black font-semibold py-2 px-6 rounded-lg shadow cursor-not-allowed"
            disabled>
            Đã Hủy
        </button>
    @else
        <a href="{{ route('order.cancel', $order->id) }}"
           class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
            Hủy đơn hàng
        </a>
    @endif
</div>

    </div>
</x-app-layout>
