<?php
/**
 * User Checkout Controller
 * 
 * This controller handles the complete checkout process for customers in the Hanaya Shop
 * e-commerce application. It manages the transition from shopping cart to completed order,
 * including order preview, validation, payment processing, and order confirmation.
 * The controller ensures data integrity through database transactions and provides
 * comprehensive error handling for a smooth checkout experience.
 * 
 * Key Features:
 * - Cart item validation and stock verification
 * - Order preview and confirmation workflow
 * - Multiple payment method support (cash, card, online)
 * - Address selection and shipping management
 * - Inventory management and stock updates
 * - Order creation with detailed line items
 * - Payment processing integration
 * - Email notifications to admins
 * - Transaction rollback on failures
 * - Session management for checkout flow
 * 
 * Checkout Flow:
 * 1. Cart → preview() → Validation & Session Storage
 * 2. index() → Checkout Form Display
 * 3. store() → Order Processing & Payment
 * 4. success() → Order Confirmation Page
 * 
 * @package App\Http\Controllers\User
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;                    // HTTP request handling
use Illuminate\Support\Facades\Auth;           // User authentication
use Illuminate\Support\Facades\DB;             // Database transactions
use App\Models\Order\Order;                    // Order model for order creation
use App\Models\Order\OrderDetail;              // Order detail model for line items
use App\Models\Product\Product;                // Product model for inventory management
use App\Models\Cart\Cart;                      // Cart model for cart operations
use Illuminate\Support\Facades\Session;        // Session management
use App\Notifications\NewOrderPending;         // Admin notification for new orders
use App\Notifications\CustomerNewOrderPending; // Customer notification for new orders
use App\Models\User;                          // User model for admin notifications
use App\Notifications\OrderCancelledNotification; // Order cancellation notifications
use App\Notifications\OrderConfirmedNotification; // Order confirmation notifications
use App\Models\Order\Payment;                  // Payment model for payment processing
use App\Models\Address;                        // Address model for shipping information

/**
 * Checkout Controller Class
 * 
 * Manages the complete customer checkout process from cart validation
 * to order completion, including payment processing and notification handling.
 */
class CheckoutController extends Controller
{
    /**
     * Preview Selected Cart Items and Validate Stock
     * 
     * This method handles the initial checkout step where customers select items
     * from their cart to proceed to checkout. It validates product availability,
     * checks stock quantities, and stores selected items in session for the
     * checkout process. This prevents customers from ordering out-of-stock items.
     * 
     * @param Request $request HTTP request containing selected items JSON
     * @return \Illuminate\Http\RedirectResponse Redirect to checkout or back with errors
     */
    public function preview(Request $request)
    {
        // Extract and decode selected items from request
        /**
         * Selected Items Processing - Parse JSON data from cart selection
         * The frontend sends selected cart items as JSON for processing
         * Each item contains: id, name, quantity, price, stock_quantity, etc.
         */
        $json = $request->input('selected_items_json');
        $selectedItems = json_decode($json, true) ?? [];

        // Stock Validation Array - Collect any stock-related errors
        $errorMessages = [];

        // Validate Stock Availability for Each Selected Item
        /**
         * Stock Validation Loop - Check each item against available inventory
         * Prevents overselling and provides clear error messages to customers
         * about specific products that have insufficient stock
         */
        foreach ($selectedItems as $item) {
            $name = $item['name'] ?? ((__('checkout.unknown_product')));        // Product name with fallback
            $quantity = $item['quantity'] ?? 0;                        // Requested quantity
            $stock = $item['stock_quantity'] ?? 0;                     // Available stock

            // Check if requested quantity exceeds available stock
            if ($quantity > $stock) {
                $errorMessages[] = "Sản phẩm \"{$name}\" chỉ còn {$stock} sản phẩm trong kho.";
            }
        }

        // Handle Stock Validation Errors
        /**
         * Error Handling - Return to previous page with validation errors
         * If any items have insufficient stock, prevent checkout and show errors
         * This maintains data integrity and prevents order fulfillment issues
         */
        if (!empty($errorMessages)) {
            return redirect()->back()->withErrors($errorMessages);
        }

        // Store Valid Items in Session for Checkout Process
        /**
         * Session Storage - Temporarily store selected items for checkout flow
         * This enables the checkout process to access selected items across requests
         * Session data will be cleared after successful order completion
         */
        session(['selectedItems' => $selectedItems]);

        // Proceed to Checkout Page
        return redirect()->route('checkout.index');
    }

