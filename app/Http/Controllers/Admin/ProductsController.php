<?php

/**
 * Admin Products Controller
 *
 * This controller handles product management functionality for the admin panel
 * in the Hanaya Shop e-commerce application. It provides comprehensive CRUD
 * operations for products, including creation, editing, deletion, and detailed
 * product views with reviews and filtering capabilities.
 *
 * Key Features:
 * - Product listing with filtering and search functionality
 * - Product creation with image upload support
 * - Product editing with image replacement
 * - Product deletion with cleanup
 * - Review management and moderation
 * - Cache management for performance optimization
 * - AJAX support for seamless user experience
 * - Stock level monitoring and filtering
 *
 * Performance Features:
 * - Cache invalidation on data changes
 * - Pagination for large product catalogs
 * - Efficient database queries with eager loading
 * - Image optimization and cleanup
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;         // HTTP request handling
use App\Models\Product\Product;      // Product model for database operations
use App\Models\Product\Review;     // Category model for product categorization
use Illuminate\Http\Request;       // Review model for product reviews
use Illuminate\Support\Facades\Cache; // Cache management for performance

/**
 * Products Controller Class
 *
 * Manages all product-related administrative functions including product creation,
 * modification, deletion, and review moderation. Implements filtering, search,
 * and image management capabilities for comprehensive product administration.
 */
class ProductsController extends Controller
{
    /**
     * Display Product List with Filtering
     *
     * Shows a paginated list of all products with filtering capabilities.
     * Supports filtering by category, stock levels, and maintains filter state
     * across pagination. Includes category relationships for efficient data display.
     *
     * Filter Options:
     * - Category filtering by ID
     * - Stock level filtering (low stock, out of stock)
     * - Newest first ordering for relevance
     *
     * Performance Features:
     * - Pagination (20 products per page)
     * - Eager loading of category relationships
     * - Query string preservation for filter persistence
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with filter parameters
     * @return \Illuminate\View\View Product index view with filtered results
     */
    public function index(Request $request)
    {
        // Filter Parameter Extraction
        /**
         * Filter Parameters - Extract filtering criteria from request
         * category_id: Filter products by specific category
         * stock_filter: Filter by stock levels (low_stock, out_of_stock)
         */
        $categoryId = $request->input('category_id');      // Category filter parameter
        $stockFilter = $request->input('stock_filter');    // Stock level filter parameter

        // Base Query Construction
        /**
         * Product Query Builder - Build filtered product query
         * Includes category relationship for efficient display
         * Orders by creation date (newest first) for relevance
         */
        $query = Product::with('category')->orderBy('created_at', 'desc');

        // Category Filter Application
        /**
         * Category Filtering - Apply category filter if specified
         * Filters products to show only those in selected category
         * Improves admin product management efficiency
         */
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Stock Level Filter Application
        /**
         * Stock Level Filtering - Apply stock-based filters
         * low_stock: Products with less than 2 items but greater than 0
         * out_of_stock: Products with exactly 0 items in stock
         * Helps admin identify inventory issues quickly
         */
        if ($stockFilter) {
            if ($stockFilter === 'low_stock') {
                $query->where('stock_quantity', '<', 2)->where('stock_quantity', '>', 0);
            } elseif ($stockFilter === 'out_of_stock') {
                $query->where('stock_quantity', 0);
            }
        }

        // Pagination and Results
        /**
         * Paginated Results - Apply pagination with filter persistence
         * withQueryString() maintains filter parameters across pagination
         * 20 products per page for optimal loading performance
         */
        $products = $query->paginate(20)->withQueryString();

        // Category Data for Filter Dropdown
        /**
         * Category Options - Get all categories for filter dropdown
         * Enables admin to switch between different category filters
         * Used in the filter form for category selection
         */
        $categories = Category::all();

        return view('admin.products.index', [
            'products' => $products,                    // Paginated product results
            'categories' => $categories,                // All categories for filtering
            'selectedCategory' => $categoryId,          // Current selected category
            'selectedStockFilter' => $stockFilter,      // Current selected stock filter
        ]);
    }

