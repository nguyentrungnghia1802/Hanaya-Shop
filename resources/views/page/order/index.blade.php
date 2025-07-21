<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Danh sách đơn hàng') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4">
        @foreach ($orders as $order)
            <div class="border p-4 mb-4 rounded-lg shadow">
                <div class="flex justify-between">
                    <div>
                        <strong>Mã đơn hàng:</strong> #{{ $order->id }} <br>
                        <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }} <br>
                        <strong>Tổng tiền:</strong> {{ number_format($order->total_price) }}₫ <br>
                        <strong>Trạng thái:</strong> {{ $order->status }}
                    </div>
                    <div class="self-center">
                        <a href="{{ route('order.show', $order->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
