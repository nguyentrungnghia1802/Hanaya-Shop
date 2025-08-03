<?php
/**
 * Product Category Model
 * 
 * This model represents product categories in the Hanaya Shop e-commerce application.
 * Categories serve as a hierarchical organization system for products, enabling
 * efficient product browsing, filtering, and management. The model supports
 * the creation of product catalogs with clear categorization for enhanced
 * user experience and administrative organization.
 * 
 * Key Features:
 * - Product categorization and organization
 * - Category-based product filtering and browsing
 * - Category image management for visual presentation
 * - Category description and metadata support
 * - Product count tracking per category
 * - SEO-friendly category structure
 * - Administrative category management
 * - Multilingual category support potential
 * 
 * Database Relationships:
 * - Has many Products (one-to-many) - Products belonging to this category
 * 
 * Category Examples:
 * - Soap Flower (Hoa xà phòng)
 * - Fresh Flower (Hoa tươi)
 * - Special Flower (Hoa đặc biệt)
 * - Souvenir (Quà lưu niệm)
 * 
 * @package App\Models\Product
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;                // Base Eloquent model class
use Illuminate\Database\Eloquent\Factories\HasFactory; // Factory support for testing

/**
 * Category Model Class
 * 
 * Eloquent model representing product categories in the e-commerce system.
 * Manages category data, hierarchical organization, and relationships with products.
 */
class Category extends Model
{
    use HasFactory; // Enable model factories for testing and database seeding

    /**
     * Database Table Configuration
     * 
     * Specifies the database table associated with this model.
     * Uses plural form following Laravel conventions.
     */
    protected $table = 'categories';

    /**
     * Mass Assignable Attributes
     * 
     * Defines which attributes can be mass-assigned using create() or fill() methods.
     * This provides security by preventing unauthorized attribute modification
     * while allowing convenient category creation and updates.
     * 
     * @var array<string> List of attributes that can be mass assigned
     */
    protected $fillable = [
        'name',        // Category name/title for display and identification
        'description', // Detailed category description for SEO and user information
        'image_path',  // Path to category image for visual representation
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
    protected $date = [
        'created_at',  // Category creation timestamp
        'updated_at',  // Category last modification timestamp
    ];

    /**
     * Timestamp Management
     * 
     * Enables automatic handling of created_at and updated_at timestamps.
     * Laravel will automatically maintain these fields during model operations
     * to track category creation and modification history.
     * 
     * @var bool Whether to use automatic timestamps
     */
    public $timestamps = true;

    // ===== RELATIONSHIP DEFINITIONS =====

    /**
     * Products Relationship
     * 
     * Defines the one-to-many relationship between categories and products.
     * Each category can contain multiple products, while each product belongs
     * to exactly one category. This relationship enables category-based product
     * organization, filtering, and browsing functionality.
     * 
     * Use cases:
     * - Category-based product listing and filtering
     * - Product count per category for analytics
     * - Category page generation with associated products
     * - Inventory management by category
     * - Category performance analytics
     * - Navigation menu generation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
