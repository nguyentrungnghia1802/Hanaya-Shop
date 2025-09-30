<?php

/**
 * Profile Management Controller
 *
 * This controller handles user profile management functionality in the Hanaya Shop
 * e-commerce application. It provides comprehensive profile management including
 * viewing, editing, updating, and deleting user accounts with role-based interfaces.
 *
 * Key Features:
 * - Role-based profile interface (User vs Admin)
 * - Profile information editing and updating
 * - Email verification handling during profile updates
 * - Secure account deletion with password confirmation
 * - Session management during account operations
 *
 * Security Features:
 * - Password confirmation for sensitive operations
 * - Email verification reset on email changes
 * - Session invalidation during account deletion
 * - CSRF token regeneration for security
 *
 * User Experience:
 * - Different interfaces for different user roles
 * - Validation feedback and error handling
 * - Redirect management with status messages
 * - Seamless authentication flow
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest; // Custom form request for profile validation
use Illuminate\Http\RedirectResponse;       // HTTP redirect response handling
use Illuminate\Http\Request;                // HTTP request handling
use Illuminate\Support\Facades\Auth;        // Authentication services
use Illuminate\Support\Facades\Redirect;    // Redirect helper facade
use Illuminate\View\View;                   // View response handling

/**
 * Profile Controller Class
 *
 * Manages user profile operations including display, editing, updating,
 * and deletion. Provides role-based interfaces and secure account management.
 */
class ProfileController extends Controller
{
    /**
     * Display User Profile Edit Form
     *
     * Shows the appropriate profile editing interface based on user role.
     * Provides different views for regular users and administrators with
     * role-specific features and interface elements.
     *
     * Role-Based Interface Logic:
     * - Regular users: Standard profile editing interface
     * - Admin users: Enhanced admin profile interface with additional features
     *
     * Interface Features:
     * - User information display and editing
     * - Role-appropriate form fields and options
     * - Consistent user experience across roles
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with user data
     * @return \Illuminate\View\View Profile edit view based on user role
     */
    public function edit(Request $request): View
    {
        // Role-Based View Selection
        /**
         * User Role Detection - Determine appropriate interface
         * Regular users get standard profile interface
         * Admin users get enhanced admin profile interface
         * Ensures appropriate features are available for each role
         */
        if ($request->user()->isUser()) {
            // Standard User Profile Interface
            /**
             * Regular User Profile - Standard profile editing interface
             * Includes basic profile information editing capabilities
             * Optimized for customer account management
             */
            return view('profile.edit', [
                'user' => $request->user(),  // Current user data for form population
            ]);
        } else {
            // Admin Profile Interface
            /**
             * Admin Profile Interface - Enhanced profile editing for administrators
             * Includes additional admin-specific features and options
             * Provides comprehensive account management capabilities
             */
            return view('profile.admin-edit', [
                'user' => $request->user(),  // Current admin user data for form population
            ]);
        }
    }

    /**
     * Update User Profile Information
     *
     * Processes profile update requests with comprehensive validation and
     * email verification handling. Updates user information and manages
     * email verification status appropriately.
     *
     * Update Process:
     * - Validates submitted profile data
     * - Updates user information
     * - Handles email verification reset if email changed
     * - Saves changes and provides user feedback
     *
     * Security Features:
     * - Form request validation
     * - Email verification reset on email changes
     * - Safe data filling with validated input only
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request  Validated profile update request
     * @return \Illuminate\Http\RedirectResponse Redirect back with success status
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Profile Data Update
        /**
         * User Information Update - Apply validated changes to user profile
         * Uses fill() method to safely update only validated fields
         * Prevents mass assignment vulnerabilities
         */
        $request->user()->fill($request->validated());

        // Email Verification Management
        /**
         * Email Change Handling - Reset verification status if email changed
         * When user changes email, verification status must be reset
         * Ensures email verification integrity and security
         */
        if ($request->user()->isDirty('email')) {
            // If the column is cast to Carbon, use unset to remove the value
            unset($request->user()->email_verified_at); // Reset email verification
        }

        // Save Changes
        /**
         * Profile Update Persistence - Save changes to database
         * Commits all profile changes including email verification reset
         * Ensures data consistency and integrity
         */
        $request->user()->save();

        // Success Response
        /**
         * Update Confirmation - Redirect with success status
         * Provides user feedback about successful profile update
         * Returns to profile edit page with success message
         */
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete User Account
     *
     * Permanently deletes the user's account with comprehensive security measures.
     * Requires password confirmation and handles complete account cleanup including
     * session invalidation and authentication logout.
     *
     * Security Process:
     * - Password confirmation validation
     * - User logout and session invalidation
     * - Account deletion from database
     * - CSRF token regeneration
     *
     * Cleanup Features:
     * - Complete user data removal
     * - Session cleanup and invalidation
     * - Authentication state reset
     * - Security token regeneration
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with password confirmation
     * @return \Illuminate\Http\RedirectResponse Redirect to home page after deletion
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Password Confirmation Validation
        /**
         * Security Validation - Require current password for account deletion
         * Uses custom validation bag to separate deletion errors
         * Prevents unauthorized account deletion attempts
         */
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'], // Must provide current password
        ]);

        // User Reference
        /**
         * User Context - Get user reference before logout
         * Stores user reference before authentication state changes
         * Necessary for account deletion after logout
         */
        $user = $request->user();

        // Authentication Cleanup
        /**
         * Logout Process - Remove authentication state
         * Logs out user before account deletion
         * Prevents authentication issues during deletion process
         */
        Auth::logout();

        // Account Deletion
        /**
         * Account Removal - Permanently delete user account
         * Removes user record from database
         * Irreversible operation with complete data removal
         */
        $user->delete();

        // Session Security Cleanup
        /**
         * Session Invalidation - Complete session cleanup for security
         * Invalidates current session data
         * Regenerates CSRF token for security
         * Prevents session-based security vulnerabilities
         */
        $request->session()->invalidate();          // Clear all session data
        $request->session()->regenerateToken();     // Generate new CSRF token

        // Post-Deletion Redirect
        /**
         * Deletion Completion - Redirect to application home
         * Takes user to public homepage after account deletion
         * Completes the account deletion flow
         */
        return Redirect::to('/');
    }
}