    /**
     * Show Product Creation Form
     *
     * Displays the form for creating new products.
     * Includes all available categories for product categorization.
     * Form provides all necessary fields for complete product data entry.
     *
     * @return \Illuminate\View\View Product creation form view
     */
    public function create()
    {
        // Category Data for Dropdown
        /**
         * Category Selection - Get all categories for product creation
         * Enables admin to assign products to appropriate categories
         * Essential for product organization and customer browsing
         */
        $categories = Category::all();

        return view('admin.products.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store New Product
     *
     * Handles product creation with comprehensive validation and image upload.
     * Creates new product records with all necessary data and manages file uploads.
     * Includes cache invalidation for immediate data freshness.
     *
     * Validation Rules:
     * - name: Required, max 255 characters
     * - descriptions: Required text description
     * - price: Required numeric, minimum 0
     * - stock_quantity: Required integer, minimum 0
     * - category_id: Required, must exist in categories table
     * - discount_percent: Optional numeric, 0-100 range
     * - view_count: Optional integer, minimum 0
     * - image_url: Optional image file with size and type restrictions
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with product data
     * @return \Illuminate\Http\RedirectResponse Redirect to product list with success message
     */
    public function store(Request $request)
    {
        // Input Validation
        /**
         * Product Data Validation - Comprehensive validation for all product fields
         * Ensures data integrity and security for product creation
         * Image validation includes file type and size restrictions
         */
        $request->validate([
            'name' => 'required|string|max:255',                          // Product name validation
            'descriptions' => 'required|string',                          // Description validation
            'price' => 'required|numeric|min:0',                          // Price validation
            'stock_quantity' => 'required|integer|min:0',                 // Stock validation
            'category_id' => 'required|exists:categories,id',             // Category existence validation
            'discount_percent' => 'nullable|numeric|min:0|max:100',       // Discount percentage validation
            'view_count' => 'nullable|integer|min:0',                     // View count validation
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Image file validation
        ]);

        // Image Upload Handling
        /**
         * Image Upload Process - Handle product image upload with fallback
         * Generates unique filename to prevent conflicts
         * Uses default image if no image provided
         * Stores images in public/images/products directory
         */
        $generatedFileName = 'default-product.jpg'; // Default fallback image
        if ($request->hasFile('image_url')) {
            $imageName = time().'.'.$request->file('image_url')->extension(); // Unique filename
            $request->file('image_url')->move(public_path('images/products'), $imageName);
            $generatedFileName = $imageName;
        }

        // Product Creation
        /**
         * Product Record Creation - Create new product with validated data
         * Assigns all form fields to product model
         * Includes default values for optional fields
         */
        $product = new Product;
        $product->name = $request->input('name');
        $product->descriptions = $request->input('descriptions');
        $product->price = $request->input('price');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->image_url = $generatedFileName;
        $product->category_id = $request->input('category_id');
        $product->discount_percent = $request->input('discount_percent', 0);
        $product->view_count = $request->input('view_count', 0);

        // Save and Cache Management
        /**
         * Product Save and Cache Invalidation - Save product and update cache
         * Invalidates product cache to ensure fresh data in admin interface
         * Returns appropriate response based on save success
         */
        if ($product->save()) {
            Cache::forget('admin_products_all'); // Invalidate cache for fresh data

            return redirect()->route('admin.product')->with('success', __('admin.product_created_successfully'));
        } else {
            return redirect()->back()->with('error', __('admin.product_creation_failed'));
        }
    }

    /**
     * Show Product Edit Form
     *
     * Displays the form for editing an existing product.
     * Pre-populates form with current product data and includes all categories.
     * Provides interface for updating all product attributes including image.
     *
     * @param  int  $id  Product ID to edit
     * @return \Illuminate\View\View Product edit form with current data
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);    // Find product or return 404
        $categories = Category::all();          // Get all categories for dropdown

        return view('admin.products.edit', [
            'product' => $product,              // Current product data
            'categories' => $categories,        // All categories for selection
        ]);
    }

    /**
     * Update Product Information
     *
     * Updates existing product with new data including optional image replacement.
     * Handles image cleanup when replacing product images.
     * Includes comprehensive validation and cache management.
     *
     * Update Features:
     * - All product fields updateable
     * - Image replacement with old image cleanup
     * - Validation for data integrity
     * - Cache invalidation for fresh data
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with updated product data
     * @param  int  $id  Product ID to update
     * @return \Illuminate\Http\RedirectResponse Redirect to product list with success message
     */
    public function update(Request $request, $id)
    {
        // Input Validation for Updates
        /**
         * Product Update Validation - Same validation rules as creation
         * Ensures data integrity for product modifications
         * Image validation for optional image replacement
         */
        $request->validate([
            'name' => 'required|string|max:255',                      // Product name validation
            'descriptions' => 'required|string',                      // Description validation
            'price' => 'required|numeric|min:0',                      // Price validation
            'stock_quantity' => 'required|integer|min:0',             // Stock validation
            'category_id' => 'required|exists:categories,id',         // Category validation
            'discount_percent' => 'nullable|numeric|min:0|max:100',   // Discount validation
            'view_count' => 'nullable|integer|min:0',                 // View count validation
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
        ]);

        // Product Data Updates
        /**
         * Product Update Process - Update product with new data
         * Preserves existing view count if not provided
         * Updates all modifiable product attributes
         */
        $product = Product::findOrFail($id);
        $product->name = $request->input('name');
        $product->descriptions = $request->input('descriptions');
        $product->price = $request->input('price');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->category_id = $request->input('category_id');
        $product->discount_percent = $request->input('discount_percent', 0);
        $product->view_count = $request->input('view_count', $product->view_count ?? 0);

        // Image Replacement Handling
        /**
         * Image Update Process - Replace product image with cleanup
         * Deletes old image file to prevent storage bloat
         * Preserves default images to maintain system integrity
         * Generates unique filename for new images
         */
        if ($request->hasFile('image_url')) {
            // Delete old image if it exists and is not default
            if ($product->image_url && $product->image_url !== 'default-product.jpg') {
                $oldImagePath = public_path('images/products/'.$product->image_url);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Remove old image file
                }
            }

            // Upload new image with unique filename
            $imageName = time().'.'.$request->file('image_url')->extension();
            $request->file('image_url')->move(public_path('images/products'), $imageName);
            $product->image_url = $imageName;
        }

        // Save and Cache Management
        /**
         * Product Save and Cache Update - Save changes and invalidate cache
         * Ensures updated product data appears immediately in admin interface
         * Maintains data consistency across the application
         */
        $product->save();
        Cache::forget('admin_products_all'); // Invalidate cache for fresh data

        return redirect()->route('admin.product')->with('success', __('admin.product_updated_successfully'));
    }

    /**
     * Delete Product
     *
     * Removes product from database with image cleanup.
     * Supports both AJAX and traditional form submissions.
     * Includes safety measures for default images and cache management.
     *
     * Cleanup Features:
     * - Image file deletion (preserves default images)
     * - Database record removal
     * - Cache invalidation
     * - Flexible response format (JSON/redirect)
     *
     * @param  int  $id  Product ID to delete
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse Appropriate response based on request type
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id); // Find product or return 404

        // Image Cleanup Process
        /**
         * Image File Cleanup - Remove associated product image
         * Preserves default/base images to maintain system integrity
         * Checks file existence before deletion to prevent errors
         */
        if (
            $product->image_url &&
            $product->image_url !== 'base.jpg' &&
            file_exists(public_path('images/products/'.$product->image_url))
        ) {
            unlink(public_path('images/products/'.$product->image_url)); // Delete image file
        }

        $product->delete(); // Remove product from database
        Cache::forget('admin_products_all'); // Invalidate cache

        // Response Type Handling
        /**
         * Dynamic Response - Return appropriate response type
         * JSON for AJAX requests (seamless UI updates)
         * Redirect for traditional form submissions
         */
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]); // AJAX success response
        }

        return redirect()->route('admin.product')->with('success', __('admin.product_deleted_successfully'));
    }

    /**
     * Show Product Details
     *
     * Displays detailed product information including reviews.
     * Supports both AJAX requests (JSON response) and regular page views.
     * Includes comprehensive product data and associated reviews with pagination.
     *
     * Response Types:
     * - JSON for AJAX quick view functionality
     * - HTML view for detailed product management
     *
     * @param  int  $id  Product ID to display
     * @param  \Illuminate\Http\Request  $request  HTTP request for response type detection
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse View or JSON based on request type
     */
    public function show($id, Request $request)
    {
        $product = Product::with('category')->findOrFail($id); // Get product with category

        // AJAX Request Detection
        /**
         * AJAX Request Handling - Detect various AJAX request indicators
         * Supports multiple AJAX detection methods for compatibility
         * Returns JSON data for quick view functionality
         */
        if (
            $request->ajax() ||
            $request->wantsJson() ||
            $request->expectsJson() ||
            $request->header('X-Requested-With') === 'XMLHttpRequest' ||
            strpos($request->header('Accept', ''), 'application/json') !== false ||
            $request->query('ajax') === '1'
        ) {
            // JSON Response for AJAX
            /**
             * AJAX Product Data - Return essential product information as JSON
             * Optimized data set for quick view functionality
             * Includes image URL generation for proper display
             */
            return response()->json([
                'success' => true,
                'id' => $product->id,
                'name' => $product->name,
                'descriptions' => $product->descriptions,
                'price' => $product->price,
                'stock_quantity' => $product->stock_quantity,
                'category_name' => $product->category ? $product->category->name : '',
                'image_url' => asset('images/products/'.($product->image_url ?? 'base.jpg')),
            ]);
        }

        // Review Data for Detailed View
        /**
         * Product Reviews - Get paginated reviews for detailed product view
         * Includes user and order relationships for complete review context
         * Ordered by creation date (newest first) for relevance
         */
        $reviews = Review::with(['user', 'order'])
            ->where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Return detailed product view
        return view('admin.products.show', [
            'product' => $product,      // Product with category relationship
            'reviews' => $reviews,      // Paginated reviews with relationships
        ]);
    }

    /**
     * Search Products with Filtering
     *
     * Provides comprehensive search functionality for the admin product interface.
     * Supports keyword search combined with category and stock filtering.
     * Returns HTML table rows for seamless integration with existing interface.
     *
     * Search Features:
     * - Multi-keyword search across name and description
     * - Category filtering integration
     * - Stock level filtering integration
     * - HTML response for direct DOM insertion
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with search parameters
     * @return \Illuminate\Http\JsonResponse JSON response with HTML table rows and count
     */
    public function search(Request $request)
    {
        // Search Parameters
        /**
         * Search Parameter Extraction - Get all search and filter criteria
         * Supports keyword search and multiple filter types
         * Trims search query to handle whitespace issues
         */
        $searchQuery = trim($request->input('query', ''));     // Search keywords
        $categoryId = $request->input('category_id');          // Category filter
        $stockFilter = $request->input('stock_filter');        // Stock level filter

        // Base Query with Ordering
        /**
         * Search Query Builder - Build comprehensive search query
         * Includes category relationship for display
         * Orders by creation date (newest first) for relevance
         */
        $productsQuery = Product::with('category')->orderBy('created_at', 'desc');

        // Keyword Search Application
        /**
         * Multi-keyword Search - Apply keyword search across multiple fields
         * Splits search query into individual words for comprehensive matching
         * Searches both product name and description for maximum coverage
         */
        if (! empty($searchQuery)) {
            $keywords = preg_split('/\s+/', $searchQuery); // Split into individual keywords

            $productsQuery->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('name', 'LIKE', "%{$keyword}%")           // Search in product name
                            ->orWhere('descriptions', 'LIKE', "%{$keyword}%");     // Search in description
                    });
                }
            });
        }

        // Category Filter Application
        /**
         * Category Filtering - Apply category filter to search results
         * Integrates with keyword search for comprehensive filtering
         * Maintains filter state across search operations
         */
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        // Stock Filter Application
        /**
         * Stock Level Filtering - Apply stock-based filters to search results
         * Same logic as main index filtering for consistency
         * Helps identify inventory issues in search results
         */
        if ($stockFilter) {
            if ($stockFilter === 'low_stock') {
                $productsQuery->where('stock_quantity', '<', 2)->where('stock_quantity', '>', 0);
            } elseif ($stockFilter === 'out_of_stock') {
                $productsQuery->where('stock_quantity', 0);
            }
        }

        $products = $productsQuery->get(); // Execute search query

        // HTML Generation for Search Results
        /**
         * Search Results HTML - Generate table rows for search results
         * Uses partial view for consistent formatting with main table
         * Maintains all functionality (edit, delete, view buttons)
         */
        $html = view('admin.products.partials.table_rows', compact('products'))->render();

        return response()->json([
            'html' => $html,            // HTML table rows for insertion
            'count' => $products->count(), // Result count for UI feedback
        ]);
    }

    /**
     * Delete Product Review
     *
     * Removes inappropriate or problematic product reviews.
     * Includes image cleanup for reviews with associated images.
     * Provides content moderation capability for admin users.
     *
     * Cleanup Features:
     * - Review record deletion
     * - Associated image file removal
     * - Preservation of default images
     *
     * @param  int  $reviewId  Review ID to delete
     * @return \Illuminate\Http\RedirectResponse Redirect back with success message
     */
    public function deleteReview($reviewId)
    {
        $review = Review::findOrFail($reviewId); // Find review or return 404

        // Review Image Cleanup
        /**
         * Review Image Deletion - Remove associated review image
         * Preserves default/base images to maintain system integrity
         * Checks file existence before deletion to prevent errors
         */
        if ($review->image_path && $review->image_path !== 'base.jpg') {
            $imagePath = public_path('images/reviews/'.$review->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete review image file
            }
        }

        $review->delete(); // Remove review from database

        return back()->with('success', __('admin.review_deleted_successfully'));
    }
}
