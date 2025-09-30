<?php

/**
 * User Review Controller
 *
 * This controller handles customer review functionality for the Hanaya Shop e-commerce
 * application. It manages the complete review lifecycle including creation, validation,
 * image uploads, and display of product reviews with comprehensive security checks.
 *
 * Key Features:
 * - Secure review creation with order verification
 * - Image upload support for visual reviews
 * - Duplicate review prevention
 * - Order status validation for review eligibility
 * - Product-order relationship verification
 * - Review display with pagination
 * - User authentication and authorization
 *
 * Security Features:
 * - Order ownership verification
 * - Product-order relationship validation
 * - Duplicate review prevention
 * - Status-based review eligibility
 * - Image upload security and validation
 *
 * Review Workflow:
 * 1. Customer completes order (status = completed/shipped)
 * 2. Customer accesses review form for ordered products
 * 3. System validates eligibility and prevents duplicates
 * 4. Customer submits review with optional image
 * 5. Review is stored and displayed on product pages
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderDetail;
use App\Models\Product\Product;
use App\Models\Product\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Review Controller Class
 *
 * Manages customer review operations including creation, validation,
 * and display with comprehensive security and business rule enforcement.
 */
class ReviewController extends Controller
{
    /**
     * Store New Customer Review
     *
     * Handles the creation of new product reviews with comprehensive validation
     * including order verification, status checking, duplicate prevention, and
     * optional image upload. Ensures only legitimate customers can review
     * products they have actually purchased.
     *
     * Validation Rules:
     * - Product must exist in the system
     * - Order must exist and belong to current user
     * - Rating must be between 1-5 stars
     * - Comment is optional but limited to 10,000 characters
     * - Image is optional but must be valid image format under 2MB
     *
     * Security Checks:
     * - Order ownership verification
     * - Order status validation (must be eligible for reviews)
     * - Product-order relationship verification
     * - Duplicate review prevention
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with review data
     * @return \Illuminate\Http\RedirectResponse Redirect back with success or error message
     */
    public function store(Request $request)
    {
        // Input Validation
        /**
         * Review Data Validation - Comprehensive validation for review submission
         *
         * Validation Rules:
         * - product_id: Must exist in products table
         * - order_id: Must exist in orders table
         * - rating: Integer between 1-5 (star rating system)
         * - comment: Optional text up to 10,000 characters
         * - image: Optional image file (JPG, JPEG, PNG) up to 2MB
         */
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:10000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Extract Request Data
        /**
         * Request Data Extraction - Get validated data for processing
         * User ID from authentication ensures review ownership
         * Product and order IDs for relationship validation
         */
        $userId = Auth::id();
        $productId = $request->product_id;
        $orderId = $request->order_id;
        $generatedFileName = null;

        // Image Upload Processing
        /**
         * Review Image Upload - Handle optional image upload with security
         *
         * Image Processing:
         * - Generate unique filename with timestamp and uniqid for collision prevention
         * - Create reviews directory if it doesn't exist
         * - Move uploaded file to secure location
         * - Store only filename (not full path) for security
         */
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension(); // Unique filename with timestamp

            // Create reviews directory if it doesn't exist
            $uploadPath = public_path('images/reviews');
            if (! file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $imageName);
            $generatedFileName = $imageName;
        } else {
            // No image uploaded - store null value
            $generatedFileName = null; // Don't store default image path
        }

        // Order Ownership Verification
        /**
         * Security Validation - Verify order belongs to current user
         * Prevents users from reviewing products in other customers' orders
         * Critical security check for review system integrity
         */
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (! $order) {
            return back()->with('error', ((__('orders.order_not_found'))));
        }

        // Order Status Validation
        /**
         * Review Eligibility Check - Verify order status allows reviews
         * Only completed/shipped orders typically allow reviews
         * Prevents reviews for cancelled or pending orders
         * Status requirement configured in constants for flexibility
         */
        $canReviewStatus = config('constants.review.can_review_status');
        if ($order->status !== $canReviewStatus) {
            return back()->with('error', ((__('orders.can_only_review_shipped_orders'))));
        }