    /**
     * Display Checkout Form with Order Summary
     * 
     * This method renders the main checkout page where customers can review
     * their order, select shipping address, choose payment method, and add
     * special instructions. It retrieves all necessary data for the checkout
     * form and ensures selected items are available.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Checkout view or redirect
     */
    public function index()
    {
        // Get Current User Information
        /**
         * User Authentication - Retrieve authenticated user for order processing
         * User information is needed for address lookup, order creation, and personalization
         */
        $user = Auth::user();
        $userName = $user->name;

        // Retrieve User Addresses for Shipping Selection
        /**
         * Address Management - Get all saved addresses for this user
         * Enables customers to choose from previously saved addresses
         * Improves checkout experience for repeat customers
         */
        $addresses = Address::where('user_id', $user->id)->get();
        $firstAddress = $addresses->first(); // Default address selection

        // Retrieve Selected Items from Session
        /**
         * Session Data Retrieval - Get items selected for checkout
         * If no items in session, redirect back with error message
         * This prevents accessing checkout page without valid cart items
         */
        $selectedItems = session('selectedItems', []);
        if (empty($selectedItems)) {
            return redirect()->back()->with('error', __('checkout.no_products_selected'));
        }

        // Get Available Payment Methods
        /**
         * Payment Options - Retrieve all available payment methods
         * Payment methods are defined in the Payment model
         * Supports multiple payment options like cash, card, online payments
         */
        $paymentMethods = Payment::getAvailableMethods();

        // Render Checkout Page with All Required Data
        /**
         * View Rendering - Display checkout form with complete order context
         * Passes all necessary data for order review and completion
         */
        return view('page.checkout.checkout', compact('selectedItems', 'paymentMethods', 'addresses', 'userName', 'firstAddress'));
    }

    /**
     * Display Order Success Page
     * 
     * This method renders the order confirmation page after successful
     * order placement. It shows order details and confirmation information
     * to provide customers with peace of mind about their purchase.
     * 
     * @param Request $request HTTP request containing order ID
     * @return \Illuminate\View\View Order success confirmation page
     */
    public function success(Request $request)
    {
        // Extract Order ID from Request
        /**
         * Order ID Retrieval - Get order ID for confirmation display
         * Used to show specific order information on success page
         */
        $orderId = $request->get('order_id');

        // Render Success Page with Order Information
        return view('page.checkout.checkout_success', compact('orderId'));
    }

