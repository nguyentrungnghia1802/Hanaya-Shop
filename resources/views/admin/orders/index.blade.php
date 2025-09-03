@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('admin.orders') }}
    </h2>
@endsection

@section('content')
    <!-- Thông báo lỗi -->
    <x-alert />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             
    {{-- Status navigation --}}
            <div class="flex flex-wrap border-b border-gray-200">
        @php
            $statuses = [
                '' => __('admin.all'),
                'pending' =>  __('admin.pending'),
                'processing' =>  __('admin.processing'),
                'shipped' =>  __('admin.shipped'),
                'cancelled' =>  __('admin.cancelled'),
                'completed' =>  __('admin.completed')
            ];
            $currentStatus = request('status');
        @endphp

        @foreach ($statuses as $key => $label)
            <a href="{{ route('admin.order', ['status' => $key]) }}"
                class="px-4 py-2 text-sm font-medium transition border-b-2
                    {{ $currentStatus === $key || ($key === '' && $currentStatus === null)
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-300' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Search input --}}
                    <form id="categorySearchForm" method="GET" class="flex gap-2 mb-4 max-w-sm">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search_order') }}"
                            class="border px-3 py-2 rounded w-full" autocomplete="off">
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 rounded">{{ __('admin.search') }}</button>
                    </form>


                    <!-- Category list table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border border-gray-300 text-sm">
                            <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                                <tr>
                                    <th class="px-2 sm:px-4 py-2 border-b">#</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.order_id') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.user') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.total_price') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.order_at') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.status') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.payment_status') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800">
                                @foreach ($order as $index => $item)
                                    @php
                                        $matchedPayment = $payment->firstWhere('order_id', $item->id);
                                        if($matchedPayment && !$matchedPayment->payment_status) {
                                            $matchedPayment->payment_status = 'N/A';
                                        }

                                         $isAllFilter = request('status') === null || request('status') === '';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-2 border-b">{{ $order->firstItem() + $index }}</td>
                                        <td class="px-4 py-2 border-b font-semibold text-blue-600">#{{ $item->id }}</td>
                                        <td class="px-4 py-2 border-b">{{ $item->user->name ?? 'Unknown' }} (ID: {{ $item->user_id }})</td>
                                        <td class="px-4 py-2 border-b">{{ $item->total_price }}</td>
                                        <td class="px-4 py-2 border-b">{{ $item->created_at }}</td>
                                        <td class="px-4 py-2 border-b">{{ $item->status }}</td>
                                        <td class="px-4 py-2 border-b">
                                            {{ $matchedPayment ? $matchedPayment->payment_status : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 border-b space-x-2">

                                        @if ($isAllFilter)
                                    
                                            <a href="{{ route('admin.order.show', $item->id) }}"
                                                class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                {{ __('admin.view_details') }}
                                            </a>
                                        @else
                                            @if($item->status === 'pending')
                                                <a href="{{ route('admin.order.show', $item->id) }}"
                                                    class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                    {{ __('admin.view_details') }}
                                                </a>

                                                <form action="{{ route('admin.order.confirm', $item->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-gray-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                        {{ __('admin.confirm') }}
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.orders.cancel', $item->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                        {{ __('admin.cancel') }}
                                                    </button>
                                                </form>
                                            @elseif($item->status === 'processing')
                                                <a href="{{ route('admin.order.show', $item->id) }}"
                                                    class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                    {{ __('admin.view_details') }}
                                                </a>

                                                <form action="{{ route('admin.order.shipped', $item->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-purple-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                        {{ __('admin.shipped') }}
                                                    </button>
                                                </form>

                                                @if ($matchedPayment && $matchedPayment->payment_status === 'pending')
                                                    <form action="{{ route('admin.order.paid', $item->id) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="inline-block px-3 py-1 bg-pink-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                            {{ __('admin.paid') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button"
                                                        class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
                                                        disabled>
                                                        {{ __('admin.paid') }}
                                                    </button>
                                                @endif
                                            @elseif($item->status === 'shipped')
                                                <a href="{{ route('admin.order.show', $item->id) }}"
                                                    class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                    {{ __('admin.view_details') }}
                                                </a>
                                                @if($matchedPayment && $matchedPayment->payment_status === 'pending')
                                                <form action="{{ route('admin.order.paid', $item->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-block px-3 py-1 bg-pink-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition">
                                                        {{ __('admin.paid') }}
                                                    </button>
                                                </form>
                                                @else
                                                    <button type="button"
                                                        class="inline-block px-3 py-1 bg-gray-300 text-white text-xs font-medium rounded cursor-not-allowed"
                                                        disabled>
                                                        {{ __('admin.paid') }}
                                                    </button>
                                                @endif
                                            @elseif($item->status === 'completed')
                                                <a href="{{ route('admin.order.show', $item->id) }}"
                                                    class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                    {{ __('admin.view_details') }}
                                                </a>
                                            @elseif($item->status === 'cancelled')
                                                <a href="{{ route('admin.order.show', $item->id) }}"
                                                    class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                    {{ __('admin.view_details') }}
                                                </a>
                                            @endif
                                                
                                        @endif
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <div class="mt-8 flex justify-center">
                            {{ $order->links() }}
                        </div>
                    </div>

                    {{-- Pagination (nếu có) --}}
                    <div class="mt-6 flex justify-center">
                        {{-- {{ $categories->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
