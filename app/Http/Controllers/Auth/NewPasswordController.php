<?php
/**
 * New Password Controller
 * 
 * This controller handles password reset functionality for the Hanaya Shop e-commerce
 * application. It manages the complete password reset process including form display,
 * token validation, password updating, and security measures to ensure safe password
 * recovery for users who have forgotten their credentials.
 * 
 * Key Features:
 * - Secure password reset form display with token validation
 * - Comprehensive password validation and confirmation
 * - Token-based password reset security
 * - Remember token regeneration for enhanced security
 * - Password reset event triggering for notifications
 * - Error handling with localized messages
 * 
 * Password Reset Flow:
 * 1. User receives password reset email with secure token
 * 2. User clicks link and is shown password reset form
 * 3. User enters new password with confirmation
 * 4. System validates token, email, and password requirements
 * 5. Password is updated and remember token is regenerated
 * 6. User is redirected to login with success message
 * 
 * Security Features:
 * - Token-based authentication for reset requests
 * - Password confirmation requirement
 * - Remember token regeneration
 * - Event-driven password reset tracking
 * - Comprehensive validation rules
 * 
 * @package App\Http\Controllers\Auth
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * New Password Controller Class
 * 
 * Manages secure password reset operations with comprehensive
 * validation and security measures for user account recovery.
 */
class NewPasswordController extends Controller
{
    /**
     * Display Password Reset Form
     * 
     * Shows the password reset view to users who have clicked on a
     * password reset link from their email. The form includes the
     * reset token and email for secure password reset processing.
     * 
     * @param \Illuminate\Http\Request $request HTTP request with reset token and email
     * @return \Illuminate\View\View Password reset form with request data
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle New Password Request
     * 
     * Processes the password reset form submission with comprehensive
     * validation, security checks, and password updating. Ensures
     * secure password reset with token validation and proper error handling.
     * 
     * Reset Process:
     * 1. Validate token, email, and password requirements
     * 2. Attempt password reset using Laravel's Password facade
     * 3. Update user password with secure hashing
     * 4. Regenerate remember token for security
     * 5. Trigger password reset event for notifications
     * 6. Redirect with appropriate status message
     * 
     * Validation Rules:
     * - Token: Required (from password reset email)
     * - Email: Required and valid email format
     * - Password: Required, confirmed, meets default complexity rules
     * 
     * Security Features:
     * - Token-based reset validation
     * - Secure password hashing
     * - Remember token regeneration
     * - Event-driven tracking
     * 
     * @param \Illuminate\Http\Request $request HTTP request with reset data
     * @return \Illuminate\Http\RedirectResponse Redirect with status or error
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function store(Request $request): RedirectResponse
    {
        // Input Validation
        /**
         * Password Reset Validation - Comprehensive validation for reset request
         * 
         * Validation Rules:
         * - token: Required reset token from email link
         * - email: Required valid email address
         * - password: Required, confirmed, meets security requirements
         */
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Password Reset Processing
        /**
         * Secure Password Reset - Attempt to reset user password with comprehensive security
         * 
         * Reset Process:
         * 1. Validate token and email combination
         * 2. Update user password with secure hashing
         * 3. Regenerate remember token for enhanced security
         * 4. Trigger password reset event for system notifications
         * 
         * Security Measures:
         * - Token validation prevents unauthorized resets
         * - Password hashing ensures secure storage
         * - Remember token regeneration invalidates existing sessions
         * - Event triggering enables audit logging and notifications
         */
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                // Update User Password and Security Token
                /**
                 * User Update - Securely update password and regenerate remember token
                 * forceFill bypasses mass assignment protection for security fields
                 * Hash::make provides secure password hashing
                 * Random remember token invalidates existing sessions
                 */
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Trigger Password Reset Event
                /**
                 * Event Notification - Trigger password reset event for system tracking
                 * Enables audit logging, user notifications, and security monitoring
                 * Allows other system components to respond to password changes
                 */
                event(new PasswordReset($user));
            }
        );

        // Handle Reset Result
        /**
         * Result Processing - Redirect based on password reset status
         * 
         * Success Case: Redirect to login with success message
         * Error Case: Return to form with error message and preserved email
         * Provides clear feedback to user about reset attempt result
         */
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
