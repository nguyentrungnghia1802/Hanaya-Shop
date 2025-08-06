<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Success') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center mb-6">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-green-700">{{ __('checkout.order_placed_successfully') }}</h3>
                    <p class="text-gray-600">{{ __('checkout.order_id') }} #{{ $orderId }}</p>
                </div>
            </div>
            
            @php
                $order = \App\Models\Order\Order::with(['payment'])->find($orderId);
                // Check if payment relationship exists and get the first payment
                $payment = $order->payment()->first();
                if (!$payment) {
                    // Fallback if payment is not found
                    $payment = new \App\Models\Order\Payment([
                        'payment_method' => 'cash_on_delivery',
                        'payment_status' => 'pending',
                        'transaction_id' => 'N/A'
                    ]);
                }
            @endphp

            <div class="border-t border-b border-gray-200 py-4 my-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                    <div>
                        <h4 class="font-medium text-gray-700">{{ __('checkout.payment_method') }}</h4>
                        <p class="text-gray-600">
                            @if($payment->payment_method == 'credit_card')
                                {{ __('checkout.credit_card') }}
                            @elseif($payment->payment_method == 'paypal')
                                {{ __('checkout.paypal') }}
                            @elseif($payment->payment_method == 'cash_on_delivery')
                                {{ __('checkout.cash_on_delivery') }}
                            @endif
                        </p>
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $payment->payment_status == 'completed' ? 'bg-green-100 text-green-800' : 
                              ($payment->payment_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            @if($payment->payment_status == 'completed')
                                {{ __('checkout.completed') }}
                            @elseif($payment->payment_status == 'pending')
                                {{ __('checkout.pending') }}
                            @else
                                {{ __('checkout.failed') }}
                            @endif
                        </span>
                    </div>
                </div>
                
                @if($payment->transaction_id && $payment->payment_method != 'cash_on_delivery')
                <div class="text-sm text-gray-600">
                    <p>{{ __('checkout.transaction_id') }} <span class="font-mono">{{ $payment->transaction_id }}</span></p>
                </div>
                @endif
            </div>

            <p class="text-gray-700 leading-relaxed mb-4">
                {{ __('checkout.thank_you') }}<span class="font-semibold">Hanaya Shop</span>. 
                @if($payment->payment_method == 'cash_on_delivery')
                    {{ __('checkout.thank_you_message') }}
                @else
                    {{ __('checkout.thank_you_message_completed') }}
                @endif
            </p>
            
            <p class="text-gray-600 mb-6">
                {{ __('checkout.order_confirmation_email') }}
            </p>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            {{ __('checkout.order_confirmation') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col md:flex-row justify-end gap-4">
                <a href="{{ route('order.cancel', $orderId) }}" class="bg-gray-200 text-gray-800 px-5 py-2 rounded hover:bg-gray-300 transition text-center">
                    {{ __('checkout.order_cancellation') }}
                </a>
                <a href="{{ route('order.show', $orderId) }}" class="bg-pink-600 text-white px-5 py-2 rounded hover:bg-pink-700 transition text-center">
                    {{ __('checkout.view_order') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
