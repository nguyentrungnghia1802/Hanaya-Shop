<?php
/**
 * Main Web Routes Configuration
 * 
 * This file serves as the central routing hub for the Hanaya Shop e-commerce application.
 * It orchestrates the inclusion of specialized route files and defines global application routes.
 * The modular approach separates concerns between user, admin, and authentication routes.
 * 
 * Route Organization:
 * - User routes: Customer-facing functionality (products, cart, orders)
 * - Admin routes: Administrative panel and management features
 * - Auth routes: Authentication and authorization functionality
 * - Global routes: Application-wide features like chatbot
 * 
 * Architecture Benefits:
 * - Separation of concerns for maintainability
 * - Clear responsibility boundaries
 * - Easier debugging and development
 * - Scalable route organization
 * 
 * @package Routes
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

use Illuminate\Support\Facades\Route;       // Laravel routing facade
use App\Http\Controllers\ChatbotController; // Chatbot functionality controller
use App\Http\Controllers\LocaleController;  // Locale switching functionality controller

/**
 * Modular Route Inclusion
 * 
 * Includes specialized route files for different application areas.
 * This modular approach keeps routes organized and maintainable.
 * Each file handles specific functionality domains.
 */

// User Routes - Customer-facing functionality
/**
 * User Route Module - Customer Interface Routes
 * Includes: Product browsing, cart management, checkout, orders, reviews
 * Handles both guest and authenticated user experiences
 * File: routes/user.php
 */
require __DIR__.'/user.php';

// Admin Routes - Administrative functionality  
/**
 * Admin Route Module - Administrative Panel Routes
 * Includes: Dashboard, product management, user management, order management
 * Protected by admin middleware for security
 * File: routes/admin.php
 */
require __DIR__.'/admin.php';

// Authentication Routes - Login/registration functionality
/**
 * Authentication Route Module - User Authentication Routes
 * Includes: Login, registration, password reset, email verification
 * Handles user account management and security
 * File: routes/auth.php
 */
require __DIR__.'/auth.php';

/**
 * Global Application Routes
 * 
 * Routes that don't fit into specific modules or are used across the entire application.
 * These routes provide global functionality accessible from various parts of the system.
 */

// Chatbot Communication Route
/**
 * Chatbot Integration - AI-powered customer support
 * 
 * Provides intelligent customer assistance through AI chatbot integration.
 * Handles customer inquiries, product recommendations, and support requests.
 * POST method ensures secure data transmission for chat interactions.
 * 
 * Features:
 * - Real-time customer support
 * - Product information assistance  
 * - Order status inquiries
 * - General shopping guidance
 * 
 * Security: CSRF protection via POST method
 * Accessibility: Available to both guests and authenticated users
 */
Route::post('/chatbot', [App\Http\Controllers\ChatbotController::class, 'chat'])
    ->name('chatbot.chat');

/**
 * Locale Switching Route - Multi-language Support
 * 
 * Handles switching between available application languages.
 * Supports English, Vietnamese, and Japanese languages.
 * Stores selected locale in session for persistence across requests.
 * 
 * Languages:
 * - en: English
 * - vi: Tiếng Việt  
 * - ja: 日本語
 * 
 * Security: Validates locale parameter against configured available locales
 * Persistence: Selected locale stored in user session
 */
Route::get('/locale/{locale}', [LocaleController::class, 'setLocale'])
    ->name('locale.set')
    ->where('locale', '[a-z]{2}');

// Test translation route for development
Route::get('/test-translation', function () {
    $locales = ['en', 'vi', 'ja'];
    $output = "<h1>Translation Test Results</h1>";
    
    foreach ($locales as $locale) {
        app()->setLocale($locale);
        $output .= "<h2>Locale: $locale</h2>";
        $output .= "<p>Home: " . __('common.navigation.home') . "</p>";
        $output .= "<p>Products: " . __('common.navigation.products') . "</p>";
        $output .= "<p>Cart: " . __('common.navigation.cart') . "</p>";
        $output .= "<p>Admin Products: " . __('admin.products') . "</p>";
        $output .= "<hr>";
    }
    
    return $output;
})->name('test.translation');

/**
 * Development and Testing Routes
 * 
 * Routes used for development, testing, and demonstration purposes.
 * These routes are typically disabled in production environments.
 */

// TinyMCE Rich Text Editor Demo Route
/**
 * TinyMCE Demo Route - Rich Text Editor Testing
 * 
 * Provides a demonstration page for TinyMCE rich text editor functionality.
 * Used for testing and development of content creation features.
 * Currently commented out - uncomment for development use.
 * 
 * Usage: Testing rich text editing capabilities for admin content creation
 * Access: Development environments only
 */
// Route::get('/tinymce-demo', function () {
//     return view('tinymce-demo');
// })->name('tinymce.demo');

// Include test routes for development
if (app()->environment(['local', 'development'])) {
    include __DIR__ . '/test-chatbot.php';
}
