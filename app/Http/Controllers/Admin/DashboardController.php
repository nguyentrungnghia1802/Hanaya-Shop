<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\User;
use App\Models\Post;
use App\Models\Order\Order;
use App\Models\Order\OrderDetail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with comprehensive statistics.
     */
    public function index()
    {
        try {
            // Basic counts with efficient queries
            $categoryCount = Category::count();
            $productCount = Product::count();
            $userCount = User::count();
            $postCount = Post::count();
            $orderCount = Order::count();

            // Revenue statistics - simplified and safe
            $totalRevenue = Order::where('status', 'completed')->sum('total_price') ?? 0;
            $monthlyRevenue = Order::where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_price') ?? 0;

            // Product statistics
            $activeProducts = Product::where('stock_quantity', '>', 0)->count();
            $outOfStockProducts = Product::where('stock_quantity', '<=', 0)->count();
            
            // Best selling products - limit and optimize
            $bestSellingProducts = Product::select('id', 'name', 'price', 'image_url', 'stock_quantity')
                ->orderBy('view_count', 'desc')
                ->limit(5)
                ->get();

            // Recent orders - simplified with user names only
            $recentOrders = Order::select('id', 'user_id', 'total_price', 'status', 'created_at')
                ->with('user:id,name')
                ->latest()
                ->limit(5)
                ->get();

            // User statistics
            $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();

            // Order status distribution - simplified
            $orderStatusStats = [
                'pending' => Order::where('status', 'pending')->count(),
                'completed' => Order::where('status', 'completed')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'shipped' => Order::where('status', 'shipped')->count(),
            ];

            // Monthly revenue for chart - last 6 months only
            $monthlyRevenueChart = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $revenue = Order::where('status', 'completed')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('total_price') ?? 0;
                $monthlyRevenueChart[] = [
                    'month' => $month->format('M Y'),
                    'revenue' => number_format($revenue, 0, ',', '.') // Format as string to prevent overflow
                ];
            }

            // Low stock products
            $lowStockProducts = Product::select('id', 'name', 'stock_quantity', 'image_url')
                ->where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<=', 10)
                ->orderBy('stock_quantity', 'asc')
                ->limit(5)
                ->get();

            $stats = compact(
                'categoryCount', 'productCount', 'userCount', 'postCount', 'orderCount',
                'totalRevenue', 'monthlyRevenue', 'activeProducts', 'outOfStockProducts',
                'bestSellingProducts', 'recentOrders', 'newUsersThisMonth', 
                'orderStatusStats', 'monthlyRevenueChart', 'lowStockProducts'
            );
                
        } catch (\Exception $e) {
            // Fallback data if queries fail
            $stats = [
                'categoryCount' => 0, 'productCount' => 0, 'userCount' => 0, 'postCount' => 0, 'orderCount' => 0,
                'totalRevenue' => 0, 'monthlyRevenue' => 0, 'activeProducts' => 0, 'outOfStockProducts' => 0,
                'bestSellingProducts' => collect(), 'recentOrders' => collect(), 'newUsersThisMonth' => 0,
                'orderStatusStats' => ['pending' => 0, 'completed' => 0, 'cancelled' => 0], 
                'monthlyRevenueChart' => [], 'lowStockProducts' => collect()
            ];
        }

        // Pass the statistics to the dashboard view
        return view('admin.dashboard', $stats);
    }
}
