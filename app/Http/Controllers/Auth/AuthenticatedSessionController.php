<?php
/**
 * Authenticated Session Controller
 * 
 * This controller handles user authentication sessions for the Hanaya Shop e-commerce
 * application. It manages the complete login/logout lifecycle including session management,
 * security features, and role-based redirections for both customers and administrators.
 * 
 * Key Features:
 * - Secure user login with session regeneration
 * - Role-based redirections (admin vs customer)
 * - Proper session management and cleanup
 * - CSRF protection and security measures
 * - Graceful logout with session invalidation
 * 
 * Authentication Flow:
 * 1. Display login form to users
 * 2. Validate credentials using LoginRequest
 * 3. Authenticate user and regenerate session for security
 * 4. Redirect based on user role (admin to dashboard, users to intended page)
 * 5. Handle logout with complete session cleanup
 * 
 * Security Features:
 * - Session regeneration after login to prevent session fixation
 * - Proper CSRF token regeneration on logout
 * - Session invalidation on logout
 * - Role-based access control
 * 
 * @package App\Http\Controllers\Auth
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Authenticated Session Controller Class
 * 
 * Manages user authentication sessions with comprehensive security
 * measures and role-based access control for the e-commerce platform.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display Login Form
     * 
     * Shows the login view to users who need to authenticate.
     * This is the entry point for user authentication in the system.
     * 
     * @return \Illuminate\View\View Login form view
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle User Authentication Request
     * 
     * Processes incoming login requests with comprehensive security measures
     * including session regeneration and role-based redirections. Ensures
     * secure authentication flow with proper session management.
     * 
     * Authentication Process:
     * 1. Validate credentials using LoginRequest (handles throttling and validation)
     * 2. Regenerate session ID to prevent session fixation attacks
     * 3. Determine user role and redirect appropriately
     * 4. Redirect admins to admin dashboard, customers to intended page
     * 
     * Security Features:
     * - Session regeneration prevents session fixation
     * - Role-based redirections for security segregation
     * - Intended URL preservation for user experience
     * 
     * @param \App\Http\Requests\Auth\LoginRequest $request Validated login request
     * @return \Illuminate\Http\RedirectResponse Redirect to appropriate dashboard
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate User Credentials
        /**
         * User Authentication - Validate credentials using LoginRequest
         * LoginRequest handles password validation, throttling, and security checks
         * Throws validation exception if credentials are invalid
         */
        $request->authenticate();

        // Regenerate Session for Security
        /**
         * Session Security - Regenerate session ID after successful login
         * Prevents session fixation attacks by creating new session ID
         * Critical security measure for authentication systems
         */
        $request->session()->regenerate();

        // Role-Based Redirection
        /**
         * Access Control - Redirect based on user role for proper access segregation
         * Admin users get redirected to admin dashboard for management functions
         * Regular customers get redirected to intended page or customer dashboard
         * Maintains security boundaries between different user types
         */
        if (Auth::user()->role === 'admin') {
             return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy Authenticated Session (Logout)
     * 
     * Handles user logout with comprehensive session cleanup and security
     * measures. Ensures complete session termination and prevents
     * unauthorized access to protected resources.
     * 
     * Logout Process:
     * 1. Log out user from web guard
     * 2. Invalidate current session data
     * 3. Regenerate CSRF token for security
     * 4. Redirect to home page
     * 
     * Security Features:
     * - Complete session invalidation
     * - CSRF token regeneration
     * - Proper guard-based logout
     * - Clean redirection to public area
     * 
     * @param \Illuminate\Http\Request $request HTTP request for logout
     * @return \Illuminate\Http\RedirectResponse Redirect to home page
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout User from Web Guard
        /**
         * User Logout - Log out user from web authentication guard
         * Clears authentication state and removes user session
         */
        Auth::guard('web')->logout();

        // Invalidate Session Data
        /**
         * Session Cleanup - Invalidate all session data
         * Removes all stored session information for security
         * Prevents access to protected resources after logout
         */
        $request->session()->invalidate();

        // Regenerate CSRF Token
        /**
         * Security Token - Regenerate CSRF token for new session
         * Prevents CSRF attacks by creating new token
         * Required for subsequent form submissions
         */
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
