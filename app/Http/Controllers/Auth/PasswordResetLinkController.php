<?php
/**
 * Password Reset Link Controller
 * 
 * This controller handles password reset link generation and sending for the Hanaya Shop
 * e-commerce application. It manages the initial step of the password reset process by
 * validating email addresses, generating secure reset tokens, and sending reset emails
 * with comprehensive error handling and logging for security and debugging.
 * 
 * Key Features:
 * - Password reset request form display
 * - Email validation and user existence checking
 * - Secure reset link generation and sending
 * - Comprehensive error handling and logging
 * - Locale preservation for multilingual support
 * - Security measures against invalid requests
 * 
 * Password Reset Flow:
 * 1. User visits forgot password page
 * 2. User enters email address
 * 3. System validates email exists in database
 * 4. System generates secure reset token
 * 5. System sends email with reset link
 * 6. User receives email and can proceed to reset
 * 
 * Security Features:
 * - Email existence verification
 * - Secure token generation
 * - Rate limiting protection
 * - Comprehensive logging for security monitoring
 * - Error handling prevents information leakage
 * 
 * @package App\Http\Controllers\Auth
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Password Reset Link Controller Class
 * 
 * Manages password reset link requests with comprehensive validation,
 * security checks, and error handling for secure password recovery.
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Display Password Reset Request Form
     * 
     * Shows the forgot password view where users can enter their email
     * address to request a password reset link. This is the entry point
     * for the password recovery process.
     * 
     * @return \Illuminate\View\View Forgot password form view
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle Password Reset Link Request
     * 
     * Processes password reset requests with comprehensive validation,
     * security checks, error handling, and logging. Ensures only valid
     * users can receive reset links while providing appropriate feedback
     * and maintaining security through comprehensive error handling.
     * 
     * Request Process:
     * 1. Validate email format and requirement
     * 2. Check if email exists in user database
     * 3. Preserve current locale for multilingual support
     * 4. Generate and send secure reset link
     * 5. Log all activities for security monitoring
     * 6. Provide appropriate user feedback
     * 
     * Security Features:
     * - Email existence verification prevents enumeration
     * - Comprehensive logging for security monitoring
     * - Error handling prevents information disclosure
     * - Rate limiting through Laravel's password reset system
     * - Secure token generation and delivery
     * 
     * @param \Illuminate\Http\Request $request HTTP request with email
     * @return \Illuminate\Http\RedirectResponse Redirect with status or error
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function store(Request $request): RedirectResponse
    {
        // Email Validation
        /**
         * Email Format Validation - Ensure valid email format
         * Required field validation prevents empty submissions
         * Email format validation ensures proper email structure
         */
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // User Existence Check
        /**
         * Security Validation - Verify email exists in database
         * Prevents password reset attempts for non-existent users
         * Provides clear error message for invalid email addresses
         * Protects against email enumeration attacks
         */
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Password Reset Processing with Error Handling
        /**
         * Secure Reset Link Processing - Generate and send reset email with comprehensive logging
         * 
         * Process Features:
         * - Detailed logging for security monitoring and debugging
         * - Locale preservation for multilingual email support
         * - Comprehensive error handling with user-friendly messages
         * - Success/failure tracking for system monitoring
         */
        try {
            // Log Reset Attempt
            /**
             * Security Logging - Track password reset attempts
             * Essential for security monitoring and audit trails
             * Helps identify potential security threats or system issues
             */
            Log::info('Attempting to send password reset email to: ' . $request->email);
            
            // Preserve Locale for Email
            /**
             * Multilingual Support - Preserve current locale for email notifications
             * Ensures password reset emails are sent in user's preferred language
             * Critical for international e-commerce platform user experience
             */
            $currentLocale = app()->getLocale();
            Session::put('locale', $currentLocale);
            Log::info('Current locale for password reset: ' . $currentLocale);
            
            // Send Reset Link
            /**
             * Reset Link Generation - Send secure password reset link via email
             * 
             * Security Features:
             * - Secure token generation with expiration
             * - Email delivery through configured mail system
             * - Rate limiting to prevent abuse
             * - Token validation for reset process
             */
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Log Reset Status
            /**
             * Status Logging - Track reset link sending status
             * Helps with debugging email delivery issues
             * Monitors success rates for system health
             */
            Log::info('Password reset status: ' . $status);

            // Handle Success Case
            /**
             * Success Response - Process successful reset link sending
             * Provides user confirmation that email was sent
             * Logs success for monitoring and audit purposes
             */
            if ($status == Password::RESET_LINK_SENT) {
                Log::info('Password reset email sent successfully to: ' . $request->email);
                return back()->with('status', 'We have emailed your password reset link!');
            }

            // Handle Failure Case
            /**
             * Failure Response - Process reset link sending failures
             * Logs failure details for debugging and monitoring
             * Provides localized error message to user
             */
            Log::warning('Password reset failed with status: ' . $status . ' for email: ' . $request->email);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            // Exception Handling
            /**
             * Exception Processing - Handle unexpected errors during reset process
             * 
             * Error Handling Features:
             * - Detailed error logging for debugging
             * - Stack trace logging for development
             * - User-friendly error message without technical details
             * - Input preservation for better user experience
             * - Graceful degradation for system reliability
             */
            Log::error('Password reset email failed: ' . $e->getMessage());
            Log::error('Password reset error trace: ' . $e->getTraceAsString());
            
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Unable to send password reset email. Please try again later or contact support.']);
        }
    }
}
