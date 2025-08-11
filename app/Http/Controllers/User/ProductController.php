<?php
/**
 * User Product Controller
 * 
 * This controller handles product-related functionality for customer-facing pages
 * in the Hanaya Shop e-commerce application. It manages product browsing, searching,
 * filtering, and detailed product views with reviews and related products.
 * 
 * Key Features:
 * - Product listing with advanced filtering and sorting options
 * - Search functionality across multiple product attributes
 * - Category-based product filtering with multilingual support
 * - Product detail views with reviews and ratings
 * - Related products recommendation system
 * - View count tracking for analytics
 * - Performance optimization through caching
 * - Pagination for large product catalogs
 * 
 * @package App\Http\Controllers\User
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;  // Product model for product data
use Illuminate\Http\Request;     // HTTP request handling
use Illuminate\Support\Facades\DB;    // Database query builder
use Illuminate\Support\Facades\Cache; // Laravel caching for performance
use App\Models\Product\Review;   // Review model for product ratings

/**
 * Product Controller Class
 * 
 * Manages all product-related operations for customer-facing pages including
 * product listing, filtering, searching, and detailed product views.
 */
class ProductController extends Controller
{
    /**
     * Display a paginated listing of products with filtering and sorting options.
     * 
     * This method handles the main product catalog page with comprehensive
     * filtering, searching, and sorting capabilities. It includes category
     * filtering, keyword search across multiple fields, and various sorting
     * options including best sellers and price ranges.
     * 
     * Performance is optimized through intelligent caching based on query parameters.
     * The method supports both category ID and category name filtering for
     * SEO-friendly URLs and multilingual support.
     * 
     * @param Request $request HTTP request containing query parameters for filtering/sorting
     * @return \Illuminate\View\View Product listing view with filtered results
     */
    public function index(Request $request)
    {
        // Extract Query Parameters
        // These parameters control filtering, sorting, and searching behavior
        
        /**
         * Sort Parameter - Controls product ordering
         * Possible values: 'asc', 'desc', 'sale', 'views', 'bestseller', 'latest'
         */
        $sort = $request->query('sort');
        
        /**
         * Keyword Parameter - Search term for product search functionality
         * Searches across product name, description, price, and category information
         */
        $keyword = $request->query('q');
        
        /**
         * Category ID Parameter - Numeric category filter
         * Direct category filtering by database ID
         */
        $categoryId = $request->query('category');
        
        /**
         * Category Name Parameter - SEO-friendly category filter
         * Human-readable category names for better URLs and user experience
         */
        $categoryName = $request->query('category_name');

        // Cache Key Generation
        /**
         * Dynamic Cache Key - Creates unique cache identifier based on query parameters
         * MD5 hash ensures consistent key length while capturing all parameter variations
         * This approach provides efficient caching while maintaining data freshness
         */
        $cacheKey = 'products_index_' . md5(serialize([
            'sort' => $sort,
            'keyword' => $keyword,
            'categoryId' => $categoryId,
            'categoryName' => $categoryName,
            'page' => $request->query('page', 1)
        ]));

        // Cached Query Execution
        /**
         * Cache Strategy - Store results for 15 minutes (900 seconds)
         * Balances performance improvements with data freshness requirements
         * Cache invalidation occurs automatically after timeout period
         */
        $result = Cache::remember($cacheKey, 900, function () use ($sort, $keyword, $categoryId, $categoryName, $request) {
            
            // Base Query Construction
            /**
             * Product Query Builder - Start with optimized base query
             * Includes category relationship and average review ratings
             * withAvg() calculates average rating efficiently at database level
             */
            $query = Product::with('category')->withAvg('reviews', 'rating');
            
            // Category Name Filtering Section
            /**
             * Multilingual Category Mapping - Support for different language variants
             * Maps SEO-friendly slugs to actual category names in multiple languages
             * Enables flexible category filtering for international users
             */
            if ($categoryName) {
                $categoryMapping = [
                    'soap-flower' => ['Soap Flower', 'Hoa sáp', 'soap flower'],     // Soap flower variants
                    'fresh-flower' => ['Fresh Flower', 'Hoa tươi', 'fresh flower'],      // Fresh flower variants
                    'special-flower' => ['Special Flower', 'Hoa đặc biệt', 'special flower'], // Special flower variants
                    'souvenir' => ['Souvenir', 'Quà lưu niệm', 'souvenir']               // Souvenir variants
                ];

                // Apply Category Name Filter
                if (isset($categoryMapping[$categoryName])) {
                    $categoryNames = $categoryMapping[$categoryName];
                    /**
                     * Category Relationship Query - Filter products by category name variants
                     * Uses whereHas to join with categories table efficiently
                     * Supports multiple name variations for each category type
                     */
                    $query->whereHas('category', function ($q) use ($categoryNames) {
                        $q->where(function ($subQ) use ($categoryNames) {
                            foreach ($categoryNames as $name) {
                                $subQ->orWhere('name', 'like', "%$name%");
                            }
                        });
                    });
                }
            }
            // Category ID Filtering (Fallback)
            /**
             * Direct Category ID Filter - Simple numeric category filtering
             * Used when category_name parameter is not provided
             * More direct but less SEO-friendly than name-based filtering
             */
            elseif ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            // Advanced Search Functionality
            /**
             * Multi-Field Search - Comprehensive keyword search across multiple attributes
             * Searches product name, descriptions, price, image_url, category_id
             * Also searches related category name and descriptions for broader results
             * Uses LIKE operators with wildcards for flexible matching
             */
            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%")              // Product name search
                        ->orWhere('descriptions', 'like', "%$keyword%")   // Product description search
                        ->orWhere('products.price', 'like', "%$keyword%") // Price search (with table prefix)
                        ->orWhere('image_url', 'like', "%$keyword%")      // Image URL search
                        ->orWhere('category_id', 'like', "%$keyword%")    // Category ID search
                        ->orWhereHas('category', function ($catQ) use ($keyword) {
                            // Related category search
                            $catQ->where('name', 'like', "%$keyword%")        // Category name search
                                ->orWhere('descriptions', 'like', "%$keyword%"); // Category description search
                        });
                });
            }

