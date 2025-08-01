<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Order\Payment;

class PaymentCastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add a global scope to ensure payment_method is cast as a string
        Payment::creating(function ($payment) {
            // Validate and sanitize payment_method before saving
            if (isset($payment->payment_method)) {
                $allowedMethods = ['credit_card', 'paypal', 'cash_on_delivery'];
                $method = trim((string)$payment->payment_method);
                
                if (!in_array($method, $allowedMethods)) {
                    \Illuminate\Support\Facades\Log::error('Invalid payment_method value detected', [
                        'provided_value' => $payment->payment_method,
                        'sanitized' => $method,
                        'allowed' => $allowedMethods
                    ]);
                    throw new \Exception("Invalid payment method: {$method}");
                }
                
                // Force cast to string to ensure proper database insertion
                $payment->payment_method = $method;
            }
        });
    }
}
