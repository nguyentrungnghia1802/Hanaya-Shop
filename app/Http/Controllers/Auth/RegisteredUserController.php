<?php

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

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Store user data in session and send verification email.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
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

        // Store registration data in session
        session([
            'pending_registration' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verification_token' => Str::random(60),
                'created_at' => now(),
            ]
        ]);

        // Send verification email with token
        $this->sendVerificationEmail($request->email, session('pending_registration.verification_token'));

        return redirect()->route('verification.notice');
    }

    /**
     * Send verification email to the user
     */
    private function sendVerificationEmail($email, $token)
    {
        $verificationUrl = route('verification.verify', ['token' => $token]);
        
        Mail::send('emails.verify-registration', [
            'verificationUrl' => $verificationUrl,
            'email' => $email
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject(__('auth.verify_email_subject'));
        });
    }

    /**
     * Show the email verification notice page
     */
    public function verificationNotice(): View|RedirectResponse
    {
        if (!session('pending_registration')) {
            return redirect()->route('register');
        }

        return view('auth.verification-notice');
    }

    /**
     * Verify the email token and create the user account
     */
    public function verifyEmail(Request $request, $token): RedirectResponse
    {
        $pendingRegistration = session('pending_registration');
        
        if (!$pendingRegistration || $pendingRegistration['verification_token'] !== $token) {
            return redirect()->route('register')->with('error', __('auth.invalid_verification_token'));
        }

        // Check if token is not expired (24 hours)
        $createdAt = \Carbon\Carbon::parse($pendingRegistration['created_at']);
        if ($createdAt->addHours(24)->isPast()) {
            session()->forget('pending_registration');
            return redirect()->route('register')->with('error', __('auth.verification_token_expired'));
        }

        // Create the user account
        $user = User::create([
            'name' => $pendingRegistration['name'],
            'email' => $pendingRegistration['email'],
            'password' => $pendingRegistration['password'],
            'email_verified_at' => now(),
        ]);

        // Clear session data
        session()->forget('pending_registration');

        // Log in the user
        Auth::login($user);

        return redirect()->route('verification.success');
    }

    /**
     * Show the verification success page
     */
    public function verificationSuccess(): View|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('auth.verification-success');
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        $pendingRegistration = session('pending_registration');
        
        if (!$pendingRegistration) {
            return redirect()->route('register');
        }

        // Send verification email again
        $this->sendVerificationEmail($pendingRegistration['email'], $pendingRegistration['verification_token']);

        return back()->with('status', 'verification-link-sent');
    }
}