            // Product Sorting Logic
            /**
             * Dynamic Sorting System - Multiple sorting options for different user needs
             * Each case handles a specific sorting requirement with appropriate database ordering
             */
            switch ($sort) {
                case 'asc':
                    // Price Ascending - Cheapest products first
                    $query->orderBy('price', 'asc');
                    break;
                case 'desc':
                    // Price Descending - Most expensive products first
                    $query->orderBy('price', 'desc');
                    break;
                case 'sale':
                    // Sale Items - Products with highest discount percentage first
                    $query->orderBy('discount_percent', 'desc');
                    break;
                case 'views':
                    // Most Viewed - Products with highest view count first
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'bestseller':
                    // Best Sellers - Products with highest total sales quantity
                    /**
                     * Best Seller Calculation - Joins with order_details to calculate total sold
                     * Uses LEFT JOIN to include products with zero sales
                     * Groups by all product columns to maintain data integrity
                     * COALESCE ensures null values become 0 for proper sorting
                     */
                    $query->leftJoin('order_details', 'products.id', '=', 'order_details.product_id')
                        ->select('products.*', DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_sold'))
                        ->groupBy('products.id', 'products.name', 'products.price', 'products.image_url', 'products.stock_quantity', 'products.category_id', 'products.descriptions', 'products.created_at', 'products.updated_at', 'products.discount_percent', 'products.view_count')
                        ->orderByDesc('total_sold');
                    break;
                case 'latest':
                default:
                    // Latest Products (Default) - Newest products first
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            // Pagination and Query Parameter Preservation
            /**
             * Paginated Results - Efficiently handle large product catalogs
             * 10 products per page provides optimal loading performance
             * appends() ensures search/filter parameters persist across pages
             */
            $products = $query->paginate(10)->appends([
                'sort' => $sort,
                'q' => $keyword,
                'category' => $categoryId,
                'category_name' => $categoryName
            ]);

            // Category Data for Filter Dropdown
            /**
             * Categories with Product Count - Populate filter dropdown
             * withCount() efficiently calculates product count for each category
             * Used for displaying category options with product quantities
             */
            $categories = \App\Models\Product\Category::withCount('product')->get();

            // Dynamic Page Title Generation
            /**
             * SEO-Optimized Page Titles - Generate appropriate page titles based on context
             * Default title for general product listing
             * Category-specific titles for filtered views improve SEO and user experience
             */
            $pageTitle = 'Products - Hanaya Shop';
            if ($categoryName) {
                $categoryTitles = [
                    'soap-flower' => 'Soap Flower',
                    'fresh-flower' => 'Fresh Flower',
                    'special-flower' => 'Special Flower',
                    'souvenir' => 'Souvenir'
                ];
                $pageTitle = $categoryTitles[$categoryName] ?? $pageTitle;
            }

            // Return Compiled Results
            /**
             * Result Array - Package all calculated data for view consumption
             * Separates cached data from request-specific parameters
             */
            return [
                'products' => $products,
                'categories' => $categories,
                'pageTitle' => $pageTitle,
            ];
        });

