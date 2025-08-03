<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if the email exists in the database
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        try {
            Log::info('Attempting to send password reset email to: ' . $request->email);
            
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            Log::info('Password reset status: ' . $status);

            if ($status == Password::RESET_LINK_SENT) {
                Log::info('Password reset email sent successfully to: ' . $request->email);
                return back()->with('status', 'We have emailed your password reset link!');
            }

            Log::warning('Password reset failed with status: ' . $status . ' for email: ' . $request->email);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            // Log the detailed error for debugging
            Log::error('Password reset email failed: ' . $e->getMessage());
            Log::error('Password reset error trace: ' . $e->getTraceAsString());
            
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Unable to send password reset email. Please try again later or contact support.']);
        }
    }
}
