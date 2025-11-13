<?php

/**
 * Confirmable Password Controller
 *
 * This controller handles password confirmation functionality for the Hanaya Shop
 * e-commerce application. It manages secure password re-verification for sensitive
 * operations that require additional authentication beyond the initial login.
 *
 * Key Features:
 * - Password re-confirmation for sensitive operations
 * - Secure password validation without storing passwords
 * - Session-based confirmation tracking with timestamps
 * - Graceful error handling for invalid passwords
 * - Integration with Laravel's password confirmation middleware
 *
 * Use Cases:
 * - Account settings changes
 * - Payment information updates
 * - Administrative operations
 * - Sensitive data access
 * - Security-critical actions
 *
 * Security Features:
 * - Password validation without storage
 * - Session-based confirmation tracking
 * - Time-based confirmation expiry
 * - Proper error messaging
 * - Integration with authentication guards
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Confirmable Password Controller Class
 *
 * Manages password confirmation workflows for enhanced security
 * during sensitive operations requiring additional authentication.
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Show Password Confirmation Form
     *
     * Displays the password confirmation view for users who need to
     * re-verify their password before accessing sensitive functionality.
     * Used when the password confirmation middleware requires additional
     * authentication for security-critical operations.
     *
     * @return \Illuminate\View\View Password confirmation form view
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm User Password
     *
     * Validates the user's password against their current credentials
     * without storing the password. Sets a session timestamp to track
     * when the password was confirmed for subsequent security checks.
     *
     * Validation Process:
     * 1. Validate provided password against user's current credentials
     * 2. Throw validation exception if password is incorrect
     * 3. Store confirmation timestamp in session for tracking
     * 4. Redirect to intended destination or dashboard
     *
     * Security Features:
     * - Password validation without storage
     * - Session-based confirmation tracking
     * - Proper error handling for invalid passwords
     * - Integration with authentication guards
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with password
     * @return \Illuminate\Http\RedirectResponse Redirect to intended destination
     *
     * @throws \Illuminate\Validation\ValidationException If password is invalid
     */
    public function store(Request $request): RedirectResponse
    {
        // Password Validation
        /**
         * Password Verification - Validate current password without storage
         * Uses authentication guard to verify password against user's credentials
         * Provides secure validation without exposing or storing password data
         */
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            // Invalid Password Error
            /**
             * Validation Error - Throw exception for incorrect password
             * Uses localized error message for user-friendly feedback
             * Prevents further execution if password is invalid
             */
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Set Confirmation Timestamp
        /**
         * Confirmation Tracking - Store password confirmation timestamp
         * Used by password confirmation middleware to track when password was verified
         * Allows time-based expiry of password confirmation for enhanced security
         */
        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
