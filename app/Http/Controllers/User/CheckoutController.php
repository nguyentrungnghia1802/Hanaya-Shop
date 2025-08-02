<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order\Order;
use App\Models\Order\OrderDetail;
use App\Models\Product\Product;
use App\Models\Cart\Cart;
use Illuminate\Support\Facades\Session;
use App\Notifications\NewOrderPending;
use App\Models\User;
use App\Notifications\OrderCancelledNotification;
use App\Notifications\OrderConfirmedNotification;
use App\Models\Order\Payment;
use App\Models\Address;

class CheckoutController extends Controller
{
    public function preview(Request $request)
    {
        $json = $request->input('selected_items_json');
        $selectedItems = json_decode($json, true) ?? [];

        $errorMessages = [];

        foreach ($selectedItems as $item) {
            $name = $item['name'] ?? 'Sản phẩm không xác định';
            $quantity = $item['quantity'] ?? 0;
            $stock = $item['stock_quantity'] ?? 0;

            if ($quantity > $stock) {
                $errorMessages[] = "Sản phẩm \"{$name}\" chỉ còn {$stock} sản phẩm trong kho.";
            }
        }

        if (!empty($errorMessages)) {
            return redirect()->back()->withErrors($errorMessages);
        }

        session(['selectedItems' => $selectedItems]);

        return redirect()->route('checkout.index');
    }

    public function index()
    {
        $user = Auth::user();
        $userName = $user->name;
        $addresses = Address::where('user_id', $user->id)->get();
        $firstAddress = $addresses->first();
        $selectedItems = session('selectedItems', []);
        if (empty($selectedItems)) {
            return redirect()->back()->with('error', 'Không có sản phẩm nào được chọn để đặt hàng.');
        }

        $paymentMethods = Payment::getAvailableMethods();

        return view('page.checkout.checkout', compact('selectedItems', 'paymentMethods', 'addresses', 'userName', 'firstAddress'));
    }

    public function success(Request $request)
    {
        $orderId = $request->get('order_id');

        return view('page.checkout.checkout_success', compact('orderId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $address = $request->input('address_id');
        $message = $request->input('note', '');
        $paymentData = $request->input('payment_data', '{}');

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:' . implode(',', Payment::getAvailableMethods()),
        ], [
            'address_id.required' => 'Vui lòng chọn địa chỉ nhận hàng.',
            'address_id.exists' => 'Địa chỉ không hợp lệ.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.'
        ]);

        // Get and validate payment method
        $paymentMethod = $request->input('payment_method');
        $allowedMethods = Payment::getAvailableMethods();
        
        // Ensure payment_method is a string (not null or empty)
        if (!is_string($paymentMethod) || empty(trim($paymentMethod))) {
            \Illuminate\Support\Facades\Log::error('Payment method is not a valid string', [
                'user_id' => $user->id,
                'payment_method' => $paymentMethod,
                'payment_method_type' => gettype($paymentMethod)
            ]);
            
            return redirect()
                ->route('checkout.index')
                ->with('error', 'Phương thức thanh toán không hợp lệ (định dạng không đúng)');
        }
        
        // Validate against allowed values
        if (!in_array($paymentMethod, $allowedMethods)) {
            \Illuminate\Support\Facades\Log::warning('Invalid payment method attempted', [
                'user_id' => $user->id,
                'payment_method' => $paymentMethod,
                'allowed_methods' => $allowedMethods
            ]);
            
            return redirect()
                ->route('checkout.index')
                ->with('error', 'Phương thức thanh toán không hợp lệ');
        }
        
        // Decode payment data with validation
        try {
            $paymentDataArray = json_decode($paymentData, true);
            if (!is_array($paymentDataArray)) {
                $paymentDataArray = [];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment data JSON decode error: ' . $e->getMessage());
            $paymentDataArray = [];
        }

        $itemsJson = $request->input('selected_items_json');
        
        try {
            $selectedItems = json_decode($itemsJson, true);
            if (!is_array($selectedItems) || empty($selectedItems)) {
                throw new \Exception('No items selected for checkout');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Selected items JSON decode error: ' . $e->getMessage());
            return redirect()
                ->route('checkout.index')
                ->with('error', 'Lỗi dữ liệu giỏ hàng. Vui lòng thử lại.');
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'     => $user->id,
                'total_price' => array_sum(array_column($selectedItems, 'subtotal')) + config('constants.checkout.shipping_fee', 8),
                'status'      => 'pending',
                'address_id'  => $address,
                'message'     => $message,
            ]);

            foreach ($selectedItems as $item) {
                $product = Product::find($item['id']);

                $product->stock_quantity -= $item['quantity'];
                $product->save();

                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            if ($order->status === 'pending') {
                $adminUsers = User::where('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    $admin->notify(new NewOrderPending($order));
                }
            }

            // Process payment based on the selected method
            $paymentService = app()->make(\App\Services\PaymentService::class);
            $paymentResult = $paymentService->processPayment($paymentMethod, $order, $paymentDataArray);
            
            if (!$paymentResult['success']) {
                throw new \Exception($paymentResult['message']);
            }

            $cartIds = array_column($selectedItems, 'cart_id');
            Cart::where('user_id', $user->id)
                ->whereIn('id', $cartIds)
                ->delete();

            DB::commit();

            session()->forget('selectedItems');

            // Redirect to success page with appropriate message
            $successMessage = $paymentResult['message'] ?? 'Đặt hàng thành công!';
            
            return redirect()
                ->route('checkout.success', ['order_id' => $order->id])
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Order creation error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'payment_method' => $paymentMethod,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->route('checkout.index')
                ->withInput()
                ->with('error', 'Lỗi khi xử lý đơn hàng: ' . $e->getMessage());
        }
    }
}
