<?php

namespace App\Services;

use App\Models\Order\Order;
use App\Models\Order\Payment;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process a payment for an order
     *
     * @param string $paymentMethod
     * @param Order $order
     * @param array $paymentData
     * @return array
     */
    public function processPayment(string $paymentMethod, Order $order, array $paymentData = []): array
    {
        try {
            // Validate payment method to ensure it's one of the allowed values
            $allowedMethods = ['credit_card', 'paypal', 'cash_on_delivery'];
            if (!in_array($paymentMethod, $allowedMethods)) {
                throw new Exception('Invalid payment method: ' . $paymentMethod);
            }
            
            switch ($paymentMethod) {
                case 'credit_card':
                    return $this->processCreditCard($order, $paymentData);
                case 'paypal':
                    return $this->processPayPal($order, $paymentData);
                case 'cash_on_delivery':
                    return $this->processCOD($order);
                default:
                    throw new Exception('Invalid payment method');
            }
        } catch (Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
            ]);
            return [
                'success' => false,
                'message' => 'Lỗi xử lý thanh toán: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process credit card payment
     *
     * @param Order $order
     * @param array $paymentData
     * @return array
     */
    protected function processCreditCard(Order $order, array $paymentData): array
    {
        // In a real application, this would validate card details and call a payment gateway
        
        // Simulate credit card payment processing
        $transactionId = 'CC_' . uniqid() . '_' . $order->id;

        try {
            // Validate payment data
            if (empty($paymentData['last_digits'] ?? null)) {
                throw new \Exception('Missing credit card information');
            }
            
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'credit_card',
                'payment_status' => 'completed', // We're assuming successful payment for simplicity
                'transaction_id' => $transactionId,
            ]);
            
            // Update order status to processing
            $order->update(['status' => 'pending']);
        } catch (\Exception $e) {
            Log::error('Credit card payment processing error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'payment_data' => $paymentData,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return [
            'success' => true,
            'message' => 'Thanh toán thẻ tín dụng thành công',
            'transaction_id' => $transactionId,
            'payment_id' => $payment->id,
        ];
    }

    /**
     * Process PayPal payment
     *
     * @param Order $order
     * @param array $paymentData
     * @return array
     */
    protected function processPayPal(Order $order, array $paymentData): array
    {
        // In a real application, this would call the PayPal API
        
        // Simulate PayPal payment processing
        $transactionId = 'PP_' . uniqid() . '_' . $order->id;

        try {
            // Ensure the payment method is explicitly set to the string 'paypal'
            $paymentMethod = 'paypal';
            
            // Validate that the payment method is valid according to the enum definition
            $validMethods = ['credit_card', 'paypal', 'cash_on_delivery'];
            if (!in_array($paymentMethod, $validMethods)) {
                throw new \Exception('Invalid payment method value: ' . $paymentMethod);
            }
            
            // Create payment record with validated payment method
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'payment_status' => 'completed', // We're assuming successful payment for simplicity
                'transaction_id' => $transactionId,
            ]);
            
            // Update order status to processing
            $order->update(['status' => 'pending']);
        } catch (\Exception $e) {
            Log::error('PayPal payment processing error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'payment_data' => $paymentData,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return [
            'success' => true,
            'message' => 'Thanh toán PayPal thành công',
            'transaction_id' => $transactionId,
            'payment_id' => $payment->id,
        ];
    }

    /**
     * Process Cash on Delivery payment
     *
     * @param Order $order
     * @return array
     */
    protected function processCOD(Order $order): array
    {
        try {
            // Create payment record with pending status
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending', // Payment will be completed when delivery is done
                'transaction_id' => 'COD_' . $order->id,
            ]);
            
            // Update order status to processing
            $order->update(['status' => 'pending']);
            
        } catch (\Exception $e) {
            Log::error('COD payment processing error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return [
            'success' => true,
            'message' => 'Đặt hàng thành công. Thanh toán khi nhận hàng.',
            'payment_id' => $payment->id,
            'transaction_id' => $payment->transaction_id
        ];
    }

    /**
     * Update payment status
     *
     * @param Payment $payment
     * @param string $status
     * @return bool
     */
    public function updatePaymentStatus(Payment $payment, string $status): bool
    {
        try {
            $validStatuses = ['pending', 'completed', 'failed'];
            
            if (!in_array($status, $validStatuses)) {
                throw new Exception('Invalid payment status');
            }
            
            $payment->payment_status = $status;
            return $payment->save();
        } catch (Exception $e) {
            Log::error('Payment status update error: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'status' => $status,
            ]);
            return false;
        }
    }
}
