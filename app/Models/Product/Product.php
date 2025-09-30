<?php

/**
 * Product Model
 *
 * This model represents products in the Hanaya Shop e-commerce application.
 * It handles all product-related data, relationships, and business logic including
 * pricing calculations, inventory management, category associations, and customer interactions.
 *
 * Key Features:
 * - Product information management (name, description, price, stock)
 * - Category relationships and classification
 * - Discount and pricing calculations
 * - Review and rating system integration
 * - Shopping cart functionality support
 * - Order tracking and sales analytics
 * - View count tracking for popularity metrics
 * - Automated timestamp management
 *
 * Database Relationships:
 * - Belongs to Category (many-to-one)
 * - Has many Order Details (one-to-many)
 * - Has many Cart Items (one-to-many)
 * - Has many Reviews (one-to-many)
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Models\Product;

use App\Models\Cart\Cart; // Factory support for testing/seeding
use App\Models\Order\OrderDetail;                // Base Eloquent model class
use Illuminate\Database\Eloquent\Factories\HasFactory;  // Order details for sales tracking
// Product categories for organization
use Illuminate\Database\Eloquent\Model;         // Shopping cart functionality

// Customer reviews and ratings

/**
 * Product Model Class
 *
 * Eloquent model representing products in the e-commerce system.
 * Manages product data, relationships, and business logic operations.
 */
class Product extends Model
{
    use HasFactory; // Enables model factories for testing and database seeding

    /**
     * Database Table Configuration
     *
     * Specifies the database table associated with this model.
     * Explicitly defined for clarity and consistency.
     */
    protected $table = 'products';

    /**
     * Mass Assignable Attributes
     *
     * Defines which attributes can be mass-assigned using create() or fill() methods.
     * This provides security by preventing unauthorized attribute modification
     * while allowing convenient bulk operations.
     *
     * @var array<string> List of attributes that can be mass assigned
     */
    protected $fillable = [
        'name',              // Product name/title
        'descriptions',      // Detailed product description
        'price',            // Base price before any discounts
        'stock_quantity',   // Current inventory level
        'image_url',        // Product image URL or file path
        'category_id',      // Foreign key to categories table
        'discount_percent', // Percentage discount (0-100)
        'view_count',       // Number of times product has been viewed
    ];

    /**
     * Date Attributes Configuration
     *
     * Specifies attributes that should be treated as Carbon date instances.
     * These attributes will be automatically cast to Carbon objects for
     * convenient date manipulation and formatting.
     *
     * @var array<string> List of date attributes
     */
    protected $dates = [
        'created_at',  // Product creation timestamp
    ];

    /**
     * Timestamp Management
     *
     * Enables automatic handling of created_at and updated_at timestamps.
     * Laravel will automatically maintain these fields during model operations.
     *
     * @var bool Whether to use automatic timestamps
     */
    public $timestamps = true;

    // ===== RELATIONSHIP DEFINITIONS =====

    /**
     * Category Relationship
     *
     * Defines the many-to-one relationship between products and categories.
     * Each product belongs to exactly one category, while categories can
     * contain multiple products. This relationship enables product organization
     * and filtering by category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Order Details Relationship
     *
     * Defines the one-to-many relationship between products and order details.
     * This relationship tracks all instances where this product has been ordered,
     * enabling sales analytics, inventory tracking, and order history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Shopping Cart Relationship
     *
     * Defines the one-to-many relationship between products and cart items.
     * This relationship manages products added to customers' shopping carts,
     * supporting e-commerce cart functionality and purchase flow.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Reviews Relationship
     *
     * Defines the one-to-many relationship between products and customer reviews.
     * This relationship enables the review and rating system, supporting
     * customer feedback, product ratings, and social proof features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ===== BUSINESS LOGIC METHODS =====

    /**
     * Calculate Discounted Price with Optional Override
     *
     * Calculates the final price after applying a discount percentage.
     * Allows for dynamic discount calculation with optional override
     * of the stored discount percentage. This method is useful for
     * promotional pricing, flash sales, or custom discount scenarios.
     *
     * @param  float|null  $discountPercentage  Optional discount override (0-100)
     * @return float The calculated discounted price
     */
    public function getDiscountedPrice($discountPercentage = null)
    {
        // Use provided discount or fall back to product's stored discount
        $discount = $discountPercentage ?? $this->discount_percent;

        // Calculate discounted price: original price - (original price * discount / 100)
        return $this->price - ($this->price * $discount / 100);
    }

    // ===== ELOQUENT ACCESSORS =====

    /**
     * Discounted Price Accessor
     *
     * Eloquent accessor that automatically calculates the discounted price
     * when accessing the 'discounted_price' attribute. This provides a
     * convenient way to get the final price without manual calculation.
     * Returns the original price if no discount is applied.
     *
     * @return float The final price after discount application
     */
    public function getDiscountedPriceAttribute()
    {
        // Check if product has a discount applied
        if ($this->discount_percent > 0) {
            // Calculate and return discounted price
            return $this->price - ($this->price * $this->discount_percent / 100);
        }

        // Return original price if no discount
        return $this->price;
    }

    /**
     * Average Rating Accessor
     *
     * Eloquent accessor that calculates and returns the average rating
     * for this product based on all customer reviews. Provides a convenient
     * way to access product rating without manual calculation. Returns a
     * default rating of 5 stars if no reviews exist (optimistic default).
     *
     * @return float Average rating from customer reviews (1-5 scale)
     */
    public function getAverageRatingAttribute()
    {
        // Calculate average rating from reviews, default to 5 if no reviews
        return $this->reviews()->avg('rating') ?? 5;
    }

    /**
     * Review Count Accessor
     *
     * Eloquent accessor that returns the total number of reviews for this product.
     * Provides easy access to review count for display purposes, social proof,
     * and analytics without requiring separate queries.
     *
     * @return int Total number of customer reviews for this product
     */
    public function getReviewCountAttribute()
    {
        // Count total reviews for this product
        return $this->reviews()->count();
    }
}
