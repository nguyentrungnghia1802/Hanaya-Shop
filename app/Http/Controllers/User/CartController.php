<?php
/**
 * User Cart Controller
 * 
 * This controller handles shopping cart functionality for customers
 * in the Hanaya Shop e-commerce application. It manages cart operations
 * including adding products, viewing cart contents, removing items,
 * and quick purchase functionality.
 * 
 * Key Features:
 * - Product addition to cart with stock validation
 * - Shopping cart display with pricing calculations
 * - Item removal from cart
 * - Quick "Buy Now" functionality
 * - Session-based cart for guests and user-based cart for authenticated users
 * - Stock quantity validation and error handling
 * - Discount price calculations and display
 * 
 * Session Management:
 * - Guest users: Cart tied to session ID
 * - Authenticated users: Cart tied to user ID
 * - Seamless transition between guest and authenticated states
 * 
 * @package App\Http\Controllers\User
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;             // HTTP request handling
use App\Models\Cart\Cart;                // Cart model for database operations
use App\Models\Product\Product;          // Product model for product data
use Illuminate\Support\Facades\Session;  // Session management for guest carts
use Illuminate\Support\Facades\Auth;     // Authentication services

/**
 * Cart Controller Class
 * 
 * Manages all shopping cart operations including product addition,
 * cart viewing, item removal, and quick purchase functionality.
 * Handles both guest and authenticated user cart management.
 */
class CartController extends Controller
{
    /**
     * Add Product to Cart
     * 
     * Adds a product to the shopping cart with comprehensive validation.
     * Handles both new additions and quantity updates for existing items.
     * Includes stock validation and prevents overselling.
     * 
     * Validation Features:
     * - Stock availability checking
     * - Quantity limit enforcement
     * - Duplicate item handling (quantity update)
     * - User/session association
     * 
     * @param \Illuminate\Http\Request $request HTTP request with quantity parameter
     * @param int $productId Product ID to add to cart
     * @return \Illuminate\Http\RedirectResponse Redirect back with success or error message
     */
    public function add(Request $request, $productId)
    {
        // Session and Product Setup
        /**
         * Cart Context Setup - Establish session and product context
         * Session ID used for guest cart management
         * Product validation ensures item exists and is available
         */
        $sessionId = Session::getId();                          // Get current session ID for guest carts
        $product = Product::findOrFail($productId);             // Find product or return 404
        $quantityToAdd = $request->input('quantity', 1);       // Get requested quantity (default: 1)

        // Stock Availability Check
        /**
         * Stock Validation - Prevent adding out-of-stock products
         * Immediate validation prevents cart pollution with unavailable items
         * Returns user-friendly error message for out-of-stock situations
         */
        if ($product->stock_quantity <= 0) {
            return redirect()->back()->with('error', (__('cart.out_of_stock_alert')));
        }

        // Existing Cart Item Check
        /**
         * Duplicate Item Detection - Check if product already in cart
         * Uses session ID to find existing cart entries
         * Enables quantity updates instead of duplicate entries
         */
        $existing = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        // Quantity Validation
        /**
         * Total Quantity Calculation - Calculate final cart quantity
         * Combines existing quantity with new addition
         * Validates against available stock to prevent overselling
         */
        $currentQuantity = $existing ? $existing->quantity : 0;
        $newTotalQuantity = $currentQuantity + $quantityToAdd;

        if ($newTotalQuantity > $product->stock_quantity) {
            return redirect()->back()->with('error', (__('cart.out_of_stock_alert')));
        }

        // Cart Item Creation or Update
        /**
         * Cart Management Logic - Add new item or update existing quantity
         * Existing items: Update quantity to new total
         * New items: Create new cart record with user/session association
         */
        if ($existing) {
            // Update existing item quantity
            $existing->quantity = $newTotalQuantity;
            $existing->save();
        } else {
            // Create new cart item
            Cart::create([
                'product_id' => $product->id,       // Product reference
                'user_id' => Auth::id(),            // User ID (null for guests)
                'quantity' => $quantityToAdd,       // Requested quantity
                'session_id' => $sessionId,         // Session ID for cart tracking
            ]);
        }

        return redirect()->back()->with('success', (__('cart.added_to_cart')));
    }

