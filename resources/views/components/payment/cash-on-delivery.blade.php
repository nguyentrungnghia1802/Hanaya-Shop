<div 
    x-data="codPayment()" 
    x-show="isVisible" 
    class="payment-method-form" 
    id="cod-form">
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Thanh toán khi nhận hàng (COD)</h3>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
        
        <div class="bg-green-50 rounded-md p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Thanh toán tiện lợi</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Bạn chỉ cần thanh toán khi nhận được hàng và kiểm tra sản phẩm.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input
                        id="terms"
                        type="checkbox"
                        x-model="termsAccepted"
                        class="focus:ring-pink-500 h-4 w-4 text-pink-600 border-gray-300 rounded"
                    >
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="font-medium text-gray-700">Tôi đồng ý với điều khoản thanh toán khi nhận hàng</label>
                    <p class="text-gray-500">Tôi sẽ thanh toán đầy đủ khi nhận được hàng.</p>
                    <p x-show="errors.terms" x-text="errors.terms" class="mt-1 text-sm text-red-600"></p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <button
                type="button"
                @click="processPayment"
                :disabled="isProcessing || !termsAccepted"
                class="w-full bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span x-show="isProcessing">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Đang xử lý...
                </span>
                <span x-show="!isProcessing">
                    Đặt hàng ngay
                </span>
            </button>
        </div>
    </div>
</div>

<script>
    function codPayment() {
        return {
            isVisible: false,
            isProcessing: false,
            termsAccepted: false,
            errors: {},
            
            validateForm() {
                if (!this.termsAccepted) {
                    this.errors.terms = 'Bạn phải đồng ý với điều khoản thanh toán';
                    return false;
                }
                
                delete this.errors.terms;
                return true;
            },
            
            processPayment() {
                if (!this.validateForm()) {
                    return;
                }
                
                this.isProcessing = true;
                
                // Set payment method in form and submit
                setTimeout(() => {
                    document.getElementById('payment_method_input').value = 'cash_on_delivery';
                    document.getElementById('payment_data').value = JSON.stringify({
                        terms_accepted: true
                    });
                    
                    // Submit the form
                    document.getElementById('checkout-form').submit();
                }, 800);
            }
        };
    }
</script>
