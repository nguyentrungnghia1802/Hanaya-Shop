{{-- 
    Admin Dashboard View
    
    This is the main dashboard page for administrators of the Hanaya Shop e-commerce platform.
    It provides a comprehensive overview of the shop's performance including:
    - Key statistics (revenue, orders, products, users)
    - Revenue charts for the last 6 months
    - Order status distribution
    - Best-selling products table
    - Low stock products alerts
    - Recent orders listing
    
    The dashboard uses a responsive grid layout with Tailwind CSS for styling
    and Chart.js for data visualization.
--}}

@extends('layouts.admin')

{{-- Page header section with dashboard title --}}
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('admin.dashboard') }}
    </h2>
@endsection

{{-- Main dashboard content section --}}
@section('content')
    {{-- Main container with responsive padding --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Welcome Section - Hero banner with gradient background and today's date --}}
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ __("admin.welcome_message") }}</h1>
                        <p class="text-xl text-blue-100">{{ __("admin.manage_hanaya_shop") }}</p>
                        <p class="text-blue-100 mt-1">{{ now()->format('l, d F Y') }}</p>
                    </div>
                    {{-- Dashboard icon - hidden on mobile devices --}}
                    <div class="hidden md:block">
                        <svg class="w-24 h-24 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Key Statistics Cards - Primary metrics displayed in responsive grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Revenue Card - Displays monthly revenue with green accent --}}
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            {{-- Money/Dollar icon for revenue --}}
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('admin.monthly_revenue') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">${{ number_format($monthlyRevenue ?? 0, 2, '.', ',') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Orders Card - Displays total number of orders with blue accent --}}
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            {{-- Shopping bag icon for orders --}}
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('admin.total_orders') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $orderCount ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Products Card - Displays total number of products with yellow accent --}}
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            {{-- Cube/Package icon for products --}}
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('admin.total_products') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $productCount ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Users Card - Displays total number of users with purple accent --}}
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            {{-- Users/People icon for user count --}}
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('admin.total_users') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $userCount ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Secondary Statistics - Additional metrics in smaller cards arranged in a 6-column grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                {{-- Categories count - Total number of product categories --}}
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $categoryCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.category') }}</div>
                </div>
                {{-- In stock products - Products currently available for purchase --}}
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $activeProducts ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.in_stock') }}</div>
                </div>
                {{-- Out of stock products - Products that need restocking --}}
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $outOfStockProducts ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.out_of_stock') }}</div>
                </div>
                {{-- Posts count - Total number of blog posts or content --}}
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $postCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.posts') }}</div>
                </div>
                {{-- New users this month - Recent user registrations --}}
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-indigo-600">{{ $newUsersThisMonth ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.new_users_this_month') }}</div>
                </div>
                {{-- Total revenue - Cumulative revenue from all orders --}}
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">${{ number_format($totalRevenue ?? 0, 2, '.', ',') }}</div>
                    <div class="text-sm text-gray-600">{{ __('admin.total_revenue') }}</div>
                </div>
            </div>

            {{-- Charts and Details Row - Two-column layout for data visualization --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Revenue Chart Section - Bar chart showing monthly revenue trends --}}
                {{-- Displays revenue data for the last 6 months with Chart.js visualization --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    {{-- Chart title with semantic styling --}}
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.revenue_chart') }}</h3>
                    {{-- Canvas element for Chart.js bar chart rendering --}}
                    <canvas id="revenueBarChart" width="400" height="200"></canvas>
                    {{-- Chart.js library import from CDN --}}
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        // Initialize chart when DOM is fully loaded
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get canvas element for chart rendering
                            const ctx = document.getElementById('revenueBarChart');
                            // Parse revenue data from Laravel backend (JSON encoded)
                            const revenueData = @json($monthlyRevenueChart ?? []);
                            // Validate canvas element and data availability
                            if (!ctx || !Array.isArray(revenueData) || revenueData.length === 0) {
                                // Display fallback message if no data available
                                if (ctx) ctx.getContext('2d').fillText('No data available', 50, 50);
                                return;
                            }
                            // Create new Chart.js bar chart instance
                            new Chart(ctx.getContext('2d'), {
                                type: 'bar', // Bar chart type for revenue visualization
                                data: {
                                    // Extract month labels from revenue data
                                    labels: revenueData.map(item => item.month || 'N/A'),
                                    datasets: [{
                                        label: 'Revenue', // Dataset label
                                        // Extract revenue values from data array
                                        data: revenueData.map(item => item.revenue || 0),
                                        backgroundColor: 'rgba(59,130,246,0.7)' // Blue bar color with transparency
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: { display: false } // Hide legend to save space
                                    },
                                    scales: {
                                        y: { beginAtZero: true } // Start Y-axis from zero for accurate representation
                                    }
                                }
                            });
                        });
                    </script>
                </div>

                {{-- Order Status Distribution Section - Visual breakdown of order statuses --}}
                {{-- Shows percentage distribution of orders across different status categories --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    {{-- Section title --}}
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.order_status_distribution') }}</h3>
                    {{-- Container for status items with vertical spacing --}}
                    <div class="space-y-3">
                        {{-- Check if order status statistics are available --}}
                        @if(isset($orderStatusStats) && count($orderStatusStats) > 0)
                            {{-- Loop through each order status and display statistics --}}
                            @foreach($orderStatusStats as $status => $count)
                                {{-- Status name and count display row --}}
                                <div class="flex items-center justify-between">
                                    {{-- Status label with proper capitalization --}}
                                    <span class="text-sm font-medium text-gray-700 capitalize">
                                        {{-- Convert status codes to readable labels --}}
                                        @switch($status)
                                            @case('pending')
                                                {{ __('admin.pending') }} {{-- Orders awaiting processing --}}
                                                @break
                                            @case('processing')
                                                {{ __('admin.processing') }} {{-- Orders being prepared --}}
                                                @break
                                            @case('completed')
                                                {{ __('admin.completed') }} {{-- Successfully fulfilled orders --}}
                                                @break
                                            @case('cancelled')
                                                {{ __('admin.cancelled') }} {{-- Cancelled orders --}}
                                                @break
                                            @case('shipped')
                                                {{ __('admin.shipped') }} {{-- Orders sent to customers --}}
                                                @break
                                            @default
                                                {{ ucfirst($status) }} {{-- Fallback for unknown statuses --}}
                                        @endswitch
                                    </span>
                                    {{-- Order count for this status --}}
                                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                                </div>
                                {{-- Progress bar container --}}
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    {{-- Calculate percentage of total orders --}}
                                    @php $percentage = $orderCount > 0 ? ($count / $orderCount) * 100 : 0 @endphp
                                    {{-- Progress bar with status-specific color coding --}}
                                    <div class="h-2 rounded-full 
                                        @if($status === 'completed') bg-green-500{{-- Green for completed orders --}}
                                        @elseif($status === 'pending') bg-yellow-500{{-- Yellow for pending orders --}}
                                        @elseif($status === 'processing') bg-blue-500{{-- Blue for processing orders --}}
                                        @else bg-red-500 @endif" {{-- Red for cancelled/problem orders --}}
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            @endforeach
                        @else
                            {{-- Fallback message when no order data is available --}}
                            <p class="text-gray-500 text-center">{{ __('admin.no_orders_yet') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tables Row - Two-column layout for product and inventory data --}}
            {{-- Contains best selling products and low stock alerts in table format --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Best Selling Products Table - Shows top performing products by sales volume --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    {{-- Table title --}}
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.best_selling_products') }}</h3>
                    {{-- Responsive table container for horizontal scrolling on mobile --}}
                    <div class="overflow-x-auto">
                        {{-- Products table with automatic column sizing --}}
                        <table class="min-w-full table-auto">
                            {{-- Table header with gray background --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- Product name column header --}}
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">{{ __('admin.product') }}</th>
                                    {{-- Quantity sold column header --}}
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">{{ __('admin.sold') }}</th>
                                </tr>
                            </thead>
                            {{-- Table body with row dividers --}}
                            <tbody class="divide-y divide-gray-200">
                                {{-- Check if best selling products data exists --}}
                                @if(isset($bestSellingProducts) && $bestSellingProducts->count() > 0)
                                    {{-- Loop through each best selling product --}}
                                    @foreach($bestSellingProducts as $product)
                                        <tr>
                                            {{-- Product name cell (truncated to 30 characters) --}}
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ Str::limit($product->name, 30) }}</td>
                                            {{-- Quantity sold cell with green accent color --}}
                                            <td class="px-4 py-2 text-sm font-semibold text-green-600">{{ $product->total_sold }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    {{-- Fallback row when no sales data is available --}}
                                    <tr>
                                        <td colspan="2" class="px-4 py-4 text-center text-gray-500">{{ __('admin.no_data_available') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Low Stock Products Table - Inventory alert system for products running low --}}
                <div class="bg-white rounded-lg shadow-lg p-6">
                    {{-- Table title --}}
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.low_stock_products') }}</h3>
                    {{-- Responsive table container --}}
                    <div class="overflow-x-auto">
                        {{-- Inventory table with automatic column sizing --}}
                        <table class="min-w-full table-auto">
                            {{-- Table header --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- Product name column header --}}
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">{{ __('admin.product') }}</th>
                                    {{-- Stock quantity column header --}}
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">{{ __('admin.remaining') }}</th>
                                </tr>
                            </thead>
                            {{-- Table body with row dividers --}}
                            <tbody class="divide-y divide-gray-200">
                                {{-- Check if low stock products data exists --}}
                                @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                                    {{-- Loop through each low stock product --}}
                                    @foreach($lowStockProducts as $product)
                                        <tr>
                                            {{-- Product name cell (truncated to 30 characters for table layout) --}}
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ Str::limit($product->name, 30) }}</td>
                                            {{-- Stock quantity cell with color-coded warning levels --}}
                                            <td class="px-4 py-2 text-sm font-semibold 
                                                @if($product->stock_quantity <= 5) text-red-600 {{-- Critical stock level (≤5) in red --}}
                                                @else text-orange-600 @endif">{{-- Low stock level (>5) in orange --}}
                                                {{ $product->stock_quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    {{-- Fallback message when all products are adequately stocked --}}
                                    <tr>
                                        <td colspan="2" class="px-4 py-4 text-center text-gray-500">{{ __('admin.all_products_are_well_stocked') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Recent Orders Section - Table showing latest customer orders --}}
            {{-- Provides overview of recent transaction activity and order management --}}
            <div class="bg-white rounded-lg shadow-lg p-6">
                {{-- Section title --}}
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('admin.recent_orders') }}</h3>
                {{-- Responsive table container for horizontal scrolling --}}
                <div class="overflow-x-auto">
                    {{-- Orders table with full width and automatic column sizing --}}
                    <table class="min-w-full table-auto">
                        {{-- Table header with light gray background --}}
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- Order ID column header --}}
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                                {{-- Customer name column header --}}
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">{{ __('admin.customer') }}</th>
                                {{-- Order total amount column header --}}
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">{{ __('admin.total') }}</th>
                                {{-- Order status column header --}}
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">{{ __('admin.status') }}</th>
                                {{-- Order date column header --}}
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">{{ __('admin.order_date') }}</th>
                            </tr>
                        </thead>
                        {{-- Table body with row dividers for visual separation --}}
                        <tbody class="divide-y divide-gray-200">
                            {{-- Check if recent orders data exists --}}
                            @if(isset($recentOrders) && $recentOrders->count() > 0)
                                {{-- Loop through each recent order --}}
                                @foreach($recentOrders as $order)
                                    <tr>
                                        {{-- Order ID cell with hash prefix --}}
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                        {{-- Customer name cell with fallback to 'Guest' --}}
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $order->user->name ?? 'Guest' }}</td>
                                        {{-- Order total with currency formatting and green accent --}}
                                        <td class="px-4 py-2 text-sm font-semibold text-green-600">${{ number_format($order->total_amount, 2, '.', ',') }}</td>
                                        {{-- Order status cell with colored badge --}}
                                        <td class="px-4 py-2">
                                            {{-- Status badge with conditional background and text colors --}}
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($order->status === 'completed') bg-green-100 text-green-800{{-- Green for completed orders --}}
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800{{-- Yellow for pending orders --}}
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800{{-- Blue for processing orders --}}
                                                @else bg-red-100 text-red-800 @endif">{{-- Red for cancelled/problem orders --}}
                                                {{-- Status text with proper capitalization --}}
                                                @switch($order->status)
                                                    @case('pending')
                                                        {{ __('admin.pending') }} {{-- Order awaiting processing --}}
                                                        @break
                                                    @case('processing')
                                                        {{ __('admin.processing') }} {{-- Order being prepared --}}
                                                        @break
                                                    @case('shgipped')
                                                        {{ __('admin.shipped') }} {{-- Order has been shipped --}}
                                                        @break
                                                    @case('completed')
                                                        {{ __('admin.completed') }} {{-- Order successfully fulfilled --}}
                                                        @break
                                                    @case('cancelled')
                                                        {{ __('admin.cancelled') }} {{-- Order was cancelled --}}
                                                        @break
                                                    @default
                                                        {{ ucfirst($order->status) }} {{-- Fallback for unknown statuses --}}
                                                @endswitch
                                            </span>
                                        </td>
                                        {{-- Order creation date with formatted display --}}
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                {{-- Fallback row when no recent orders exist --}}
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">{{ __('admin.no_orders_yet') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

{{-- End of admin dashboard view section --}}
@endsection
