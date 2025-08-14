<div 
    x-data="paypalPayment()" 
    x-show="isVisible" 
    class="payment-method-form" 
    id="paypal-form">
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold">{{ __('payment.pay_with_paypal') }}</h3>
            <img src="{{ asset('fixed_resources/payment/paypal.svg') }}" alt="PayPal" class="h-8">
        </div>

        <div class="text-center py-4">
            <p class="text-gray-600 mb-6">
                {{ __('payment.paypal_redirect_message') }}
            </p>
            
            <div x-show="!isProcessing" class="flex justify-center">
                <button 
                    type="button" 
                    @click="processPayment"
                    class="bg-[#0070ba] hover:bg-[#005ea6] text-white py-3 px-8 rounded-md flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                <span class="font-semibold">{{ __('payment.pay_with_paypal') }}</span>
                    <span class="text-xl font-bold ml-1">Pay<span class="text-[#0070ba] bg-white px-0.5 rounded">Pal</span></span>
                </button>
            </div>
            
            <div x-show="isProcessing" class="flex flex-col items-center justify-center">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#0070ba] mb-4"></div>
                <p class="text-gray-600">{{ __('payment.redirecting_to_paypal') }}</p>
            </div>
        </div>
        
        <div class="mt-6 border-t pt-4">
            <p class="text-xs text-gray-500 text-center">
                {{ __('payment.paypal_security_note') }}
            </p>
        </div>
    </div>
</div>

<script>
    function paypalPayment() {
        return {
            isVisible: false,
            isProcessing: false,
            
            processPayment() {
                this.isProcessing = true;
                
                // In a real application, we would redirect to PayPal
                // For this demo, we simulate successful payment after a delay
                setTimeout(() => {
                    // Set payment method in form and submit - ensure it's a valid string value
                    const paymentMethodInput = document.getElementById('payment_method_input');
                    paymentMethodInput.value = 'paypal';
                    
                    // Validate that we have the right value before proceeding
                    if (paymentMethodInput.value !== 'paypal') {
                        console.error('Failed to set payment method value correctly');
                        return;
                    }
                    
                    document.getElementById('payment_data').value = JSON.stringify({
                        paypal_transaction: 'SIMULATED-' + Math.random().toString(36).substring(2, 15)
                    });
                    
                    // Submit the form
                    document.getElementById('checkout-form').submit();
                }, 1500);
            }
        };
    }
</script>
