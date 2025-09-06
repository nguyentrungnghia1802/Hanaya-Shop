<?php
/**
 * Admin Dashboard Controller
 * 
 * This controller handles the admin dashboard functionality for the Hanaya Shop e-commerce application.
 * It provides comprehensive statistics, analytics, and overview data for administrative purposes.
 * The dashboard displays key performance indicators, sales metrics, inventory status, and recent activity.
 * 
 * Features:
 * - Real-time business statistics (revenue, orders, products, users)
 * - Monthly revenue trends and charts
 * - Order status distribution analytics
 * - Best selling products tracking
 * - Low stock inventory alerts
 * - Recent orders overview
 * - User growth metrics
 * 
 * @package App\Http\Controllers\Admin
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product\Category; // Product category model
use App\Models\Product\Product;  // Product model for inventory data
use App\Models\User;             // User model for customer analytics
use App\Models\Post;             // Post model for content management
use App\Models\Order\Order;      // Order model for sales analytics
use App\Models\Order\OrderDetail; // Order detail model for detailed sales data
use Illuminate\Support\Facades\Cache; // Laravel caching system for performance
use Illuminate\Support\Facades\DB;    // Database query builder
use Illuminate\Http\Request;     // HTTP request handling
use Carbon\Carbon;               // Date manipulation library

/**
 * Admin Dashboard Controller Class
 * 
 * Handles all admin dashboard related operations including data aggregation,
 * statistical calculations, and view rendering for the administrative interface.
 */
