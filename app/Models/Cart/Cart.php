<?php
/**
 * Cart Model
 * 
 * This model represents the shopping cart functionality in the Hanaya Shop e-commerce application.
 * It manages temporary storage of products that customers intend to purchase, supporting both
 * authenticated users and guest sessions for seamless shopping experience.
 * 
 * Key Features:
 * - Dual-mode cart support (authenticated users and guests)
 * - Product quantity management
 * - Session-based cart persistence for guest users
 * - User-based cart persistence for authenticated users
 * - Automatic cart migration from session to user upon login
 * 
 * Database Structure:
 * - product_id: References the product being added to cart
 * - user_id: Links cart to authenticated user (null for guests)
 * - session_id: Links cart to session for guest users
 * - quantity: Number of items of the product in cart
 * - timestamps: Track cart item creation and modification
 * 
 * Business Logic:
 * - Guest carts are identified by session_id
 * - User carts are identified by user_id
 * - Cart items can be updated, removed, or converted to orders
 * - Supports cart persistence across user sessions
 * 
 * @package App\Models\Cart
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Model;              // Base Eloquent model
use Illuminate\Database\Eloquent\Factories\HasFactory; // Factory pattern support
use App\Models\Product\Product;                     // Product relationship
use App\Models\User;                                // User relationship

/**
 * Cart Model Class
 * 
 * Represents shopping cart items with support for both authenticated users
 * and guest sessions. Manages product quantities and provides relationships
 * to products and users for complete cart functionality.
 */
class Cart extends Model
{
    use HasFactory; // Enable model factory support for testing and seeding

    /**
     * Database Table Configuration
     * 
     * Specifies the database table name for cart items storage.
     * Contains cart entries with product, user, and quantity information.
     */
    protected $table = 'carts'; // Shopping cart storage table

    /**
     * Mass Assignment Protection
     * 
     * Defines which attributes can be mass-assigned through create() and update() methods.
     * Includes all essential cart fields for product management and user association.
     * 
     * Security Note:
     * - Only specified fields can be mass-assigned
     * - Prevents injection of unauthorized data
     * - Maintains data integrity during cart operations
     */
    protected $fillable = [
        'product_id',  // Product reference for cart item
        'user_id',     // User reference for authenticated cart (null for guests)
        'session_id',  // Session reference for guest cart (null for authenticated users)
        'quantity',    // Number of product units in cart
    ];

    /**
     * Date Casting Configuration
     * 
     * Automatically casts specified fields to Carbon date instances.
     * Enables easy date manipulation and formatting for cart timestamps.
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Timestamp Management
     * 
     * Enables automatic timestamp management for cart items.
     * Tracks when cart items are created and last modified.
     */
    public $timestamps = true;

    /**
     * Product Relationship
     * 
     * Defines the relationship between cart items and products.
     * Each cart item belongs to exactly one product, enabling
     * access to complete product information from cart context.
     * 
     * Relationship Type: Many-to-One (Many cart items to One product)
     * 
     * Usage:
     * - Access product details: $cartItem->product->name
     * - Get product pricing: $cartItem->product->price
     * - Check stock availability: $cartItem->product->stock_quantity
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * User Relationship
     * 
     * Defines the relationship between cart items and users.
     * Each cart item belongs to exactly one user (when authenticated).
     * This relationship is null for guest cart items.
     * 
     * Relationship Type: Many-to-One (Many cart items to One user)
     * 
     * Usage:
     * - Access user details: $cartItem->user->name
     * - Get user preferences: $cartItem->user->email
     * - Check user status: $cartItem->user->isUser()
     * 
     * Cart Context:
     * - Authenticated users: user_id is populated, session_id may be null
     * - Guest users: user_id is null, session_id is populated
     * - Cart migration: When guest logs in, user_id is set and session_id may be cleared
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
