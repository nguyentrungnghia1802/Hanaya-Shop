<?php
/**
 * Email Verification Notification Controller
 * 
 * This controller handles email verification notification functionality for the
 * Hanaya Shop e-commerce application. It manages the sending of email verification
 * links to users who need to verify their email addresses during registration
 * or email change processes.
 * 
 * Key Features:
 * - Email verification link generation and sending
 * - Duplicate verification prevention for already verified users
 * - Automatic redirection for verified users
 * - Status feedback for successful link sending
 * - Integration with Laravel's email verification system
 * 
 * Email Verification Flow:
 * 1. User registers or changes email address
 * 2. System checks if email is already verified
 * 3. If not verified, sends verification notification
 * 4. User receives email with verification link
 * 5. User clicks link to complete verification
 * 
 * Security Features:
 * - Signed URL generation for verification links
 * - Automatic expiry of verification links
 * - Prevention of unnecessary email sending
 * - Integration with authentication middleware
 * 
 * @package App\Http\Controllers\Auth
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Email Verification Notification Controller Class
 * 
 * Manages email verification notifications with security checks
 * and user experience optimizations for the authentication system.
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Send Email Verification Notification
     * 
     * Sends a new email verification notification to users who haven't
     * verified their email address yet. Includes smart checks to prevent
     * sending notifications to already verified users and provides
     * appropriate feedback messages.
     * 
     * Verification Process:
     * 1. Check if user's email is already verified
     * 2. If verified, redirect to dashboard (no action needed)
     * 3. If not verified, send verification notification email
     * 4. Return success status for user feedback
     * 
     * Features:
     * - Prevents duplicate notifications to verified users
     * - Automatic dashboard redirection for verified users
     * - Success feedback for email sending
     * - Integration with Laravel's notification system
     * 
     * @param \Illuminate\Http\Request $request HTTP request from authenticated user
     * @return \Illuminate\Http\RedirectResponse Redirect with status message
     */
    public function store(Request $request): RedirectResponse
    {
        // Check Email Verification Status
        /**
         * Verification Status Check - Prevent unnecessary email sending
         * If user's email is already verified, redirect to dashboard
         * Improves user experience and reduces unnecessary email traffic
         */
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Send Verification Notification
        /**
         * Email Notification - Send verification link to user's email
         * Uses Laravel's built-in email verification system
         * Generates signed URL with automatic expiry for security
         * Email contains clickable link to complete verification process
         */
        $request->user()->sendEmailVerificationNotification();

        // Return Success Status
        /**
         * Success Feedback - Provide user feedback for email sending
         * Status message indicates verification link was sent successfully
         * Used by frontend to display appropriate success message to user
         */
        return back()->with('status', 'verification-link-sent');
    }
}