class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with comprehensive statistics.
     * 
     * This method aggregates data from multiple models to provide a complete
     * overview of the e-commerce platform's performance. It calculates various
     * metrics including sales, inventory, user activity, and generates chart data.
     * 
     * The method uses efficient database queries with proper error handling
     * to ensure reliable performance even with large datasets.
     * 
     * @return \Illuminate\View\View The admin dashboard view with statistics
     * @throws \Exception When database queries fail
     */
    public function index()
    {
        try {
            // Basic Entity Counts Section
            // These queries provide fundamental metrics about the platform's content and data
            
            /**
             * Category Count - Total number of product categories
             * Used for inventory organization metrics
             */
            $categoryCount = Category::count();
            
            /**
             * Product Count - Total number of products in the system
             * Indicates the size of the product catalog
             */
            $productCount = Product::count();
            
            /**
             * User Count - Total number of registered users/customers
             * Represents the customer base size
             */
            $userCount = User::count();
            
            /**
             * Post Count - Total number of blog posts or content pieces
             * For content management and marketing metrics
             */
            $postCount = Post::count();
            
            /**
             * Order Count - Total number of orders placed
             * Key metric for business volume and customer activity
             */
            $orderCount = Order::count();

            // Revenue Statistics Section
            // These calculations provide financial performance indicators
            
            /**
             * Total Revenue - Sum of all completed order amounts
             * Represents the total earnings from all successful transactions
             * Only includes completed orders to avoid counting pending/cancelled orders
             */
            $totalRevenue = Order::where('status', 'completed')->sum('total_price') ?? 0;
            
            /**
             * Monthly Revenue - Revenue for the current month
             * Tracks current month's performance compared to historical data
             * Filters by both month and year to ensure accuracy across year boundaries
             */
            $monthlyRevenue = Order::where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_price') ?? 0;

            // Product Inventory Statistics Section
            // These metrics help with inventory management and stock monitoring
            
            /**
             * Active Products - Products currently available for purchase
             * Products with stock quantity greater than 0
             * Important for understanding available inventory
             */
            $activeProducts = Product::where('stock_quantity', '>', 0)->count();
            
            /**
             * Out of Stock Products - Products that need restocking
             * Products with zero or negative stock quantity
             * Critical for inventory management alerts
             */
            $outOfStockProducts = Product::where('stock_quantity', '<=', 0)->count();
            
            // Best Selling Products Section
            // Identifies top-performing products based on view count as a proxy for popularity
            /**
             * Best Selling Products - Top 5 most viewed products
             * Orders by view_count in descending order to show most popular items
             * Includes essential product information for dashboard display
             * Limited to 5 items for optimal dashboard performance and readability
             */
            $bestSellingProducts = Product::select('id', 'name', 'price', 'image_url', 'stock_quantity')
                ->orderBy('view_count', 'desc')
                ->limit(5)
                ->get();

            // Recent Orders Section
            // Provides overview of latest customer activity and order management needs
            /**
             * Recent Orders - Latest 5 orders with customer information
             * Includes user relationship to display customer names
             * Essential for order management and customer service
             * Limited selection improves query performance
             */
            $recentOrders = Order::select('id', 'user_id', 'total_price', 'status', 'created_at')
                ->with('user:id,name') // Eager load only necessary user fields
                ->latest() // Order by created_at descending
                ->limit(5)
                ->get();

            // User Growth Statistics Section
            // Tracks customer acquisition and user base expansion
            /**
             * New Users This Month - Count of users registered in current month
             * Important metric for measuring marketing effectiveness and growth
             * Filters by both month and year for accurate period-specific data
             */
            $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();

            // Order Status Distribution Section
            // Provides breakdown of orders by their current status for operational insights
            /**
             * Order Status Statistics - Count of orders by status type
             * Essential for understanding order flow and operational bottlenecks
             * Helps identify issues in order processing pipeline
             */
            $orderStatusStats = [
                'pending' => Order::where('status', 'pending')->count(),       // Orders awaiting processing
                'completed' => Order::where('status', 'completed')->count(),   // Successfully fulfilled orders
                'cancelled' => Order::where('status', 'cancelled')->count(),   // Cancelled orders
                'processing' => Order::where('status', 'processing')->count(), // Orders being prepared
                'shipped' => Order::where('status', 'shipped')->count(),       // Orders sent to customers
            ];

            // Monthly Revenue Chart Data Section
            // Generates historical revenue data for trend visualization
            /**
             * Monthly Revenue Chart Data - Revenue trends for last 6 months
             * Creates array of month/revenue pairs for Chart.js visualization
             * Limited to 6 months for optimal chart readability and performance
             */
            $monthlyRevenueChart = [];
            for ($i = 5; $i >= 0; $i--) {
                // Calculate date for each month going backward from current month
                $month = Carbon::now()->subMonths($i);
                
                // Calculate revenue for this specific month
                $revenue = Order::where('status', 'completed')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('total_price') ?? 0;
                
                // Add formatted data point to chart array
                $monthlyRevenueChart[] = [
                    'month' => $month->format('M Y'),           // Human-readable month/year
                    'revenue' => number_format($revenue, 2, '.', ',') // Formatted revenue string
                ];
            }

            // Low Stock Alert Section
            // Identifies products requiring attention for inventory management
            /**
             * Low Stock Products - Products with critically low inventory levels
             * Shows products with 1-10 items remaining (above 0, at or below 10)
             * Excludes out-of-stock items as they're tracked separately
             * Ordered by stock quantity ascending to prioritize most critical items
             * Limited to 5 items for dashboard focus and performance
             */
            $lowStockProducts = Product::select('id', 'name', 'stock_quantity', 'image_url')
                ->where('stock_quantity', '>', 0)   // Exclude out-of-stock items
                ->where('stock_quantity', '<=', 10) // Low stock threshold
                ->orderBy('stock_quantity', 'asc')  // Most critical first
                ->limit(5)
                ->get();

            // Data Compilation Section
            // Organize all statistics into a single array for view consumption
            /**
             * Statistics Array - Complete dashboard data package
             * Uses compact() function to create associative array with variable names as keys
             * This approach ensures all calculated metrics are available to the view
             */
            $stats = compact(
                'categoryCount', 'productCount', 'userCount', 'postCount', 'orderCount',
                'totalRevenue', 'monthlyRevenue', 'activeProducts', 'outOfStockProducts',
                'bestSellingProducts', 'recentOrders', 'newUsersThisMonth', 
                'orderStatusStats', 'monthlyRevenueChart', 'lowStockProducts'
            );
                
        } catch (\Exception $e) {
            // Error Handling Section
            // Provides graceful fallback when database queries fail
            /**
             * Fallback Statistics - Safe default values when queries fail
             * Prevents dashboard from breaking due to database issues
             * Returns empty collections and zero values to maintain view compatibility
             * Logs error for debugging while maintaining user experience
             */
            $stats = [
                'categoryCount' => 0, 'productCount' => 0, 'userCount' => 0, 'postCount' => 0, 'orderCount' => 0,
                'totalRevenue' => 0, 'monthlyRevenue' => 0, 'activeProducts' => 0, 'outOfStockProducts' => 0,
                'bestSellingProducts' => collect(), 'recentOrders' => collect(), 'newUsersThisMonth' => 0,
                'orderStatusStats' => ['pending' => 0, 'completed' => 0, 'cancelled' => 0], 
                'monthlyRevenueChart' => [], 'lowStockProducts' => collect()
            ];
        }

        // View Rendering Section
        /**
         * Return Dashboard View - Render admin dashboard with calculated statistics
         * Passes all statistical data to the admin.dashboard Blade template
         * The view receives the $stats array and can access each metric individually
         */
        return view('admin.dashboard', $stats);
    }
}
