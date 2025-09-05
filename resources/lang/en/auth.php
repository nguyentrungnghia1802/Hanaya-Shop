<?php

return [
    // Authentication
    'email' => 'Email',
    'password' => 'Password',
    'remember_me' => 'Remember me',
    'forgot_password' => 'Forgot your password?',
    'log_in' => 'Log in',
    'register' => 'Register',
    'dont_have_account' => "Don't have an account?",
    'already_have_account' => 'Already have an account?',
    'sign_up' => 'Sign up',
    'confirm_password' => 'Confirm Password',
    'name' => 'Name',
    'verify_email' => 'Verify Email',
    'reset_password' => 'Reset Password',
    'send_reset_link' => 'Send Password Reset Link',
    'back_to_login' => 'Back to Login',
    'current_password' => 'Current Password',
    'new_password' => 'New Password',
    'logout' => 'Log Out',

    // Messages
    'thanks_for_signing_up' => 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.',
    'verification_link_sent' => 'A new verification link has been sent to the email address you provided during registration.',
    'resend_verification' => 'Resend Verification Email',
    'forgot_password_text' => 'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
    'secure_area_confirmation' => 'This is a secure area of the application. Please confirm your password before continuing.',
    'confirm' => 'Confirm',

    // Validation
    'failed' => 'These credentials do not match our records.',
    'password_incorrect' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // Edit Profile
    'delete_account' => 'Delete Account',
    'delete_account_confirmation' => 'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.',
    'are_you_sure_you_want_to_delete_your_account' => 'Are you sure you want to delete your account?',
    'cancel' => 'Cancel',
    'update_password' => 'Update Password',
    'update_password_description' => 'Ensure your account is using a long, random password to stay secure.',
    'saved' => 'Saved',
    'save' => 'Save',
    'profile_information' => 'Profile Information',
    'update_profile_information_description' => 'Update your account\'s profile information and email address.',
    'your_email_address_is_unverified' => 'Your email address is unverified.',
    'click_here_to_resend_verification_email' => 'Click here to re-send the verification email.',
    'verification_link_sent_notification' => 'A new verification link has been sent to your email address.',
    'profile' => 'Profile',

    // New Email Verification System
    'verify_email_title' => 'Verify Your Email Address',
    'verify_email_sent' => 'We\'ve sent a verification link to your email address:',
    'important' => 'Important',
    'verify_email_instructions' => 'Please check your email (including spam folder) and click the verification link to complete your registration.',
    'resend_verification_email' => 'Resend Verification Email',
    'register_different_email' => 'Register with a different email',
    'verification_link_resent' => 'Verification link sent successfully!',

    // Email Verification Success
    'verification_success_title' => 'Email Verified Successfully!',
    'verification_success_message' => 'Your email address has been verified and your account is now active.',
    'welcome' => 'Welcome',
    'account_ready' => 'Your account is ready to use.',
    'go_to_dashboard' => 'Go to Dashboard',
    'continue_shopping' => 'Continue Shopping',
    'verification_completed_at' => 'Verification completed at:',

    // Email Template
    'verify_email_subject' => 'Verify Your Email Address - Hanaya Shop',
    'email_greeting' => 'Thank you for registering with Hanaya Shop!',
    'email_verification_instruction' => 'Please click the button below to verify your email address and complete your account setup:',
    'verify_email_button' => 'Verify Email Address',
    'verification_link_expires' => 'This verification link will expire in 24 hours.',
    'email_manual_copy' => 'If the button doesn\'t work, you can copy and paste this link into your browser:',
    'email_not_requested' => 'If you didn\'t request this account, please ignore this email.',
    'email_footer' => 'Thank you for choosing :shop',

    // Error Messages
    'invalid_verification_token' => 'Invalid or expired verification token.',
    'verification_token_expired' => 'Verification token has expired. Please register again.',

    // Gmail Requirement
    'create_account' => 'Create Account',
    'create_account_description' => 'Join Hanaya Shop and start your shopping journey',
    'gmail_requirement_title' => '📧 Gmail Account Required',
    'gmail_requirement_description' => 'Please use a valid Gmail address for the best experience:',
    'gmail_for_order_updates' => 'Receive order status updates and notifications',
    'gmail_for_password_recovery' => 'Secure password recovery and account access',
    // 'gmail_for_account_security' => 'Enhanced account security and verification',
    'gmail_required_note' => 'Gmail address is required for notifications and security',

    // Support Contact
    'need_help' => 'Need help with verification?',
    'contact_support' => 'Contact Support',
    'support_email_subject' => 'Email Verification Help Request',
    'support_email_body' => 'Hello Hanaya Shop Support,

I need assistance with email verification for my account.

Email requiring verification support: :email

Please help me resolve this issue.

Thank you!',

    // Placeholders
    'email_placeholder' => 'Enter your email address',
    'password_placeholder' => 'Enter your password',
    'name_placeholder' => 'Enter your full name',
    'confirm_password_placeholder' => 'Re-enter your password',

    // Notes
    'notice' => 'Notice',
    'password_requirement_title' => 'Password Requirements',
    'password_requirement_description' => 'Password must contain at least 8 characters, including uppercase, lowercase, numbers, special characters, and no spaces.',

    // Password Reset Email
    'reset_password_subject' => 'Reset Password Notification',
    'reset_password_greeting' => 'Hello!',
    'reset_password_line' => 'You are receiving this email because we received a password reset request for your account.',
    'reset_password_action' => 'Reset Password',
    'reset_password_expire' => 'This password reset link will expire in :count minutes.',
    'reset_password_no_action' => 'If you did not request a password reset, no further action is required.',
    'reset_password_regards' => 'Regards,',
    'reset_password_signature' => 'Hanaya Shop',
    'reset_password_trouble' => 'If you\'re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:',

    // Test Account Experience
    'test_account_button' => 'Try With Test Account',
    'test_account_experience_title' => 'Experience our website before registering',
    'test_account_experience_description' => 'Use one of the test accounts below to explore all features without creating a new account',
    'test_account_free_note' => 'Everything is completely free, feel free to explore!',
    'test_account_password_note' => 'All test accounts use the same password: :password',
];
