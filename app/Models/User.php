<?php
/**
 * User Model
 * 
 * This model represents users/customers in the Hanaya Shop e-commerce application.
 * It handles user authentication, authorization, and relationships with other entities
 * like orders, reviews, shopping cart, and addresses. The model extends Laravel's
 * Authenticatable class to provide full authentication functionality.
 * 
 * Key Features:
 * - User authentication and session management
 * - Role-based access control (admin, user)
 * - Password hashing and security
 * - Email verification capabilities
 * - Order management and purchase history
 * - Product review and rating system
 * - Shopping cart functionality
 * - Address management for shipping
 * - Blog post authoring (for admin users)
 * - Notification system integration
 * 
 * Database Relationships:
 * - Has many Orders (one-to-many)
 * - Has many Reviews (one-to-many)
 * - Has many Cart Items (one-to-many)
 * - Has many Addresses (one-to-many)
 * - Has many Posts (one-to-many)
 * 
 * @package App\Models
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Email verification interface (commented out)
use Illuminate\Database\Eloquent\Factories\HasFactory; // Factory support for testing
use Illuminate\Foundation\Auth\User as Authenticatable; // Laravel's authentication base class
use Illuminate\Notifications\Notifiable;               // Notification system support
use Illuminate\Auth\Passwords\CanResetPassword;        // Password reset functionality
use App\Models\Order\Order;      // Order model for purchase tracking
use App\Models\Product\Review;   // Review model for product feedback
use App\Models\Cart\Cart;        // Shopping cart model
use App\Notifications\ResetPassword as ResetPasswordNotification;  // Custom password reset notification
use Illuminate\Support\Facades\Session; // Session support for locale

/**
 * User Model Class
 * 
 * Eloquent model representing users in the authentication and authorization system.
 * Extends Laravel's Authenticatable class to provide complete user management
 * functionality including authentication, authorization, and business relationships.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CanResetPassword; // Enable model factories, notifications, and password reset

    /**
     * Mass Assignable Attributes
     * 
     * Defines which attributes can be mass-assigned using create() or fill() methods.
     * This provides security by preventing unauthorized attribute modification
     * while allowing convenient user registration and profile updates.
     *
     * @var list<string> List of attributes that can be mass assigned
     */
    protected $fillable = [
        'name',     // User's full name for display and communication
        'email',    // Email address for authentication and communication
        'password', // Hashed password for authentication
        'role',     // User role for authorization (admin, user)
    ];

    /**
     * Hidden Attributes for Serialization
     * 
     * Specifies attributes that should be hidden when the model is converted
     * to an array or JSON. This is crucial for security to prevent sensitive
     * data like passwords from being exposed in API responses.
     *
     * @var list<string> List of attributes to hide from serialization
     */
    protected $hidden = [
        'password',      // Never expose password hash
        'remember_token', // Never expose remember token for security
    ];

    /**
     * Attribute Casting Configuration
     * 
     * Defines how attributes should be cast when accessed from the database.
     * This ensures proper data types and automatic transformations for
     * enhanced developer experience and data consistency.
     *
     * @return array<string, string> Attribute casting rules
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Cast to Carbon datetime instance
            'password' => 'hashed',            // Automatically hash password on assignment
        ];
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // Lấy locale hiện tại từ session hoặc sử dụng locale mặc định
        $locale = Session::get('locale', config('app.locale'));
        
        // Gửi notification với locale
        $this->notify(new ResetPasswordNotification($token, $locale));
    }

    /**
     * Get the email address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    // ===== AUTHORIZATION METHODS =====

    /**
     * Check if User is Administrator
     * 
     * Determines whether the user has administrative privileges.
     * Administrators have full access to the admin panel, can manage
     * products, orders, users, and have complete system control.
     * 
     * @return bool True if user is an administrator, false otherwise
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // /**
    //  * Check if User is Manager (Currently Disabled)
    //  * 
    //  * Manager role functionality is commented out but preserved for
    //  * future implementation. Managers would have intermediate privileges
    //  * between regular users and administrators.
    //  * 
    //  * @return bool True if user is a manager, false otherwise
    //  */
    // public function isManager(): bool
    // {
    //     return $this->role === 'manager';
    // }

    /**
     * Check if User is Regular Customer
     * 
     * Determines whether the user is a regular customer with standard
     * privileges. Regular users can browse products, place orders,
     * write reviews, and manage their personal account information.
     * 
     * @return bool True if user is a regular customer, false otherwise
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // ===== RELATIONSHIP DEFINITIONS =====

    /**
     * Orders Relationship
     * 
     * Defines the one-to-many relationship between users and orders.
     * Each user can have multiple orders, enabling order history tracking,
     * purchase analytics, and customer service functionality.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Reviews Relationship
     * 
     * Defines the one-to-many relationship between users and product reviews.
     * Users can write multiple reviews for different products, supporting
     * the product feedback and rating system.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function review()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Shopping Cart Relationship
     * 
     * Defines the one-to-many relationship between users and cart items.
     * Each user can have multiple items in their shopping cart, supporting
     * the e-commerce shopping experience and purchase flow.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Blog Posts Relationship
     * 
     * Defines the one-to-many relationship between users and blog posts.
     * Primarily used for admin users who can create and manage blog content
     * for the website's content management system.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * Addresses Relationship
     * 
     * Defines the one-to-many relationship between users and addresses.
     * Users can have multiple saved addresses for shipping and billing
     * purposes, improving checkout experience and order management.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
