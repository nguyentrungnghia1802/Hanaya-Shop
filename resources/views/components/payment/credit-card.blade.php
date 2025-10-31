<div 
    x-data="creditCardPayment()" 
    x-show="isVisible" 
    class="payment-method-form" 
    id="credit-card-form">
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">{{ __('payment.pay_with_credit_card') }}</h3>
            <div class="flex space-x-2">
                <img src="{{ asset('fixed_resources/payment/visa.svg') }}" alt="Visa" class="h-6">
                <img src="{{ asset('fixed_resources/payment/mastercard.svg') }}" alt="Mastercard" class="h-6">
                <img src="{{ asset('fixed_resources/payment/amex.svg') }}" alt="American Express" class="h-6">
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="form-group">
                <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">{{ __('payment.card_number') }}</label>
                <input 
                    type="text" 
                    id="card_number" 
                    x-model="cardNumber" 
                    @input="formatCardNumber" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                    placeholder="1234 5678 9012 3456" 
                    maxlength="19">
                <p x-show="errors.cardNumber" x-text="errors.cardNumber" class="mt-1 text-sm text-red-600"></p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="expiry" class="block text-sm font-medium text-gray-700 mb-1">{{ __('payment.expiry_date') }}</label>
                    <input 
                        type="text" 
                        id="expiry" 
                        x-model="expiry" 
                        @input="formatExpiry" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                        placeholder="MM/YY" 
                        maxlength="5">
                    <p x-show="errors.expiry" x-text="errors.expiry" class="mt-1 text-sm text-red-600"></p>
                </div>
                
                <div class="form-group">
                    <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                    <input 
                        type="text" 
                        id="cvv" 
                        x-model="cvv" 
                        @input="validateCVV" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                        placeholder="123" 
                        maxlength="4">
                    <p x-show="errors.cvv" x-text="errors.cvv" class="mt-1 text-sm text-red-600"></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="card_holder" class="block text-sm font-medium text-gray-700 mb-1">{{ __('payment.card_holder_name') }}</label>
                <input 
                    type="text" 
                    id="card_holder" 
                    x-model="cardHolder" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500" 
                    placeholder="{{ __('payment.example_name') }}">
                <p x-show="errors.cardHolder" x-text="errors.cardHolder" class="mt-1 text-sm text-red-600"></p>
            </div>
        </div>

        <div class="mt-6">
            <button
                type="button"
                @click="processPayment"
                :disabled="isProcessing"
                class="w-full bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span x-show="isProcessing">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('payment.processing') }}
                </span>
                <span x-show="!isProcessing">
                    {{ __('payment.pay_now') }}
                </span>
            </button>
            <p class="text-xs text-center text-gray-500 mt-2">{{ __('payment.payment_security_note') }}</p>
        </div>
    </div>
</div>

<script>
    function creditCardPayment() {
        return {
            isVisible: false,
            isProcessing: false,
            cardNumber: '',
            expiry: '',
            cvv: '',
            cardHolder: '',
            errors: {},
            
            // Format card number as user types (adds spaces)
            formatCardNumber() {
                this.cardNumber = this.cardNumber.replace(/\D/g, '');
                this.cardNumber = this.cardNumber.replace(/(\d{4})(?=\d)/g, '$1 ');
                this.validateCardNumber();
            },
            
            // Format expiry date as MM/YY
            formatExpiry() {
                this.expiry = this.expiry.replace(/\D/g, '');
                if (this.expiry.length > 2) {
                    this.expiry = this.expiry.substring(0, 2) + '/' + this.expiry.substring(2);
                }
                this.validateExpiry();
            },
            
            // Validate CVV is numbers only
            validateCVV() {
                this.cvv = this.cvv.replace(/\D/g, '');
                if (this.cvv.length < 3) {
                    this.errors.cvv = "{{ __('payment.cvv_validation') }}";
                } else {
                    delete this.errors.cvv;
                }
            },
            
            // Validate card number using Luhn algorithm
            validateCardNumber() {
                const cardNumber = this.cardNumber.replace(/\s/g, '');
                if (cardNumber.length < 16) {
                    this.errors.cardNumber = "{{ __('payment.card_number_validation') }}";
                    return;
                }
                
                // Simple check - in real world we'd use Luhn algorithm
                if (!/^\d{16,19}$/.test(cardNumber)) {
                    this.errors.cardNumber = "{{ __('payment.invalid_card_number') }}";
                } else {
                    delete this.errors.cardNumber;
                }
            },
            
            // Validate expiry date format and not expired
            validateExpiry() {
                if (!this.expiry.includes('/')) return;
                
                const [month, year] = this.expiry.split('/');
                const currentDate = new Date();
                const currentYear = currentDate.getFullYear() % 100; // Get last two digits
                const currentMonth = currentDate.getMonth() + 1;
                
                if (parseInt(month) < 1 || parseInt(month) > 12) {
                    this.errors.expiry = "{{ __('payment.invalid_month') }}";
                } else if (parseInt(year) < currentYear || 
                          (parseInt(year) === currentYear && parseInt(month) < currentMonth)) {
                    this.errors.expiry = "{{ __('payment.card_expired') }}";
                } else {
                    delete this.errors.expiry;
                }
            },
            
            // Validate all fields before submission
            validateAll() {
                this.validateCardNumber();
                this.validateExpiry();
                this.validateCVV();
                
                if (!this.cardHolder.trim()) {
                    this.errors.cardHolder = "{{ __('payment.card_holder_required') }}";
                } else {
                    delete this.errors.cardHolder;
                }
                
                return Object.keys(this.errors).length === 0;
            },
            
            // Process the payment
            processPayment() {
                if (!this.validateAll()) {
                    return;
                }
                
                this.isProcessing = true;
                
                // In a real application, this would call your backend to process payment
                // For this demo, we simulate successful payment after a delay
                setTimeout(() => {
                    this.isProcessing = false;
                    
                    // Set payment method in form and submit
                    document.getElementById('payment_method_input').value = 'credit_card';
                    document.getElementById('payment_data').value = JSON.stringify({
                        last_digits: this.cardNumber.slice(-4),
                        expiry: this.expiry,
                        card_holder: this.cardHolder
                    });
                    
                    // Submit the form
                    document.getElementById('checkout-form').submit();
                }, 1500);
            }
        };
    }
</script>
