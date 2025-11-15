<?php

/**
 * Product Review Model
 *
 * This model represents customer reviews and ratings for products in the Hanaya Shop
 * e-commerce application. It manages customer feedback, ratings, and review content
 * to provide social proof and product quality insights for other customers.
 *
 * Key Features:
 * - Star rating system (1-5 stars)
 * - Written review comments
 * - Review image attachments
 * - User and product association
 * - Order verification for authentic reviews
 * - Rating normalization and default values
 *
 * Review System:
 * - Only customers who purchased products can review
 * - One review per user per product per order
 * - Reviews include rating (required) and comment (optional)
 * - Images can be attached to reviews for visual feedback
 * - Default rating of 5 stars if no rating provided
 *
 * Business Value:
 * - Builds customer trust and confidence
 * - Provides product quality feedback
 * - Influences purchase decisions
 * - Enables product improvement insights
 * - Supports social commerce features
 *
 * Verification:
 * - Reviews linked to actual orders for authenticity
 * - Only verified purchasers can leave reviews
 * - Prevents fake or spam reviews
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Models\Product;

use App\Models\Order\Order; // Factory pattern support
use App\Models\User;                // Base Eloquent model
use Illuminate\Database\Eloquent\Factories\HasFactory;                                   // User relationship
// Product relationship
use Illuminate\Database\Eloquent\Model;                            // Order relationship for verification

/**
 * Review Model Class
 *
 * Represents customer product reviews with rating system, comments,
 * and verification through order association. Provides comprehensive
 * review functionality for e-commerce social proof.
 */
class Review extends Model
{
    use HasFactory; // Enable model factory support for testing and seeding

    /**
     * Database Table Configuration
     *
     * Specifies the database table name for review storage.
     * Contains customer reviews with ratings, comments, and associations.
     */
    protected $table = 'reviews';

    /**
     * Mass Assignment Protection
     *
     * Defines which attributes can be mass-assigned through create() and update() methods.
     * Includes all essential review fields for customer feedback management.
     *
     * Security Features:
     * - Only specified fields can be mass-assigned
     * - Prevents injection of unauthorized review data
     * - Maintains review integrity and authenticity
     */
    protected $fillable = [
        'rating',      // Star rating (1-5 stars)
        'comment',     // Written review text
        'image_path',  // Optional review image attachment
        'product_id',  // Product being reviewed
        'order_id',    // Order reference for review verification
        'user_id',     // Customer who wrote the review
    ];

    /**
     * Attribute Casting Configuration
     *
     * Automatically casts specified attributes to appropriate data types.
     * Ensures consistent data handling for ratings and timestamps.
     *
     * Cast Configuration:
     * - rating: Integer type for star rating calculations
     * - created_at: DateTime for review submission timestamp
     * - updated_at: DateTime for review modification timestamp
     */
    protected $casts = [
        'rating' => 'integer',        // Star rating as integer (1-5)
        'created_at' => 'datetime',   // Review creation timestamp
        'updated_at' => 'datetime',   // Review update timestamp
    ];

    /**
     * Timestamp Management
     *
     * Enables automatic timestamp management for review records.
     * Tracks when reviews are created and last modified for moderation.
     */
    public $timestamps = true;

    /**
     * Product Relationship
     *
     * Defines the relationship between reviews and products.
     * Each review belongs to exactly one product, enabling
     * product-specific review display and rating calculations.
     *
     * Relationship Type: Many-to-One (Many reviews to One product)
     *
     * Usage:
     * - Access product details: $review->product->name
     * - Get product category: $review->product->category->name
     * - Check product availability: $review->product->stock_quantity
     *
     * Business Context:
     * - Products can have multiple reviews from different customers
     * - Reviews contribute to overall product rating
     * - Product ratings influence search ranking and recommendations
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
     * Defines the relationship between reviews and users.
     * Each review belongs to exactly one user, providing
     * reviewer identification and review authenticity.
     *
     * Relationship Type: Many-to-One (Many reviews to One user)
     *
     * Usage:
     * - Access reviewer name: $review->user->name
     * - Get reviewer email: $review->user->email
     * - Check reviewer status: $review->user->isUser()
     *
     * Business Context:
     * - Users can write multiple reviews for different products
     * - Only verified customers (with orders) can review
     * - User information displayed with reviews for credibility
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order Relationship
     *
     * Defines the relationship between reviews and orders.
     * Each review belongs to exactly one order, providing
     * verification that the reviewer actually purchased the product.
     *
     * Relationship Type: Many-to-One (Many reviews to One order)
     *
     * Usage:
     * - Verify purchase: $review->order->status === 'completed'
     * - Get order details: $review->order->total_price
     * - Check order date: $review->order->created_at
     *
     * Business Context:
     * - Only customers with completed orders can review products
     * - Prevents fake reviews from non-purchasers
     * - Ensures review authenticity and credibility
     * - Links review to specific purchase transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Rating Attribute Accessor
     *
     * Provides default rating value handling when rating is null or empty.
     * Ensures consistent rating display and prevents null rating issues.
     *
     * Default Behavior:
     * - Returns actual rating value if provided
     * - Returns 5 stars if rating is null or empty
     * - Maintains rating system integrity
     *
     * Usage:
     * - Automatic in Eloquent attribute access: $review->rating
     * - No manual null checking required
     * - Consistent rating values across application
     *
     * @param  mixed  $value  The actual rating value from database
     * @return int The rating value or default 5 if null
     */
    public function getRatingAttribute($value)
    {
        return $value ?? 5; // Default to 5 stars if no rating provided
    }

    /**
     * Product-Specific Review Scope
     *
     * Provides a query scope to filter reviews for a specific product.
     * Enables efficient product review retrieval and display.
     *
     * Usage:
     * - Get product reviews: Review::forProduct($productId)->get()
     * - Chain with other queries: Review::forProduct($productId)->latest()->paginate()
     * - Filter by product: $query->forProduct(123)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  Query builder instance
     * @param  int  $productId  Product ID to filter reviews for
     * @return \Illuminate\Database\Eloquent\Builder Modified query builder
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * User-Specific Review Scope
     *
     * Provides a query scope to filter reviews written by a specific user.
     * Enables efficient user review history retrieval and display.
     *
     * Usage:
     * - Get user reviews: Review::forUser($userId)->get()
     * - User review history: Review::forUser($userId)->with('product')->latest()->get()
     * - Filter by user: $query->forUser(456)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  Query builder instance
     * @param  int  $userId  User ID to filter reviews for
     * @return \Illuminate\Database\Eloquent\Builder Modified query builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