        // View Rendering with Combined Data
        /**
         * Final View Return - Merge cached results with current request parameters
         * Provides view with both data and current state for form maintenance
         * array_merge combines cached data with current request state
         */
        return view('page.products.index', array_merge($result, [
            'currentSort' => $sort,              // Current sort selection for form state
            'keyword' => $keyword,               // Current search keyword for form state
            'selectedCategory' => $categoryId,   // Current category ID for form state
            'selectedCategoryName' => $categoryName, // Current category name for form state
        ]));
    }

    /**
     * Display detailed view of a specific product with reviews and related products.
     * 
     * This method handles individual product detail pages including product information,
     * customer reviews with ratings, related products recommendations, and view tracking.
     * The method optimizes performance through strategic caching while ensuring view
     * counts are accurately tracked for analytics.
     * 
     * Features include:
     * - Comprehensive product details with category information
     * - Paginated customer reviews with user information
     * - Average rating calculation and review statistics
     * - Related products from the same category
     * - View count tracking for popularity metrics
     * - Efficient caching for improved performance
     * 
     * @param int $id Product ID to display
     * @return \Illuminate\View\View Product detail view with related data
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If product not found
     */
    public function show($id)
    {
        // Product Data Retrieval with Caching
        /**
         * Cached Product Details - Store product information for 30 minutes
         * Includes category relationship for complete product context
         * Longer cache time (1800 seconds) as product details change less frequently
         * findOrFail() ensures proper 404 handling for non-existent products
         */
        $product = Cache::remember("product_detail_{$id}", 1800, function () use ($id) {
            return Product::with('category')->findOrFail($id);
        });

        // View Count Tracking
        /**
         * Analytics Tracking - Increment view count for product popularity metrics
         * Not cached to ensure accurate view counting for analytics
         * Uses direct query for performance (no model instantiation needed)
         * Increment operation is atomic and efficient
         */
        Product::where('id', $id)->increment('view_count');

        // Product Reviews Retrieval
        /**
         * Paginated Reviews - Get customer reviews with user information
         * Includes user relationship for displaying reviewer names
         * Ordered by creation date (newest first) for relevance
         * Paginated (5 per page) for optimal page loading performance
         * Not cached as reviews change frequently and need real-time display
         */
        $reviews = $product->reviews()
            ->with('user')                    // Eager load user information
            ->orderBy('created_at', 'desc')   // Newest reviews first
            ->paginate(5);                    // 5 reviews per page

        // Review Statistics Calculation
        /**
         * Average Rating Calculation - Calculate overall product rating
         * Uses database aggregation for efficiency
         * Defaults to 5 stars if no reviews exist (optimistic default)
         */
        $averageRating = $product->reviews()->avg('rating') ?? 5;
        
        /**
         * Total Review Count - Count of all reviews for this product
         * Used for displaying review statistics and social proof
         */
        $totalReviews = $product->reviews()->count();

        // Related Products Recommendation
        /**
         * Related Products Query - Find similar products from same category
         * Excludes current product to avoid redundancy
         * Filters by same category for relevance
         * Orders by creation date for freshness
         * Limited to 8 products for optimal page layout and performance
         * Includes category relationship for complete product information
         */
        $relatedProducts = Product::with('category')
            ->where('id', '!=', $id)                    // Exclude current product
            ->where('category_id', $product->category_id) // Same category only
            ->orderBy('created_at', 'desc')             // Newest products first
            ->limit(8)                                  // Optimal number for UI layout
            ->get();

        // View Rendering with Complete Product Context
        /**
         * Product Detail View - Render complete product page
         * compact() creates associative array with all required data
         * Includes product details, reviews, statistics, and recommendations
         */
        return view('page.products.productDetail', compact(
            'product',          // Main product information
            'relatedProducts',  // Recommended similar products
            'reviews',          // Paginated customer reviews
            'averageRating',    // Calculated average rating
            'totalReviews'      // Total number of reviews
        ));
    }
}
