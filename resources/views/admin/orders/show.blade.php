@extends('layouts.admin')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Order details') }}
    </h2>
@endsection

@section('content')

    <div class="py-10 max-w-5xl mx-auto px-6 space-y-8">
        <!-- Grid 2 cột: Thông tin người đặt + Thông tin đơn hàng -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Thông tin người đặt -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="space-y-2 text-gray-700">
                    <p>
                        <span class="font-medium">Người đặt:</span>
                        {{ $order->user->name }}
                    </p>
                    <p>
                        <span class="font-medium">Email:</span>
                        {{ $order->user->email }}
                    </p>
                    <p>
                        <span class="font-medium">Địa chỉ:</span>
                        <span class="block text-sm text-gray-600">(+84) 374169035</span>
                        <span class="block text-sm text-gray-600">
                            Số 15, Ngách 415 Ngõ 192 Lê Trọng Tấn,<br>
                            Phường Định Công, Quận Hoàng Mai, Hà Nội
                        </span>
                    </p>
                </div>
            </div>

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

        
<div class="flex justify-end gap-4 mt-6">
    <!-- Nút Hủy -->
    {{-- <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}">
        @csrf
        @method('PUT')
        <button type="submit"
            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
            Hủy đơn hàng
        </button>
    </form> --}}
    

    @if ($order->status === 'shipped')
        <!-- Nút Disabled -->
        <button type="button"
            class="bg-gray-400 text-black font-semibold py-2 px-6 rounded-lg shadow cursor-not-allowed"
            disabled>
            Đã giao
        </button>
    @elseif ($order->status === 'canceled')
        <!-- Nút Disabled -->
        <button type="button"
            class="bg-gray-400 text-black font-semibold py-2 px-6 rounded-lg shadow cursor-not-allowed"
            disabled>
            Đã Hủy
        </button>
    @else
        <button type="submit"
            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
            Hủy đơn hàng
        </button>
        <!-- Nút Xác nhận -->
        <form method="POST" action="{{ route('admin.order.confirm', $order->id) }}">
            @csrf
            @method('PUT')
            <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
                Xác nhận đơn hàng
            </button>
        </form>
    @endif
</div>

</div>

    </div>
@endsection
