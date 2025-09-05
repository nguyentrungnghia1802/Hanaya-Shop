<?php
/**
 * Registered User Controller
 * 
 * This controller handles user registration functionality for the Hanaya Shop e-commerce
 * application. It manages the complete registration process including form display,
 * validation, email verification, and account creation with comprehensive security
 * measures and user experience optimizations.
 * 
 * Key Features:
 * - Secure user registration with comprehensive validation
 * - Email verification before account activation
 * - Session-based pending registration management
 * - Enhanced password complexity requirements
 * - Email verification token system with expiration
 * - Verification email resending capability
 * - Automatic login after successful verification
 * 
 * Registration Flow:
 * 1. User fills out registration form
 * 2. System validates input and stores in session
 * 3. Verification email sent with secure token
 * 4. User clicks verification link in email
 * 5. System validates token and creates account
 * 6. User automatically logged in and redirected
 * 
 * Security Features:
 * - Enhanced password complexity validation
 * - Email uniqueness verification
 * - Secure token generation for verification
 * - Token expiration (24 hours)
 * - Session-based pending registration storage
 * - Email verification before account activation
 * 
 * @package App\Http\Controllers\Auth
 * @author Hanaya Shop Development Team
 * @version 1.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Users;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * Registered User Controller Class
 * 
 * Manages user registration with email verification, comprehensive
 * validation, and secure account creation for the e-commerce platform.
 */
class RegisteredUserController extends Controller
{
    /**
     * Display Registration Form
     * 
     * Shows the user registration view where new customers can create
     * accounts for the Hanaya Shop e-commerce platform.
     * 
     * @return \Illuminate\View\View Registration form view
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle Registration Request
     * 
     * Processes user registration with comprehensive validation, stores
     * registration data in session for verification, and sends email
     * verification link. Implements secure registration flow with
     * email verification before account activation.
     * 
     * Registration Process:
     * 1. Validate user input with enhanced security rules
     * 2. Store registration data in session (not database yet)
     * 3. Generate secure verification token
     * 4. Send verification email with token
     * 5. Redirect to verification notice page
     * 
     * Validation Rules:
     * - Name: Required string, max 255 characters
     * - Email: Required, valid format, lowercase, unique
     * - Password: Enhanced complexity requirements with confirmation
     * 
     * Security Features:
     * - Session-based pending registration (prevents unverified accounts)
     * - Secure password hashing before storage
     * - Unique verification token generation
     * - Email verification before account creation
     * 
     * @param \Illuminate\Http\Request $request HTTP request with registration data
     * @return \Illuminate\Http\RedirectResponse Redirect to verification notice
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function store(Request $request): RedirectResponse
    {
        // Registration Data Validation
        /**
         * Comprehensive Registration Validation - Enhanced security and data integrity
         * 
         * Validation Rules:
         * - name: Required string up to 255 characters
         * - email: Required, valid email format, lowercase, unique in users table
         * - password: Enhanced complexity requirements:
         *   * Minimum 8 characters
         *   * At least one lowercase letter (regex: /[a-z]/)
         *   * At least one uppercase letter (regex: /[A-Z]/)
         *   * At least one digit (regex: /[0-9]/)
         *   * At least one special character (regex: /[@$!%*#?&]/)
         *   * No whitespace (not_regex: /\\s/)
         *   * Must be confirmed (password_confirmation field)
         */
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
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

