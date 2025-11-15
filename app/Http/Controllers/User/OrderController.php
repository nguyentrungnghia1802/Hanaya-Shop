<?php

/**
 * User Order Management Controller
 *
 * This controller handles order management functionality for customers in the Hanaya Shop
 * e-commerce application. It provides comprehensive order viewing, tracking, cancellation,
 * and completion features for customer order lifecycle management.
 *
 * Key Features:
 * - Order listing with pagination and status tracking
 * - Detailed order view with product information
 * - Order cancellation with inventory restoration
 * - Order completion confirmation for delivered items
 * - Review eligibility checking and management
 * - Payment status integration and display
 * - Admin notification system for order changes
 * - Database transaction safety for data integrity
 *
 * Order Status Flow:
 * - pending → processing → shipped → completed
 * - Any status → cancelled (with inventory restoration)
 *
 * Review Integration:
 * - Review eligibility based on order completion
 * - Duplicate review prevention
 * - Review status tracking per order item
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\Payment;
use App\Models\Product\Product;
use App\Models\Product\Review;
use App\Models\User;
use App\Notifications\OrderCancelledNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Order Controller Class
 *
 * Manages customer order operations including viewing, tracking,
 * cancellation, and completion with integrated review management.
 */
class OrderController extends Controller
{
    /**
     * Display Customer Order List
     *
     * Retrieves and displays all orders for the authenticated customer with
     * pagination, order details, and review eligibility checking. Provides
     * comprehensive order overview with product information and review status.
     *
     * Features:
     * - Paginated order listing (10 orders per page)
     * - Order details with product information
     * - Review eligibility determination per order item
     * - Existing review detection and status
     * - Order status tracking and display
     *
     * @return \Illuminate\View\View Order index view with paginated orders and review status
     */
    /**
     * Display Customer Order List
     *
     * Retrieves and displays all orders for the authenticated customer with
     * pagination, order details, and review eligibility checking. Provides
     * comprehensive order overview with product information and review status.
     *
     * Features:
     * - Paginated order listing (10 orders per page)
     * - Order details with product information
     * - Review eligibility determination per order item
     * - Existing review detection and status
     * - Order status tracking and display
     *
     * @return \Illuminate\View\View Order index view with paginated orders and review status
     */
    public function index()
    {
        // Get Current User Orders
        /**
         * Order Retrieval - Get all orders for authenticated customer
         * Includes order details with products and existing reviews
         * Orders sorted by creation date (newest first) with pagination
         */
        $userId = Auth::id();
        $orders = Order::with(['orderDetail.product', 'review'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate with 10 orders per page

        // Review Eligibility Configuration
        /**
         * Review Status Configuration - Get review eligibility settings
         * Determines which order status allows customers to leave reviews
         * Typically 'completed' status enables review functionality
         */
        $canReviewStatus = config('constants.review.can_review_status');

        // Process Review Status for Each Order Item
        /**
         * Review Status Processing - Determine review eligibility for each order item
         *
         * For each order detail:
         * - Check if customer already reviewed this product for this order
         * - Determine if review is allowed based on order status
         * - Set review flags for template display
         */
        foreach ($orders as $order) {
            foreach ($order->orderDetail as $detail) {
                // Check if user has already reviewed this product in this order
                $existingReview = Review::where('user_id', $userId)
                    ->where('product_id', $detail->product_id)
                    ->where('order_id', $order->id)
                    ->first();

                $detail->can_review = ($order->status === $canReviewStatus && ! $existingReview);
                $detail->has_review = (bool) $existingReview;
                $detail->review = $existingReview;
            }
        }

        return view('page.order.index', compact('orders'));
    }

    /**
     * Display Detailed Order Information
     *
     * Shows comprehensive details for a specific order including all products,
     * payment status, and review eligibility. Ensures customers can only view
     * their own orders with complete product and payment information.
     *
     * Features:
     * - Complete order details with product information
     * - Payment status display and tracking
     * - Review eligibility checking per order item
     * - Security validation for order ownership
     *
     * @param  int  $orderId  Order ID to display
     * @return \Illuminate\View\View Order detail view with comprehensive order information
     */
    public function show($orderId)
    {
        // Get Order with Security Validation
        /**
         * Secure Order Retrieval - Get order with ownership validation
         * Ensures customers can only view their own orders
         * Includes all necessary relationships for complete order display
         */
        $userId = Auth::id();
        $order = Order::with(['orderDetail.product', 'review'])
            ->where('user_id', $userId)
            ->findOrFail($orderId);

        // Payment Information Retrieval
        /**
         * Payment Status Processing - Get payment information for order
         * Retrieves payment records and extracts status for display
         * Provides empty string fallback if no payment information exists
         */
        $canReviewStatus = config('constants.review.can_review_status');
        $payment = Payment::where('order_id', $order->id)->get();
        $payment_status = $payment->first()->payment_status ?? '';

        // Review Status Processing
        /**
         * Individual Item Review Status - Process review eligibility for each product
         *
         * For each order detail:
         * - Check existing reviews to prevent duplicates
         * - Determine review eligibility based on order status
         * - Provide review status flags for template logic
         */
        foreach ($order->orderDetail as $detail) {
            // Check if user has already reviewed this product in this order
            $existingReview = Review::where('user_id', $userId)
                ->where('product_id', $detail->product_id)
                ->where('order_id', $order->id)
                ->first();

            $detail->can_review = ($order->status === $canReviewStatus && ! $existingReview);
            $detail->has_review = (bool) $existingReview;
            $detail->review = $existingReview;
        }

        return view('page.order.show', compact('order', 'payment_status'));
    }

    /**
     * Cancel Customer Order
     *
     * Handles order cancellation with comprehensive inventory restoration and
     * notification system. Uses database transactions to ensure data consistency
     * and notifies administrators of the cancellation.
     *
     * Cancellation Process:
     * - Restore product inventory for all order items
     * - Update payment status to failed
     * - Change order status to cancelled
     * - Send notifications to administrators
     * - Provide customer feedback on success/failure
     *
     * @param  int  $orderId  Order ID to cancel
     * @return \Illuminate\Http\RedirectResponse Redirect to order list with status message
     */
    /**
     * Cancel Customer Order
     *
     * Handles order cancellation with comprehensive inventory restoration and
     * notification system. Uses database transactions to ensure data consistency
     * and notifies administrators of the cancellation.
     *
     * Cancellation Process:
     * - Restore product inventory for all order items
     * - Update payment status to failed
     * - Change order status to cancelled
     * - Send notifications to administrators
     * - Provide customer feedback on success/failure
     *
     * @param  int  $orderId  Order ID to cancel
     * @return \Illuminate\Http\RedirectResponse Redirect to order list with status message
     */
    public function cancel($orderId)
    {
        // Get Order for Cancellation
        /**
         * Order Retrieval - Find order for cancellation processing
         * Uses findOrFail to ensure order exists before processing
         * Prevents cancellation of non-existent orders
         */
        $order = Order::findOrFail($orderId);

        // Begin Transaction for Data Consistency
        /**
         * Transaction Management - Ensure all cancellation operations succeed together
         * Critical for maintaining data integrity during inventory restoration
         * Prevents partial cancellation states and data inconsistencies
         */
        DB::beginTransaction();
        try {
            // Update Payment Status
            /**
             * Payment Status Update - Mark payment as failed for cancelled orders
             * Reflects order cancellation in payment system
             * Enables proper financial tracking and reporting
             */
            $payment = Payment::where('order_id', $order->id)->get();
            $payment->payment_status = 'failed';

            // Restore Product Inventory
            /**
             * Inventory Restoration - Return products to available stock
             *
             * For each order item:
             * - Add ordered quantity back to product stock
             * - Update product availability for future orders
             * - Maintain accurate inventory levels
             */
            foreach ($order->orderDetail as $detail) {
                $product = $detail->product;
                $product->stock_quantity += $detail->quantity;
                $product->save();
            }

            // Update Order Status
            /**
             * Order Status Update - Mark order as cancelled
             * Changes order from current status to cancelled state
             * Prevents further processing of cancelled orders
             */
            $order->status = 'cancelled';
            $order->save();

            // Admin Notification System
            /**
             * Cancellation Notifications - Alert administrators of order cancellation
             * Enables prompt customer service and order management response
             * Notifies all admin users for comprehensive order tracking
             */
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderCancelledNotification($order));
            }

            // Commit Transaction
            DB::commit();

            // Success Response
            return redirect()->route('order.index')->with('success', ((__('orders.order_has_been_cancelled'))));
        } catch (\Exception $e) {
            // Rollback on Error
            /**
             * Error Handling - Rollback all changes if any operation fails
             * Maintains data consistency by undoing partial changes
             * Provides error feedback to customer for troubleshooting
             */
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while cancelling the order: '.$e->getMessage());
        }
    }

    /**
     * Mark Order as Received
     *
     * Allows customers to confirm receipt of their delivered orders.
     * Updates order status to completed when customer confirms delivery.
     * Typically called when customer receives their shipment successfully.
     *
     * Features:
     * - Order status update to completed
     * - Customer confirmation of successful delivery
     * - Order lifecycle completion
     *
     * @param  int  $orderId  Order ID to mark as received
     * @return \Illuminate\Http\RedirectResponse Redirect back with success confirmation
     */
    public function receive($orderId)
    {
        // Update Order Status to Completed
        /**
         * Order Completion - Mark order as successfully delivered
         * Customer confirmation of receipt changes status to completed
         * Enables review functionality and order lifecycle closure
         */
        $order = Order::findOrFail($orderId);
        $order->status = 'completed';
        $order->save();

        // Success Confirmation
        return redirect()->back()->with('success', 'Order has been marked as received.');
    }
}
