<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Success') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-green-700 mb-2">Đặt hàng thành công!</h3>
            <p class="text-gray-700 leading-relaxed mb-4">
                Cảm ơn bạn đã đặt hàng tại <span class="font-semibold">Hanaya Shop</span>. Đơn hàng của bạn đã được xử lý
                thành công.
            </p>
            <p class="text-gray-600 mb-4">
                Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng và thông tin giao hàng.
            </p>

            <div class="mt-6 flex flex-col md:flex-row justify-end gap-4">
                <a href="{{ route('order.cancel', $orderId) }}" class="bg-gray-200 text-gray-800 px-5 py-2 rounded hover:bg-gray-300 transition">
                    Hủy đơn hàng
                </a>
                <a href="{{ route('order.show', $orderId) }}" class="bg-pink-600 text-white px-5 py-2 rounded hover:bg-pink-700 transition">
                    Xem đơn hàng
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