        // Store Pending Registration in Session
        /**
         * Session-Based Pending Registration - Secure temporary storage
         * 
         * Security Features:
         * - Data stored in session, not database (prevents unverified accounts)
         * - Password hashed immediately for security
         * - Unique verification token generated
         * - Timestamp recorded for expiration tracking
         * 
         * Session Data Structure:
         * - name: User's full name
         * - email: User's email address
         * - password: Securely hashed password
         * - verification_token: Unique 60-character random token
         * - created_at: Registration timestamp for expiration
         */
        session([
            'pending_registration' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verification_token' => Str::random(60),
                'created_at' => now(),
            ]
        ]);

        // Send Verification Email
        /**
         * Email Verification - Send secure verification link to user
         * Uses private method to send email with verification token
         * Critical step in secure registration process
         */
        $this->sendVerificationEmail($request->email, session('pending_registration.verification_token'));

        return redirect()->route('verification.notice');
    }

    /**
     * Send Verification Email
     * 
     * Sends email verification message to user with secure verification
     * link containing unique token. Uses configured mail system to
     * deliver registration verification emails.
     * 
     * @param string $email User's email address
     * @param string $token Unique verification token
     */
    private function sendVerificationEmail($email, $token)
    {
        // Generate Verification URL
        /**
         * Secure Verification URL - Create verification link with token
         * Token embedded in URL for secure verification process
         * Route leads to verification validation method
         */
        $verificationUrl = route('verification.verify', ['token' => $token]);
        
        // Send Verification Email
        /**
         * Email Delivery - Send verification email using mail template
         * 
         * Email Features:
         * - Custom verification email template
         * - Verification URL included for user action
         * - Localized subject line for international support
         * - User's email address passed to template for personalization
         */
        Mail::send('emails.verify-registration', [
            'verificationUrl' => $verificationUrl,
            'email' => $email
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject(__('auth.verify_email_subject'));
        });
    }

    /**
     * Show Email Verification Notice
     * 
     * Displays verification notice page informing user to check email
     * for verification link. Includes security check to ensure valid
     * pending registration exists in session.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Verification notice or redirect
     */
    public function verificationNotice(): View|RedirectResponse
    {
        // Validate Pending Registration
        /**
         * Session Validation - Ensure pending registration exists
         * Prevents access to verification notice without valid registration
         * Redirects to registration form if no pending registration found
         */
        if (!session('pending_registration')) {
            return redirect()->route('register');
        }

        return view('auth.verification-notice');
    }

    /**
     * Verify Email Token and Create Account
     * 
     * Validates email verification token and creates user account upon
     * successful verification. Includes comprehensive security checks
     * for token validation, expiration, and account creation.
     * 
     * Verification Process:
     * 1. Validate token against session data
     * 2. Check token expiration (24 hours)
     * 3. Create user account with verified email
     * 4. Clear session data
     * 5. Automatically log in user
     * 6. Redirect to success page
     * 
     * @param \Illuminate\Http\Request $request HTTP request
     * @param string $token Verification token from email link
     * @return \Illuminate\Http\RedirectResponse Redirect to success or error
     */
    public function verifyEmail(Request $request, $token): RedirectResponse
    {
        // Retrieve Pending Registration
        /**
         * Session Data Retrieval - Get pending registration from session
         * Contains user data and verification token for validation
         */
        $pendingRegistration = session('pending_registration');
        
        // Token Validation
        /**
         * Security Validation - Verify token matches session token
         * Prevents unauthorized account creation attempts
         * Ensures only legitimate verification requests are processed
         */
        if (!$pendingRegistration || $pendingRegistration['verification_token'] !== $token) {
            return redirect()->route('register')->with('error', __('auth.invalid_verification_token'));
        }

        // Token Expiration Check
        /**
         * Expiration Validation - Check if verification token is still valid
         * 24-hour expiration window for security and data integrity
         * Prevents use of old verification tokens
         */
        $createdAt = \Carbon\Carbon::parse($pendingRegistration['created_at']);
        if ($createdAt->addHours(24)->isPast()) {
            session()->forget('pending_registration');
            return redirect()->route('register')->with('error', __('auth.verification_token_expired'));
        }

        // Create User Account
        /**
         * Account Creation - Create verified user account in database
         * 
         * Account Features:
         * - Pre-hashed password from session
         * - Email marked as verified immediately
         * - All registration data transferred from session
         * - Account ready for immediate use
         */
        $user = User::create([
            'name' => $pendingRegistration['name'],
            'email' => $pendingRegistration['email'],
            'password' => $pendingRegistration['password'],
            'email_verified_at' => now(),
        ]);

        // Clean Up Session Data
        /**
         * Session Cleanup - Remove pending registration data
         * Prevents session data accumulation and ensures security
         * Registration process complete, data no longer needed
         */
        session()->forget('pending_registration');

        // Automatic User Login
        /**
         * Auto-Login - Log in newly created user automatically
         * Provides seamless user experience after verification
         * User can immediately access account features
         */
        Auth::login($user);

        return redirect()->route('verification.success');
    }

    /**
     * Show Verification Success Page
     * 
     * Displays success page after account verification and creation.
     * Includes authentication check to ensure user is properly logged in.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Success page or redirect
     */
    public function verificationSuccess(): View|RedirectResponse
    {
        // Authentication Check
        /**
         * Login Validation - Ensure user is authenticated
         * Prevents access to success page without proper authentication
         * Redirects to login if user not authenticated
         */
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('auth.verification-success');
    }

    /**
     * Resend Verification Email
     * 
     * Resends verification email to users who didn't receive the original
     * or need a new verification link. Validates pending registration
     * exists before sending email.
     * 
     * @param \Illuminate\Http\Request $request HTTP request
     * @return \Illuminate\Http\RedirectResponse Redirect with status message
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        // Validate Pending Registration
        /**
         * Session Validation - Ensure pending registration exists
         * Prevents email sending without valid registration data
         * Redirects to registration if no pending data found
         */
        $pendingRegistration = session('pending_registration');
        
        if (!$pendingRegistration) {
            return redirect()->route('register');
        }

        // Resend Verification Email
        /**
         * Email Resending - Send verification email again
         * Uses same verification token for consistency
         * Provides user option to receive email again if needed
         */
        $this->sendVerificationEmail($pendingRegistration['email'], $pendingRegistration['verification_token']);

        return back()->with('status', 'verification-link-sent');
    }
}
