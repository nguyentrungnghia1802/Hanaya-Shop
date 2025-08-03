@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Admin Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ __("Welcome back!") }}</h1>
                        <p class="text-xl text-blue-100">{{ __("Manage Hanaya Shop") }}</p>
                        <p class="text-blue-100 mt-1">{{ now()->format('l, d F Y') }}</p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-24 h-24 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Key Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Revenue Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Monthly Revenue</dt>
                                <dd class="text-lg font-medium text-gray-900">${{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $orderCount ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $productCount ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $userCount ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $categoryCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Categories</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $activeProducts ?? 0 }}</div>
                    <div class="text-sm text-gray-600">In Stock</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $outOfStockProducts ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Out of Stock</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $postCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Posts</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-indigo-600">{{ $newUsersThisMonth ?? 0 }}</div>
                    <div class="text-sm text-gray-600">New Users/Month</div>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">${{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-600">Total Revenue</div>
                </div>
            </div>

            <!-- Charts and Details Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Revenue Chart -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Chart - Last 6 Months</h3>
                    <canvas id="revenueBarChart" width="400" height="200"></canvas>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('revenueBarChart');
                            const revenueData = @json($monthlyRevenueChart ?? []);
                            if (!ctx || !Array.isArray(revenueData) || revenueData.length === 0) {
                                if (ctx) ctx.getContext('2d').fillText('No data available', 50, 50);
                                return;
                            }
                            new Chart(ctx.getContext('2d'), {
                                type: 'bar',
                                data: {
                                    labels: revenueData.map(item => item.month || 'N/A'),
                                    datasets: [{
                                        label: 'Revenue',
                                        data: revenueData.map(item => item.revenue || 0),
                                        backgroundColor: 'rgba(59,130,246,0.7)'
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: { display: false }
                                    },
                                    scales: {
                                        y: { beginAtZero: true }
                                    }
                                }
                            });
                        });
                    </script>
                </div>

                <!-- Order Status Distribution -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status Distribution</h3>
                    <div class="space-y-3">
                        @if(isset($orderStatusStats) && count($orderStatusStats) > 0)
                            @foreach($orderStatusStats as $status => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 capitalize">
                                        @switch($status)
                                            @case('pending')
                                                Pending
                                                @break
                                            @case('processing')
                                                Processing
                                                @break
                                            @case('completed')
                                                Completed
                                                @break
                                            @case('cancelled')
                                                Cancelled
                                                @break
                                            @case('shipped')
                                                Shipped
                                                @break
                                            @default
                                                {{ ucfirst($status) }}
                                        @endswitch
                                    </span>
                                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @php $percentage = $orderCount > 0 ? ($count / $orderCount) * 100 : 0 @endphp
                                    <div class="h-2 rounded-full 
                                        @if($status === 'completed') bg-green-500
                                        @elseif($status === 'pending') bg-yellow-500
                                        @elseif($status === 'processing') bg-blue-500
                                        @else bg-red-500 @endif" 
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-center">No orders yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Best Selling Products -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Best Selling Products</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Product</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Sold</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @if(isset($bestSellingProducts) && $bestSellingProducts->count() > 0)
                                    @foreach($bestSellingProducts as $product)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ Str::limit($product->name, 30) }}</td>
                                            <td class="px-4 py-2 text-sm font-semibold text-green-600">{{ $product->total_sold }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="px-4 py-4 text-center text-gray-500">No data available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Low Stock Products -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Low Stock Products</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Product</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Remaining</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                                    @foreach($lowStockProducts as $product)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ Str::limit($product->name, 30) }}</td>
                                            <td class="px-4 py-2 text-sm font-semibold 
                                                @if($product->stock_quantity <= 5) text-red-600 
                                                @else text-orange-600 @endif">
                                                {{ $product->stock_quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="px-4 py-4 text-center text-gray-500">All products are well stocked</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Orders</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Customer</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Total</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Order Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if(isset($recentOrders) && $recentOrders->count() > 0)
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $order->user->name ?? 'Guest' }}</td>
                                        <td class="px-4 py-2 text-sm font-semibold text-green-600">${{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                @switch($order->status)
                                                    @case('pending')
                                                        Pending
                                                        @break
                                                    @case('processing')
                                                        Processing
                                                        @break
                                                    @case('completed')
                                                        Completed
                                                        @break
                                                    @case('cancelled')
                                                        Cancelled
                                                        @break
                                                    @default
                                                        {{ ucfirst($order->status) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No orders yet</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
