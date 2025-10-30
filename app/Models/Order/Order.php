<?php
/**
 * Order Model
 * 
 * This model represents customer orders in the Hanaya Shop e-commerce application.
 * It manages the complete order lifecycle from creation to completion, including
 * order status tracking, payment processing, shipping information, and customer
 * communication. The model serves as the central hub for order management operations.
 * 
 * Key Features:
 * - Order lifecycle management (pending, processing, shipped, completed, cancelled)
 * - Customer order history and tracking
 * - Payment integration and processing
 * - Shipping address management
 * - Order total calculations and pricing
 * - Customer messaging and special instructions
 * - Order detail line items management
 * - Review system integration for post-purchase feedback
 * - Administrative order management tools
 * 
 * Database Relationships:
 * - Belongs to User (many-to-one) - Customer who placed the order
 * - Belongs to Address (many-to-one) - Shipping/billing address
 * - Has many Order Details (one-to-many) - Individual product line items
 * - Has one/many Payments (one-to-one/one-to-many) - Payment transactions
 * - Has many Reviews (one-to-many) - Post-purchase product reviews
 * 
 * Order Status Flow:
 * pending → processing → shipped → completed
 *     ↓
 * cancelled (can occur from pending or processing)
 * 
 * @package App\Models\Order
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Factory support for testing
use Illuminate\Database\Eloquent\Model;                // Base Eloquent model class
use Illuminate\Foundation\Auth\User;     // User model for customer relationships
use App\Models\Product\Review;           // Review model for post-purchase feedback
use App\Models\Address;                  // Address model for shipping information

/**
 * Order Model Class
 * 
 * Eloquent model representing customer orders in the e-commerce system.
 * Manages order data, status tracking, and relationships with related entities
 * such as customers, products, payments, and shipping addresses.
 */
class Order extends Model
{
    use HasFactory; // Enable model factories for testing and database seeding

    /**
     * Database Table Configuration
     * 
     * Specifies the database table associated with this model.
     * Explicitly defined for clarity and consistency.
     */
    protected $table = 'orders';

    /**
     * Mass Assignable Attributes
     * 
     * Defines which attributes can be mass-assigned using create() or fill() methods.
     * This provides security by preventing unauthorized attribute modification
     * while allowing convenient order creation and updates.
     * 
     * @var array<string> List of attributes that can be mass assigned
     */
    protected $fillable = [
        'user_id',     // Foreign key to users table - customer who placed the order
        'total_price', // Total order amount including taxes and fees
        'status',      // Current order status (pending, processing, shipped, completed, cancelled)
        'message',     // Customer message or special instructions for the order
        'address_id',  // Foreign key to addresses table - shipping/billing address
    ];

    // ===== ELOQUENT ACCESSORS AND MUTATORS =====

    /**
     * Total Amount Accessor
     * 
     * Provides an alternative attribute name for accessing the total order amount.
     * This accessor allows views and other code to use 'total_amount' instead
     * of 'total_price' for better semantic meaning and consistency with financial terminology.
     * 
     * @return float The total order amount
     */
    public function getTotalAmountAttribute()
    {
        return $this->total_price;
    }

    /**
     * Total Amount Mutator
     * 
     * Allows setting the total order amount using 'total_amount' attribute name.
     * This mutator provides flexibility in how the total amount is set while
     * maintaining the underlying 'total_price' database field structure.
     * 
     * @param float $value The total amount value to set
     * @return void
     */
    public function setTotalAmountAttribute($value)
    {
        $this->attributes['total_price'] = $value;
    }

    /**
     * Date Attributes Configuration
     * 
     * Specifies attributes that should be treated as Carbon date instances.
     * These attributes will be automatically cast to Carbon objects for
     * convenient date manipulation and formatting.
     * 
     * @var array<string> List of date attributes
     */
    protected $date = [
        'created_at',  // Order creation timestamp for tracking order history
    ];

    /**
     * Timestamp Management
     * 
     * Enables automatic handling of created_at and updated_at timestamps.
     * Laravel will automatically maintain these fields during model operations
     * to track order creation and modification times.
     * 
     * @var bool Whether to use automatic timestamps
     */
    public $timestamps = true;

    // ===== RELATIONSHIP DEFINITIONS =====

    /**
     * Customer Relationship
     * 
     * Defines the many-to-one relationship between orders and users.
     * Each order belongs to exactly one customer (user), while customers
     * can have multiple orders. This relationship enables order history
     * tracking and customer service functionality.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order Details Relationship
     * 
     * Defines the one-to-many relationship between orders and order details.
     * Each order can contain multiple line items (products with quantities),
     * enabling complex order composition and detailed order tracking.
     * This relationship is crucial for order fulfillment and inventory management.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Primary Payment Relationship
     * 
     * Defines the one-to-one relationship between orders and the primary payment.
     * Each order typically has one primary payment transaction, though
     * the payments() relationship (below) handles multiple payment scenarios.
     * This relationship provides quick access to main payment information.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    /**
     * All Payments Relationship
     * 
     * Defines the one-to-many relationship between orders and payments.
     * This relationship handles scenarios where orders may have multiple
     * payment transactions (partial payments, refunds, adjustments).
     * Provides comprehensive payment tracking and financial auditing.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Reviews Relationship
     * 
     * Defines the one-to-many relationship between orders and product reviews.
     * After order completion, customers can review products they purchased.
     * This relationship links reviews to specific orders for authenticity
     * and helps prevent fake reviews by ensuring purchase verification.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function review()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Shipping Address Relationship
     * 
     * Defines the many-to-one relationship between orders and addresses.
     * Each order is associated with a specific shipping/billing address,
     * enabling proper order delivery and customer communication.
     * Multiple orders can use the same address for repeat customers.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