    /**
     * Display Shopping Cart
     * 
     * Retrieves and displays all items in the user's shopping cart.
     * Calculates pricing including discounts and formats data for display.
     * Handles both authenticated and guest user carts.
     * 
     * Display Features:
     * - Product information with images
     * - Original and discounted pricing
     * - Quantity controls and totals
     * - Stock availability information
     * 
     * @return \Illuminate\View\View Cart index view with cart items and pricing
     */
    public function index()
    {
        // User and Session Context
        /**
         * Cart Context Setup - Establish user and session context
         * User ID for authenticated users, session ID for guests
         * Enables proper cart retrieval for both user types
         */
        $userId = Auth::id();               // Get authenticated user ID
        $sessionId = Session::getId();      // Get current session ID

        // Cart Query Construction
        /**
         * Cart Retrieval Query - Build query based on user authentication status
         * Authenticated users: Filter by user ID
         * Guest users: Filter by session ID
         * Includes product relationship for complete cart data
         */
        $query = Cart::with('product');
        
        if ($userId) {
            $query->where('user_id', $userId);         // Authenticated user cart
        } else {
            $query->where('session_id', $sessionId);   // Guest user cart
        }
        
        $cartItems = $query->get(); // Execute cart query

        // Cart Data Processing
        /**
         * Cart Data Transformation - Convert database records to display format
         * Calculates discount pricing and formats for template consumption
         * Includes all necessary data for cart display and functionality
         */
        $cart = [];

        foreach ($cartItems as $item) {
            // Price Calculation
            /**
             * Price Processing - Calculate original and discounted prices
             * Applies discount percentage to original price
             * Maintains both prices for display transparency
             */
            $price = $item->product->price;                                    // Original product price
            $discountPercent = $item->product->discount_percent ?? 0;         // Discount percentage
            $discountedPrice = $price * (1 - $discountPercent / 100);        // Calculated discounted price

            // Cart Item Data Structure
            /**
             * Cart Item Formatting - Structure data for template display
             * Includes all necessary information for cart interface
             * Product data, pricing, quantities, and metadata
             */
            $cart[$item->id] = [
                'id'         => $item->id,                          // Cart item ID
                'product_id' => $item->product->id,                 // Product reference
                'product_quantity' => $item->product->stock_quantity, // Available stock
                'name'       => $item->product->name,               // Product name
                'image_url'  => $item->product->image_url,          // Product image
                'price'      => $price,                             // Original price
                'discounted_price' => $discountedPrice,             // Discounted price
                'discount_percent' => $discountPercent,             // Discount percentage
                'quantity'   => $item->quantity,                    // Cart quantity
            ];
        }

        return view('page.cart.index', compact('cart'));
    }

    /**
     * Remove Item from Cart
     * 
     * Removes a specific item from the shopping cart.
     * Includes security validation to ensure users can only remove their own items.
     * Supports both authenticated and guest user cart management.
     * 
     * Security Features:
     * - User/session ownership validation
     * - Item existence verification
     * - Proper authorization checks
     * 
     * @param int $id Cart item ID to remove
     * @return \Illuminate\Http\RedirectResponse Redirect back with success message
     */
    public function remove($id)
    {
        // Context Setup
        /**
         * Removal Context - Establish user and session context for security
         * Session ID for guest validation, user ID for authenticated validation
         * Ensures users can only remove their own cart items
         */
        $sessionId = Session::getId();  // Current session ID
        $userId = Auth::id();           // Current user ID (if authenticated)

        // Security Query Construction
        /**
         * Secure Removal Query - Build query with ownership validation
         * Authenticated users: Validate user ownership
         * Guest users: Validate session ownership
         * Prevents unauthorized cart item removal
         */
        $query = Cart::where('id', $id);

        if ($userId) {
            $query->where('user_id', $userId);         // Ensure user owns the cart item
        } else {
            $query->where('session_id', $sessionId);   // Ensure session owns the cart item
        }

        $query->delete(); // Remove cart item with security validation

        return redirect()->back()->with('success', (__('cart.removed_from_cart')));
    }

    /**
     * Quick Buy Now Functionality
     * 
     * Provides immediate purchase capability by adding product to cart
     * and redirecting to cart page. Handles stock validation and cart management.
     * Enables streamlined purchase flow for impatient customers.
     * 
     * Features:
     * - Immediate cart addition
     * - Stock validation
     * - Quantity handling for existing items
     * - Direct cart redirection with product highlighting
     * 
     * @param \Illuminate\Http\Request $request HTTP request with product and quantity data
     * @return \Illuminate\Http\RedirectResponse Redirect to cart with product reference
     */
    public function buyNow(Request $request)
    {
        // Request Data Processing
        /**
         * Buy Now Parameters - Extract product and quantity information
         * Product ID for item identification
         * Quantity for purchase amount (default: 1)
         * Session ID for cart tracking
         */
        $productId = $request->input('product_id');     // Product to purchase
        $sessionId = Session::getId();                  // Session for cart tracking
        $quantity = intval($request->input('quantity', 1)); // Purchase quantity

        $product = Product::findOrFail($productId); // Find product or return 404
        
        // Stock Validation
        /**
         * Purchase Stock Validation - Ensure sufficient stock for immediate purchase
         * Prevents buy now functionality for unavailable quantities
         * Returns user-friendly error for stock limitations
         */
        if ($quantity > $product->stock_quantity) {
            return redirect()->back()->with('error', ((__('cart.out_of_stock_alert'))));
        }
        
        // Existing Cart Item Check
        /**
         * Quick Purchase Cart Management - Handle existing cart items
         * Adds to existing quantity if product already in cart
         * Creates new cart item if product not in cart
         */
        $existing = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            // Add to existing cart quantity
            $existing->quantity += $quantity;
            $existing->save();
        } else {
            // Create new cart item for quick purchase
            Cart::create([
                'product_id' => $product->id,       // Product reference
                'user_id' => Auth::id(),            // User ID (null for guests)
                'quantity'   => $quantity,          // Purchase quantity
                'session_id' => $sessionId,         // Session tracking
            ]);
        }

        // Redirect to Cart with Product Reference
        /**
         * Quick Purchase Completion - Redirect to cart with product highlighting
         * Product ID passed for potential highlighting or focus
         * Enables immediate cart review and checkout initiation
         */
        return redirect()->route('cart.index')->with('product_id', $product->id);
    }
}
