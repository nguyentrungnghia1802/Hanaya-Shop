<?php
/**
 * User Dashboard Controller
 * 
 * This controller manages the main customer-facing dashboard for the Hanaya Shop
 * e-commerce application. It provides a comprehensive overview of products, categories,
 * and content to create an engaging homepage experience for customers.
 * 
 * Key Features:
 * - Homepage product displays with multiple sorting options
 * - Category-based product organization
 * - Caching system for improved performance
 * - Top seller identification and promotion
 * - Latest products and sale items highlighting
 * - Blog integration for content marketing
 * - Banner management for promotional content
 * 
 * Performance Optimization:
 * - Strategic caching with time-based invalidation
 * - Efficient database queries with eager loading
 * - Optimized product retrieval with minimal queries
 * - Category-specific product filtering
 * 
 * Dashboard Sections:
 * - Top selling products (based on order data)
 * - Latest products by category
 * - Recently added products
 * - Products on sale (with discounts)
 * - Most viewed products (popularity-based)
 * - Category listing with product counts
 * - Latest blog posts for engagement
 * - Promotional banners for marketing
 * 
 * Business Value:
 * - Drives product discovery and sales
 * - Improves user engagement and time on site
 * - Showcases popular and trending items
 * - Promotes sale items and special offers
 * - Integrates content marketing with e-commerce
 * 
 * @package App\Http\Controllers\User
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;         // Base controller
use Illuminate\Http\Request;                 // HTTP request handling
use App\Models\Product\Product;              // Product model for product data
use App\Models\Product\Category;             // Category model for organization
use App\Models\Post;                         // Post model for blog content
use Illuminate\Support\Facades\DB;           // Database query builder
use Illuminate\Support\Facades\Cache;        // Caching system for performance

/**
 * User Dashboard Controller Class
 * 
 * Manages the customer homepage with comprehensive product displays,
 * category organization, and content integration. Provides optimized
 * data retrieval with caching for enhanced user experience.
 */
class DashboardController extends Controller
{
    /**
     * Dashboard Index - Main Homepage Display
     * 
     * Renders the main customer dashboard with comprehensive product collections,
     * category information, and promotional content. Implements strategic caching
     * for optimal performance and user experience.
     * 
     * Dashboard Components:
     * - Top selling products based on order history
     * - Latest products organized by category
     * - Recently added products
     * - Sale items with discount highlighting
     * - Most viewed products for popularity trends
     * - Category listing with product counts
     * - Latest blog posts for content engagement
     * - Promotional banners for marketing campaigns
     * 
     * Performance Features:
     * - Hour-based cache invalidation for data freshness
     * - Efficient database queries with relationship loading
     * - Optimized product collection retrieval
     * - Strategic data aggregation and grouping
     * 
     * @return \Illuminate\View\View Dashboard view with comprehensive product and content data
     */
    public function index() 
    {
        // Cache Strategy Implementation
        /**
         * Dashboard Data Caching - Performance optimization with time-based cache
         * 
         * Cache Key Strategy:
         * - Includes date and hour for automatic hourly refresh
         * - Ensures fresh data while maintaining performance
         * - Balances data accuracy with loading speed
         * 
         * Cache Duration: 30 minutes for optimal balance
         */
        $cacheKey = 'dashboard_data_' . date('Y-m-d-H');
        
        // Cached Data Retrieval
        /**
         * Data Collection and Caching - Aggregate dashboard data with caching
         * 
         * Cached Components:
         * - Top seller products with sales data
         * - Latest products by category organization
         * - Recently added products
         * - Sale items with discount information
         * - Most viewed products for popularity
         * - Category listings with product counts
         * - Latest blog posts for content marketing
         * 
         * Cache Benefits:
         * - Reduces database load for frequent homepage visits
         * - Improves page load times significantly
         * - Maintains data consistency across requests
         */
        $data = Cache::remember($cacheKey, now()->addMinutes(30), function () {
            return [
                'topSeller' => $this->getTopSellerProducts(),           // Best-selling products
                'latestByCategory' => $this->getLatestByCategory(),     // Category-organized products
                'latest' => Product::with('reviews')->latest()->take(8)->get(), // Recently added products
                'onSale' => Product::with('reviews')->where('discount_percent', '>', 0) // Discounted products
                    ->orderByDesc('discount_percent')
                    ->take(8)
                    ->get(),
                'mostViewed' => Product::with('reviews')->orderByDesc('view_count') // Popular products
                    ->take(8)
                    ->get(),
                'categories' => Category::withCount('product')->get(),  // Category listing
                'latestPosts' => Post::where('status', true)            // Blog content
                    ->with('author')
                    ->latest()
                    ->take(3)
                    ->get(),
            ];
        });

        // Banner Configuration
        /**
         * Promotional Banner Integration - Marketing content display
         * 
         * Banner system enables:
         * - Promotional campaign display
         * - Seasonal marketing content
         * - Special offer highlighting
         * - Brand message communication
         */
        $banners = config('constants.banners');
        
        // View Rendering
        /**
         * Dashboard View Rendering - Compile all data for homepage display
         * 
         * View Data:
         * - Cached product collections and analytics
         * - Promotional banners for marketing
         * - Category and content information
         * - Optimized for customer engagement
         */
        return view('page.dashboard', array_merge($data, ['banners' => $banners]));
    }

