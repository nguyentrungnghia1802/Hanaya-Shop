<?php

/**
 * Payment Model
 *
 * This model represents payment transactions in the Hanaya Shop e-commerce application.
 * It manages payment processing, status tracking, and transaction records for customer orders.
 * Supports multiple payment methods and provides comprehensive payment lifecycle management.
 *
 * Key Features:
 * - Multiple payment method support (COD, Credit Card, PayPal)
 * - Payment status tracking throughout transaction lifecycle
 * - Transaction ID management for payment reconciliation
 * - Order association for payment-order relationship
 * - Default payment configurations for streamlined processing
 *
 * Payment Methods:
 * - cash_on_delivery: Cash payment upon delivery
 * - credit_card: Credit/debit card payment processing
 * - paypal: PayPal payment gateway integration
 *
 * Payment Statuses:
 * - pending: Payment initiated but not completed
 * - completed: Payment successfully processed
 * - failed: Payment processing failed
 * - refunded: Payment refunded to customer
 *
 * Business Logic:
 * - Each payment belongs to exactly one order
 * - Transaction IDs provide external payment system references
 * - Status tracking enables order fulfillment decisions
 * - Default values ensure consistent payment initialization
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Factory pattern support
use Illuminate\Database\Eloquent\Model;                // Base Eloquent model

/**
 * Payment Model Class
 *
 * Represents payment transactions with comprehensive payment lifecycle management.
 * Handles payment method selection, status tracking, and transaction recording
 * for complete e-commerce payment processing.
 */
class Payment extends Model
{
    use HasFactory; // Enable model factory support for testing and seeding

    /**
     * Database Table Configuration
     *
     * Specifies the database table name for payment transaction storage.
     * Contains payment records with method, status, and transaction details.
     */
    protected $table = 'payments';

    /**
     * Mass Assignment Protection
     *
     * Defines which attributes can be mass-assigned through create() and update() methods.
     * Includes all essential payment fields for transaction management and order association.
     *
     * Security Features:
     * - Only specified fields can be mass-assigned
     * - Prevents injection of unauthorized payment data
     * - Maintains transaction integrity during payment processing
     */
    protected $fillable = [
        'payment_method',  // Payment method used (COD, credit card, PayPal)
        'payment_status',  // Current payment status (pending, completed, failed, refunded)
        'transaction_id',  // External payment system transaction reference
        'order_id',        // Associated order reference
    ];

    /**
     * Default Attribute Values
     *
     * Sets default values for payment attributes when creating new payment records.
     * Ensures consistent payment initialization and proper default configurations.
     *
     * Default Configuration:
     * - payment_method: 'cash_on_delivery' (most common payment method)
     * - payment_status: 'pending' (initial payment state)
     */
    protected $attributes = [
        'payment_method' => 'cash_on_delivery', // Default to COD for accessibility
        'payment_status' => 'pending',          // Default to pending status
    ];

    /**
     * Attribute Casting Configuration
     *
     * Automatically casts specified attributes to appropriate data types.
     * Ensures consistent data handling for payment method and status fields.
     *
     * Cast Configuration:
     * - payment_method: String type for method identification
     * - payment_status: String type for status tracking
     */
    protected $casts = [
        'payment_method' => 'string', // Payment method as string
        'payment_status' => 'string', // Payment status as string
    ];

    /**
     * Date Field Configuration
     *
     * Specifies which fields should be treated as dates for automatic casting.
     * Enables easy date manipulation and formatting for payment timestamps.
     *
     * Note: There appears to be a typo in the original code ($date instead of $dates)
     * This should be corrected in future updates for proper date handling.
     */
    protected $date = [
        'created_at', // Payment creation timestamp
    ];

    /**
     * Timestamp Management
     *
     * Enables automatic timestamp management for payment records.
     * Tracks when payments are created and last modified for audit purposes.
     */
    public $timestamps = true;

    /**
     * Order Relationship
     *
     * Defines the relationship between payments and orders.
     * Each payment belongs to exactly one order, establishing
     * the payment-order association for transaction tracking.
     *
     * Relationship Type: Many-to-One (Many payments to One order)
     *
     * Usage:
     * - Access order details: $payment->order->total_price
     * - Get order status: $payment->order->status
     * - Check order items: $payment->order->orderDetails
     *
     * Business Context:
     * - Orders may have multiple payment attempts (failed, retry, partial)
     * - Only completed payments should trigger order fulfillment
     * - Payment status affects order processing workflow
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get Available Payment Methods
     *
     * Provides a list of all supported payment methods in the system.
     * Used for payment method validation, form options, and system configuration.
     *
     * Payment Method Details:
     * - cash_on_delivery: Cash payment upon delivery (COD)
     * - credit_card: Credit/debit card payment processing
     * - paypal: PayPal payment gateway integration
     *
     * Usage:
     * - Form dropdown options: Payment::getAvailableMethods()
     * - Validation rules: Rule::in(Payment::getAvailableMethods())
     * - System configuration: Display available payment options
     *
     * @return array List of available payment method identifiers
     */
    public static function getAvailableMethods(): array
    {
        return ['cash_on_delivery', 'credit_card', 'paypal'];
    }
}