    /**
     * Process Order Creation and Payment
     * 
     * This method handles the core checkout processing including order creation,
     * payment processing, inventory updates, and notification sending. It uses
     * database transactions to ensure data consistency and provides comprehensive
     * error handling for various failure scenarios.
     * 
     * @param Request $request HTTP request containing order and payment data
     * @return \Illuminate\Http\RedirectResponse Redirect to success or back with errors
     */
    public function store(Request $request)
    {
        // Get Current User and Extract Request Data
        /**
         * Request Data Extraction - Get all necessary data from checkout form
         * Includes address selection, payment method, special instructions, and payment data
         */
        $user = Auth::user();
        $address = $request->input('address_id');
        $message = $request->input('note', '');            // Customer notes/instructions
        $paymentData = $request->input('payment_data', '{}'); // Additional payment information

        // Form Validation
        /**
         * Input Validation - Ensure required fields are present and valid
         * Validates address selection and payment method against allowed values
         * Provides localized error messages for better user experience
         */
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:' . implode(',', Payment::getAvailableMethods()),
        ], [
            'address_id.required' => ((__('checkout.please_select_address'))),
            'address_id.exists' => ((__('checkout.invalid_address'))),
            'payment_method.required' => ((__('checkout.please_select_payment_method'))),
            'payment_method.in' => ((__('checkout.invalid_payment_method'))),
        ]);

        // Payment Method Validation and Security Checks
        /**
         * Payment Method Security Validation - Ensure payment method is valid string
         * Additional security layer to prevent injection attacks and invalid data
         * Logs security incidents for monitoring and analysis
         */
        $paymentMethod = $request->input('payment_method');
        $allowedMethods = Payment::getAvailableMethods();
        
        // Validate payment method format
        if (!is_string($paymentMethod) || empty(trim($paymentMethod))) {
            \Illuminate\Support\Facades\Log::error('Payment method is not a valid string', [
                'user_id' => $user->id,
                'payment_method' => $paymentMethod,
                'payment_method_type' => gettype($paymentMethod)
            ]);
            
            return redirect()
                ->route('checkout.index')
                ->with('error', ((__('checkout.invalid_payment_method'))));
        }
        
        // Validate payment method against allowed values
        if (!in_array($paymentMethod, $allowedMethods)) {
            \Illuminate\Support\Facades\Log::warning('Invalid payment method attempted', [
                'user_id' => $user->id,
                'payment_method' => $paymentMethod,
                'allowed_methods' => $allowedMethods
            ]);
            
            return redirect()
                ->route('checkout.index')
                ->with('error', ((__('checkout.invalid_payment_method'))));
        }
        
        // Parse and Validate Payment Data
        /**
         * Payment Data Processing - Safely decode JSON payment data
         * Handles potential JSON parsing errors gracefully
         * Ensures payment data is in expected array format
         */
        try {
            $paymentDataArray = json_decode($paymentData, true);
            if (!is_array($paymentDataArray)) {
                $paymentDataArray = [];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment data JSON decode error: ' . $e->getMessage());
            $paymentDataArray = [];
        }

        // Parse and Validate Selected Items
        /**
         * Cart Items Processing - Extract and validate selected items for order
         * Ensures cart data is valid and contains actual items for processing
         * Handles JSON parsing errors and invalid data gracefully
         */
        $itemsJson = $request->input('selected_items_json');
        
        try {
            $selectedItems = json_decode($itemsJson, true);
            if (!is_array($selectedItems) || empty($selectedItems)) {
                throw new \Exception('No items selected for checkout');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Selected items JSON decode error: ' . $e->getMessage());
            return redirect()
                ->route('checkout.index')
                ->with('error', ((__('checkout.cart_data_error'))));
        }

        // Begin Database Transaction for Data Consistency
        /**
         * Transaction Management - Ensure all operations succeed or fail together
         * Critical for maintaining data integrity across multiple database operations
         * Prevents partial order creation or inventory inconsistencies
         */
        DB::beginTransaction();

        try {
            // Create New Order Record
            /**
             * Order Creation - Create main order record with calculated totals
             * Includes shipping fee from configuration and sets initial status to pending
             * Links order to user and selected shipping address
             */
            $order = Order::create([
                'user_id'     => $user->id,
                'total_price' => array_sum(array_column($selectedItems, 'subtotal')) + config('constants.checkout.shipping_fee', 8),
                'status'      => 'pending',
                'address_id'  => $address,
                'message'     => $message,
            ]);

            // Process Each Order Item
            /**
             * Order Details Creation and Inventory Management
             * For each selected item: update inventory, create order detail record
             * This ensures accurate inventory tracking and detailed order records
             */
            foreach ($selectedItems as $item) {
                // Get product and update inventory
                $product = Product::find($item['id']);

                $product->stock_quantity -= $item['quantity']; // Reduce available stock
                $product->save();

                // Create order detail record for this item
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'], // Preserve price at time of order
                ]);
            }

            // Send Admin Notifications for New Orders
            /**
             * Admin Notification System - Alert administrators of new pending orders
             * Enables prompt order processing and customer service
             * Notifies all admin users for comprehensive order management
             */
            if ($order->status === 'pending') {
                // Get current locale from session
                $currentLocale = Session::get('locale', config('app.locale'));
                
                // Notify all admins
                $adminUsers = User::where('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    $admin->notify(new NewOrderPending($order, $currentLocale));
                }

                // Notify only the customer who placed the order
                $customer = User::find($order->user_id);
                if ($customer) {
                    $customer->notify(new CustomerNewOrderPending($order, $currentLocale));
                }
            }

            // Process Payment Through Payment Service
            /**
             * Payment Processing - Handle payment through dedicated service
             * Uses dependency injection to get payment service instance
             * Processes payment based on selected method and order details
             */
            $paymentService = app()->make(\App\Services\PaymentService::class);
            $paymentResult = $paymentService->processPayment($paymentMethod, $order, $paymentDataArray);
            
            // Check payment processing result
            if (!$paymentResult['success']) {
                throw new \Exception($paymentResult['message']);
            }

            // Clean Up Cart Items After Successful Order
            /**
             * Cart Cleanup - Remove ordered items from customer's cart
             * Only removes items that were actually ordered
             * Maintains cart integrity for remaining items
             */
            $cartIds = array_column($selectedItems, 'cart_id');
            Cart::where('user_id', $user->id)
                ->whereIn('id', $cartIds)
                ->delete();

            // Commit Transaction - All operations successful
            DB::commit();

            // Clear Session Data
            /**
             * Session Cleanup - Remove checkout session data after successful order
             * Prevents reuse of order data and cleans up temporary storage
             */
            session()->forget('selectedItems');

            // Redirect to Success Page
            /**
             * Success Redirect - Send customer to order confirmation page
             * Includes success message and order ID for reference
             * Provides positive feedback about successful order completion
             */
            $successMessage = $paymentResult['message'] ?? ((__('checkout.order_success')));
            
            return redirect()
                ->route('checkout.success', ['order_id' => $order->id])
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            // Rollback Transaction on Any Error
            /**
             * Error Handling and Rollback - Undo all changes if any step fails
             * Logs detailed error information for debugging and monitoring
             * Returns user to checkout with error message and preserved form data
             */
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Order creation error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'payment_method' => $paymentMethod,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->route('checkout.index')
                ->withInput() // Preserve form data for user convenience
                ->with('error', ((__('checkout.order_failed'))) . $e->getMessage());
        }
    }
}