        // Product-Order Relationship Verification
        /**
         * Product Purchase Validation - Ensure product was actually ordered
         * Prevents reviews for products not in the customer's order
         * Maintains review authenticity and prevents fake reviews
         */
        $orderDetail = OrderDetail::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->first();

        if (! $orderDetail) {
            return back()->with('error', ((__('orders.product_not_found_in_order'))));
        }

        // Duplicate Review Prevention
        /**
         * Duplicate Check - Prevent multiple reviews for same product-order combination
         * One review per product per order maintains review integrity
         * Prevents review spam and maintains authentic feedback
         */
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();

        if ($existingReview) {
            return back()->with('error', ((__('orders.already_reviewed'))));
        }

        // Create New Review Record
        /**
         * Review Creation - Store validated review in database
         * Links review to user, product, and order for complete traceability
         * Includes rating, comment, and optional image path
         */
        Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'order_id' => $orderId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'image_path' => $generatedFileName,
        ]);

        return back()->with('success', ((__('orders.review_submitted_successfully'))));
    }

    /**
     * Get Paginated Product Reviews
     *
     * Retrieves all reviews for a specific product with user information
     * and pagination. Used for AJAX requests to load reviews dynamically
     * on product pages without full page refresh.
     *
     * Features:
     * - User information included for displaying reviewer names
     * - Newest reviews first for relevance
     * - Pagination for performance (10 reviews per page)
     * - JSON response for AJAX integration
     *
     * @param  int  $productId  Product ID to get reviews for
     * @return \Illuminate\Http\JsonResponse JSON response with paginated reviews
     */
    public function getProductReviews($productId)
    {
        // Product Reviews Query
        /**
         * Review Retrieval - Get paginated reviews with user information
         * Includes user relationship for displaying reviewer names
         * Ordered by creation date (newest first) for relevance
         * Paginated for optimal performance and user experience
         */
        $reviews = Review::with('user')
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($reviews);
    }

    /**
     * Show Review Creation Form
     *
     * Displays the review creation form for a specific product and order
     * combination. Includes comprehensive validation to ensure only eligible
     * customers can access the review form.
     *
     * Validation Process:
     * - Order ownership verification
     * - Product existence validation
     * - Product-order relationship checking
     * - Duplicate review prevention
     * - Review eligibility confirmation
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with product and order IDs
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Review form or redirect with error
     */
    public function create(Request $request)
    {
        // Extract Form Parameters
        /**
         * Parameter Extraction - Get product and order IDs from request
         * User ID from authentication for ownership validation
         * These parameters define the review context
         */
        $productId = $request->get('product_id');
        $orderId = $request->get('order_id');
        $userId = Auth::id();

        // Order Validation and Ownership Check
        /**
         * Order Security Validation - Verify order exists and belongs to user
         * Prevents access to review forms for other customers' orders
         * Critical security check for review form access
         */
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (! $order) {
            return redirect()->route('order.index')->with('error', ((__('orders.order_not_found'))));
        }

        // Product Existence Validation
        /**
         * Product Validation - Ensure product exists in system
         * Prevents review form access for non-existent products
         * Maintains data integrity and prevents errors
         */
        $product = Product::find($productId);
        if (! $product) {
            return redirect()->route('order.index')->with('error', ((__('orders.product_not_found'))));
        }

        // Product-Order Relationship Verification
        /**
         * Purchase Verification - Confirm product was actually ordered
         * Ensures only purchased products can be reviewed
         * Maintains review authenticity and system integrity
         */
        $orderDetail = OrderDetail::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->first();

        if (! $orderDetail) {
            return redirect()->route('order.index')->with('error', ((__('orders.product_not_found_in_order'))));
        }

        // Duplicate Review Check
        /**
         * Duplicate Prevention - Check if review already exists
         * Prevents multiple reviews for same product-order combination
         * Maintains review integrity and prevents spam
         */
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();

        if ($existingReview) {
            return redirect()->route('order.index')->with('error', ((__('orders.already_reviewed'))));
        }

        // Render Review Creation Form
        /**
         * Form Display - Show review creation form with validated context
         * Passes product, order, and order detail for form population
         * All validation passed - user eligible to submit review
         */
        return view('page.reviews.create', compact('product', 'order', 'orderDetail'));
    }
}
