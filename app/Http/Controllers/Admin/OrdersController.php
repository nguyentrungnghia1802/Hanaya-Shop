<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\Payment;
use App\Models\Product\Product;
use App\Models\User;
use App\Notifications\CustomerOrderCancelledNotification;
use App\Notifications\CustomerOrderCompletedNotification;
use App\Notifications\CustomerOrderConfirmedNotification;
use App\Notifications\CustomerOrderShippedNotification;
use App\Notifications\OrderCancelledNotification;
use App\Notifications\OrderCompletedNotification;
use App\Notifications\OrderConfirmedNotification;
use App\Notifications\OrderPaidNotification;
use App\Notifications\OrderShippedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{
    /**
     * Display a paginated list of orders with optional search and status filtering.
     * Eager loads user relationship to avoid N+1 queries.
     * Returns the orders and payment data to the index view.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Order::with('user'); // tránh N+1

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('user_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('total_price', 'like', "%{$search}%")
                    ->orWhereDate('created_at', $search);
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        $order = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $payment = Payment::all();

        return view('admin.orders.index', compact('order', 'payment'));
    }

    /**
     * Show detailed information for a specific order, including order details, user, and address.
     * Also retrieves payment information for the order.
     * Returns the data to the show view.
     */
    public function show($orderId)
    {
        $order = Order::with('orderDetail.product', 'user', 'address')->findOrFail($orderId);
        $payment = Payment::where('order_id', $order->id)->get();

        return view('admin.orders.show', compact('order', 'payment'));
    }

    /**
     * Confirm an order and update its status to 'processing'.
     * Sends notifications to all admins (in English) and the customer (in current locale).
     * Redirects back with a success message.
     */
    public function confirm(Order $order)
    {
        $order->status = 'processing';
        $order->save();

        // Get current locale from session for customer notifications for customer notifications
        $currentLocale = Session::get('locale', config('app.locale'));

        // Gửi thông báo cho admin (admin uses English by default) (admin uses English by default)
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderConfirmedNotification($order)); // Admin uses English by default
        }

        // Gửi thông báo cho khách hàng đã đặt hàng (với URL khác)
        $customer = User::find($order->user_id);
        if ($customer) {
            $customer->notify(new CustomerOrderConfirmedNotification($order, $currentLocale));
        }

        return redirect()->back()->with('success', __('admin.order_confirmed_successfully'));
    }

    /**
     * Mark an order as shipped and update its status.
     * Sends notifications to all admins (in English) and the customer (in current locale).
     * Redirects back with a success message.
     */
    public function shipped(Order $order)
    {
        $order->status = 'shipped';
        $order->save();

        // Get current locale from session for customer notifications
        $currentLocale = Session::get('locale', config('app.locale'));

        // Gửi thông báo cho admin (admin uses English by default)
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderShippedNotification($order)); // Admin uses English by default
        }

        // Gửi thông báo cho khách hàng đã đặt hàng (với URL khác)
        $customer = User::find($order->user_id);
        if ($customer) {
            $customer->notify(new CustomerOrderShippedNotification($order, $currentLocale));
        }

        return redirect()->back()->with('success', __('admin.order_shipped_successfully'));
    }

    /**
     * Mark payment as completed for an order and update payment status.
     * Sends notifications to all admins and the customer.
     * If payment is completed, may also mark order as completed (commented logic).
     * Handles transaction and error rollback.
     * Redirects back with a success or error message.
     */
    public function paid(Order $order)
    {
        $payment = Payment::where('order_id', $order->id)->first();

        if (! $payment) {
            return redirect()->back()->with('error', __('admin.payment_info_not_found'));
        }

        DB::beginTransaction();
        try {
            $payment->payment_status = 'completed';
            $payment->save();

            // Get current locale from session for customer notifications
            $currentLocale = Session::get('locale', config('app.locale'));

            // Notify admins about payment completion
            $admins = User::where('role', 'admin')->get();
            $customer = User::find($order->user_id);

            foreach ($admins as $admin) {
                $admin->notify(new OrderPaidNotification($order)); // Admin uses English by default
            }

            // Notify customer with specific customer notification
            if ($customer) {
                // Create CustomerOrderPaidNotification if it doesn't exist, or use appropriate customer notification
                $customer->notify(new CustomerOrderCompletedNotification($order, $currentLocale));
            }

            // If order is shipped and payment is now completed, mark order as completed
            // if ($order->status === 'shipped') {
            //     $order->status = 'completed';
            //     $order->save();

            //     // Notify admins
            //     $admins = User::where('role', 'admin')->get();
            //     $customer = User::find($order->user_id);

            //     foreach ($admins as $admin) {
            //         $admin->notify(new OrderCompletedNotification($order));
            //     }

            //     // Notify customer
            //     if ($customer) {
            //         $customer->notify(new OrderCompletedNotification($order));
            //     }
            // } else {
            //     // Just notify about payment
            //     $admins = User::where('role', 'admin')->get();
            //     $customer = User::find($order->user_id);

            //     foreach ($admins as $admin) {
            //         $admin->notify(new OrderPaidNotification($order));
            //     }

            //     // Notify customer
            //     if ($customer) {
            //         $customer->notify(new OrderPaidNotification($order));
            //     }
            // }

            DB::commit();

            return redirect()->back()->with('success', __('admin.payment_confirmed_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', __('admin.payment_update_error').': '.$e->getMessage());
        }
    }

    /**
     * Cancel an order, update payment status, restore product stock, and update order status.
     * Sends notifications to all admins and the customer.
     * Handles transaction and error rollback.
     * Redirects back with a success or error message.
     */
    public function cancel($orderId)
    {
        $order = Order::findOrFail($orderId);

        DB::beginTransaction();
        try {
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment) {
                $payment->payment_status = 'failed';
                $payment->save();
            }
            foreach ($order->orderDetail as $detail) {
                $product = $detail->product;
                $product->stock_quantity += $detail->quantity;
                $product->save();
            }
            $order->status = 'cancelled';
            $order->save();

            // Get current locale from session for customer notifications
            $currentLocale = Session::get('locale', config('app.locale'));

            // Gửi thông báo cho admin (admin uses English by default)
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderCancelledNotification($order)); // Admin uses English by default
            }

            // Gửi thông báo cho khách hàng đã đặt hàng
            $customer = User::find($order->user_id);
            if ($customer) {
                $customer->notify(new CustomerOrderCancelledNotification($order, $currentLocale));
            }

            DB::commit();

            return redirect()->back()->with('success', __('admin.order_cancelled_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', __('admin.order_cancel_error').': '.$e->getMessage());
        }
    }
}
