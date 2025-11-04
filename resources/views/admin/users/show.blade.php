@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('admin.account_details') }}: {{ $user->name }}
    </h2>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow space-y-6">
        <!-- Basic information -->
        <div>
            <h3 class="text-lg font-semibold mb-2">Basic Information</h3>
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>{{ __('admin.name') }}:</strong> {{ $user->name }}</p>
            <p><strong>{{ __('admin.email') }}:</strong> {{ $user->email }}</p>
            <p><strong>{{ __('admin.role') }}:</strong> {{ $user->role }}</p>
        </div>

        <!-- Cart section -->
        <div>
            <h3 class="text-lg font-semibold mb-2">{{ __('admin.cart') }}</h3>
            @if ($carts->count())
                <table class="min-w-full bg-white rounded shadow mx-auto">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b text-center">#</th>
                            <th class="py-2 px-4 border-b text-center">{{ __('admin.product_name') }}</th>
                            <th class="py-2 px-4 border-b text-center">{{ __('admin.quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $i => $cart)
                            <tr>
                                <td class="py-2 px-4 border-b text-center">{{ $i + 1 }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $cart->product->name ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $cart->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500">{{ __('admin.no_products_in_cart') }}.</p>
            @endif
        </div>

   <!-- Orders section -->
    <div>
        <h3 class="text-lg font-semibold mb-4">{{ __('admin.orders') }}</h3>
        @if ($orders->count())
            <div class="max-h-64 overflow-y-auto pr-2 space-y-3"> {{-- Thêm scroll --}}
                @foreach ($orders as $order)
                    <div 
                        onclick="window.location.href='{{ route('admin.order.show', $order->id) }}'" 
                        class="p-4 border rounded-lg shadow-sm bg-white hover:bg-gray-100 cursor-pointer transition duration-200 flex justify-between items-center"
                    >
                        <div>
                            <p class="font-medium text-gray-800">
                                {{ __('Order ID') }}: {{ $order->id }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ __('admin.total') }}: 
                                <span class="font-semibold text-green-600">
                                    ${{ number_format($order->total_price ?? 0, 2, '.', ',') }}
                                </span>
                            </p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">{{ __('admin.no_categories_found') }}</p>
        @endif
    </div>

@endsection
