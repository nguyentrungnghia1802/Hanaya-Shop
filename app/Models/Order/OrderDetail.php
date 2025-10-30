<?php
/**
 * Order Detail Model
 * 
 * This model represents individual line items within customer orders in the Hanaya Shop
 * e-commerce application. Each OrderDetail record represents a specific product with its
 * quantity and price within a parent order. This model enables detailed order tracking,
 * inventory management, and accurate order fulfillment.
 * 
 * Key Features:
 * - Individual product line item management within orders
 * - Quantity tracking for each product in an order
 * - Historical price preservation for accurate order records
 * - Product relationship linking for order fulfillment
 * - Order composition and breakdown functionality
 * - Sales analytics and reporting support
 * - Inventory tracking and stock management
 * - Revenue calculation and financial reporting
 * 
 * Database Relationships:
 * - Belongs to Order (many-to-one) - Parent order containing this line item
 * - Belongs to Product (many-to-one) - Specific product being ordered
 * 
 * Business Logic:
 * - Preserves product price at time of order to maintain order integrity
 * - Tracks quantity for accurate inventory management
 * - Enables order modification and line item adjustments
 * - Supports partial fulfillment and backorder scenarios
 * 
 * @package App\Models\Order
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Factory support for testing
use Illuminate\Database\Eloquent\Model;                // Base Eloquent model class
use App\Models\Product\Product; // Product model for line item details

/**
 * Order Detail Model Class
 * 
 * Eloquent model representing individual line items within customer orders.
 * Each instance represents one product with its specific quantity and price
 * within a larger order, enabling detailed order composition and tracking.
 */
class OrderDetail extends Model
{
    use HasFactory; // Enable model factories for testing and database seeding

    /**
     * Database Table Configuration
     * 
     * Specifies the database table associated with this model.
     * Uses plural form following Laravel conventions for pivot/detail tables.
     */
    protected $table = 'order_details';

    /**
     * Mass Assignable Attributes
     * 
     * Defines which attributes can be mass-assigned using create() or fill() methods.
     * This provides security by preventing unauthorized attribute modification
     * while allowing convenient order detail creation and updates.
     * 
     * Key considerations:
     * - order_id: Links this line item to its parent order
     * - product_id: Identifies the specific product being ordered
     * - quantity: Number of units of this product in the order
     * - price: Historical price of the product at time of order (important for order integrity)
     * 
     * @var array<string> List of attributes that can be mass assigned
     */
    protected $fillable = [
        'order_id',   // Foreign key to orders table - parent order containing this line item
        'product_id', // Foreign key to products table - specific product being ordered
        'quantity',   // Number of units of this product in the order (must be positive integer)
        'price',      // Unit price of the product at time of order (preserves historical pricing)
    ];

    // ===== RELATIONSHIP DEFINITIONS =====

    /**
     * Product Relationship
     * 
     * Defines the many-to-one relationship between order details and products.
     * Each order detail line item belongs to exactly one product, while products
     * can appear in multiple order details across different orders. This relationship
     * enables access to complete product information for order fulfillment, display,
     * and inventory management.
     * 
     * Use cases:
     * - Displaying product information in order summaries
     * - Inventory tracking and stock management
     * - Product performance analytics
     * - Order fulfillment and shipping processes
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Order Relationship
     * 
     * Defines the many-to-one relationship between order details and orders.
     * Each order detail belongs to exactly one parent order, while orders
     * contain multiple order detail line items. This relationship enables
     * order composition, total calculation, and order management operations.
     * 
     * Use cases:
     * - Calculating order totals from line items
     * - Order status tracking and updates
     * - Customer order history and tracking
     * - Order modification and line item management
     * - Financial reporting and revenue calculation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
