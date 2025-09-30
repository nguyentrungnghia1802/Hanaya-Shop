<?php

/**
 * Password Controller
 *
 * This controller handles password updates for authenticated users in the Hanaya Shop
 * e-commerce application. It manages secure password changes with comprehensive
 * validation, current password verification, and enhanced security requirements
 * to ensure strong password policies for customer accounts.
 *
 * Key Features:
 * - Current password verification for security
 * - Enhanced password complexity requirements
 * - Secure password hashing and storage
 * - Comprehensive validation with custom rules
 * - Success feedback for password updates
 * - Error bag separation for UI handling
 *
 * Password Requirements:
 * - Minimum 8 characters
 * - At least one lowercase letter
 * - At least one uppercase letter
 * - At least one numeric digit
 * - At least one special character (@$!%*#?&)
 * - No whitespace characters
 * - Password confirmation required
 *
 * Security Features:
 * - Current password verification
 * - Strong password complexity rules
 * - Secure password hashing with bcrypt
 * - Validation error separation
 * - Success status feedback
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Password Controller Class
 *
 * Manages secure password updates for authenticated users with
 * comprehensive validation and enhanced security requirements.
 */
class PasswordController extends Controller
{
    /**
     * Update User Password
     *
     * Handles password updates for authenticated users with comprehensive
     * security validation including current password verification and
     * enhanced password complexity requirements. Ensures secure password
     * management for customer accounts.
     *
     * Validation Process:
     * 1. Verify current password for security
     * 2. Validate new password complexity requirements
     * 3. Confirm password matches confirmation
     * 4. Hash and update password securely
     * 5. Provide success feedback
     *
     * Password Security Requirements:
     * - Minimum 8 characters length
     * - Must include lowercase letter (a-z)
     * - Must include uppercase letter (A-Z)
     * - Must include numeric digit (0-9)
     * - Must include special character (@$!%*#?&)
     * - Cannot contain whitespace
     * - Must be confirmed (typed twice)
     *
     * Security Features:
     * - Current password verification prevents unauthorized changes
     * - Enhanced complexity rules ensure strong passwords
     * - Secure bcrypt hashing for password storage
     * - Error bag separation for clean UI error handling
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with password data
     * @return \Illuminate\Http\RedirectResponse Redirect with status message
     */
    public function update(Request $request): RedirectResponse
    {
        // Password Validation with Enhanced Security Rules
        /**
         * Comprehensive Password Validation - Enhanced security requirements
         *
         * Validation Rules:
         * - current_password: Required, must match user's current password
         * - password: Required string with comprehensive complexity requirements:
         *   * Minimum 8 characters
         *   * At least one lowercase letter (regex: /[a-z]/)
         *   * At least one uppercase letter (regex: /[A-Z]/)
         *   * At least one digit (regex: /[0-9]/)
         *   * At least one special character (regex: /[@$!%*#?&]/)
         *   * No whitespace (not_regex: /\\s/)
         *   * Must be confirmed (password_confirmation field)
         *
         * Error Bag: Uses 'updatePassword' bag for UI error separation
         */
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[@$!%*#?&]/', // at least one special character
                'not_regex:/\\s/',  // no whitespace
                'confirmed',
            ],
        ]);

        // Update User Password Securely
        /**
         * Secure Password Update - Hash and store new password
         *
         * Security Process:
         * - Hash::make uses secure bcrypt hashing algorithm
         * - Overwrites existing password hash in database
         * - Provides one-way encryption for password security
         * - Updates only the password field for minimal data exposure
         */
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Return Success Status
        /**
         * Success Feedback - Provide user confirmation of password update
         * Status used by frontend to display success message
         * Confirms password was changed successfully
         */
        return back()->with('status', 'password-updated');
    }
}