    /**
     * Get Top Selling Products
     * 
     * Retrieves products with highest sales volume based on order history.
     * Uses aggregate queries to calculate total sold quantities and ranks
     * products by sales performance for promotional highlighting.
     * 
     * Query Features:
     * - Left join with order details for sales data
     * - Aggregate quantity calculation across all orders
     * - Handles products with no sales (COALESCE for zero default)
     * - Includes review relationships for rating display
     * 
     * Business Value:
     * - Highlights customer favorites
     * - Drives additional sales through social proof
     * - Identifies successful products for inventory management
     * - Supports marketing and promotional strategies
     * 
     * @return \Illuminate\Database\Eloquent\Collection Top 4 best-selling products with sales data
     */
    private function getTopSellerProducts()
    {
        // Top Seller Query Construction
        /**
         * Sales Performance Analysis - Calculate total sales per product
         * 
         * Query Components:
         * - Products with review relationships
         * - Left join with order_details for sales calculation
         * - COALESCE for zero-sales handling
         * - GROUP BY with all product columns for aggregation
         * - ORDER BY total sales descending
         */
        return Product::with('reviews')
            ->select('products.*', DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold'))
            ->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
            ->groupBy(
                'products.id', 'products.name', 'products.price', 'products.image_url', 
                'products.stock_quantity', 'products.category_id', 'products.descriptions', 
                'products.created_at', 'products.updated_at', 'products.discount_percent', 'products.view_count'
            )
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();
    }

    /**
     * Get Latest Products by Category
     * 
     * Organizes recent products by predefined category mappings to create
     * structured product displays for different customer interests.
     * Supports multilingual category names and flexible category matching.
     * 
     * Category Organization:
     * - Soap Flower: Handcrafted soap flower products
     * - Special Flower: Unique and premium flower arrangements
     * - Fresh Flowers: Natural fresh flower products
     * - Souvenir: Gift and memorable items
     * 
     * Features:
     * - Multilingual category name support
     * - Flexible category name matching
     * - Latest product prioritization within categories
     * - Minimum product threshold for display
     * 
     * @return array Organized array of categories with their latest products
     */
    private function getLatestByCategory()
    {
        // Category Mapping Configuration
        /**
         * Category Name Mapping - Support for multilingual category identification
         * 
         * Mapping Structure:
         * - English names for international customers
         * - Vietnamese names for local customers
         * - Lowercase variations for flexible matching
         * - Multiple name variations per category
         */
        $categoryMapping = [
            'soap-flower' => ['Soap Flower', 'Hoa sáp', 'soap flower'],
            'special-flower' => ['Special Flower', 'Hoa đặc biệt', 'special flower'],
            'fresh-flowers' => ['Fresh Flowers', 'Hoa tươi', 'fresh flowers'],
            'souvenir' => ['Souvenir', 'Quà lưu niệm', 'souvenir']
        ];

        $latestByCategory = [];
        
        // Category Processing Loop
        /**
         * Category Product Collection - Retrieve latest products for each category
         * 
         * Processing for each category:
         * - Search products by category name variations
         * - Include review relationships for rating display
         * - Order by creation date (latest first)
         * - Limit to 4 products per category
         * - Only include categories with available products
         */
        foreach ($categoryMapping as $key => $names) {
            $products = Product::with('reviews')->whereHas('category', function($q) use ($names) {
                $q->where(function($subQ) use ($names) {
                    foreach ($names as $name) {
                        $subQ->orWhere('name', 'like', "%$name%");
                    }
                });
            })
            ->latest()
            ->take(4)
            ->get();
            
            // Category Data Structure
            /**
             * Category Result Assembly - Build structured category data
             * 
             * Only includes categories with available products
             * Provides display name, products, and URL slug for navigation
             */
            if ($products->count() > 0) {
                $latestByCategory[$key] = [
                    'name' => $this->getCategoryDisplayName($key),  // Human-readable category name
                    'products' => $products,                        // Product collection
                    'slug' => $key                                  // URL-friendly category identifier
                ];
            }
        }
        
        return $latestByCategory;
    }

    /**
     * Get Category Display Name
     * 
     * Converts category keys to human-readable display names for UI presentation.
     * Provides consistent category naming across the application interface.
     * 
     * Display Name Mapping:
     * - soap-flower → Soap Flower
     * - special-flower → Special Flower
     * - fresh-flowers → Fresh Flowers
     * - souvenir → Souvenir
     * 
     * @param string $key Category key identifier
     * @return string Human-readable category display name
     */
    private function getCategoryDisplayName($key)
    {
        // Display Name Configuration
        /**
         * Category Display Names - Human-readable category labels
         * 
         * Provides consistent naming for UI display
         * Supports easy category name updates and internationalization
         */
        $displayNames = [
            'soap-flower' => 'Soap Flower',
            'special-flower' => 'Special Flower',
            'fresh-flowers' => 'Fresh Flowers',
            'souvenir' => 'Souvenir'
        ];
        
        // Fallback Name Generation
        /**
         * Dynamic Name Generation - Handle unmapped category keys
         * 
         * Fallback logic:
         * - Use mapped name if available
         * - Convert slug to title case if no mapping exists
         * - Replace hyphens with spaces for readability
         */
        return $displayNames[$key] ?? ucfirst(str_replace('-', ' ', $key));
    }
}
