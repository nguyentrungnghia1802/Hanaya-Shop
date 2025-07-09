@extends('layouts.admin')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Thông tin tài khoản: {{ $user->name }}
    </h2>
@endsection
@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow space-y-6">
        <div>
            <h3 class="text-lg font-semibold mb-2">Thông tin cơ bản</h3>
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>
        </div>
        <div>
            <h3 class="text-lg font-semibold mb-2">Giỏ hàng</h3>
            @if ($carts->count())
            <table class="min-w-full bg-white rounded shadow mx-auto">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b !text-center">#</th>
                        <th class="py-2 px-4 border-b !text-center">Tên sản phẩm</th>
                        <th class="py-2 px-4 border-b !text-center">Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $i => $cart)
                        <tr>
                            <td class="py-2 px-4 border-b !text-center">{{ $i + 1 }}</td>
                            <td class="py-2 px-4 border-b !text-center">{{ $cart->product->name ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b !text-center">{{ $cart->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <p class="text-gray-500">Không có sản phẩm trong giỏ hàng.</p>
            @endif
        </div>
        <div>
            <h3 class="text-lg font-semibold mb-2">Đơn hàng</h3>
            @if ($orders->count())
                <ul class="list-disc ml-6">
                    @foreach ($orders as $order)
                        <li>Mã đơn: {{ $order->id }} - Tổng tiền: {{ number_format($order->total ?? 0, 0, ',', '.') }}đ</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">Chưa có đơn hàng nào.</p>
            @endif
        </div>
    </div>
@endsection