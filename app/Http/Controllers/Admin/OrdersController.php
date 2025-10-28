<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order\Order;
use App\Models\Order\OrderDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product\Product;
use App\Models\Cart\Cart;
use Illuminate\Support\Facades\Session;
use App\Notifications\NewOrderPending;
use App\Notifications\OrderConfirmedNotification;
use App\Notifications\OrderShippedNotification;
use App\Notifications\OrderCompletedNotification;
use App\Notifications\OrderPaidNotification;
use App\Notifications\OrderCancelledNotification;
use App\Models\Order\Payment;

class OrdersController extends Controller
{
    public function index()
    {
        $order = Order::orderBy('created_at', 'desc')->get();
        $payment = Payment::all();
        return view('admin.orders.index', compact('order', 'payment'));
    }

    public function show($orderId)
    {
        $order = Order::with('orderDetail.product', 'user', 'address')->findOrFail($orderId);
        $payment = Payment::where('order_id', $order->id)->get();
        return view('admin.orders.show', compact('order', 'payment'));
    }

    public function confirm(Order $order)
    {
        $order->status = 'processing';
        $order->save();

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderConfirmedNotification($order));
        }

        return redirect()->back()->with('success', 'xác nhận đơn hàng thành công');
    }

    public function shipped(Order $order)
    {
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment->payment_status === 'completed') {
            $order->status = 'completed';
            $order->save();
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderShippedNotification($order));
                $admin->notify(new OrderCompletedNotification($order));
            }
        } else {
            $order->status = 'shipped';
            $order->save();
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderShippedNotification($order));
            }
        }
        return redirect()->back()->with('success', 'giao thành công');
    }

    public function paid(Order $order)
    {
        $payment = Payment::where('order_id', $order->id)->first();
        
        if (!$payment) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin thanh toán cho đơn hàng này');
        }
        
        DB::beginTransaction();
        try {
            $payment->payment_status = 'completed';
            $payment->save();
            
            // If order is shipped and payment is now completed, mark order as completed
            if ($order->status === 'shipped') {
                $order->status = 'completed';
                $order->save();
                
                // Notify admins
                $admins = User::where('role', 'admin')->get();
                $customer = User::find($order->user_id);
                
                foreach ($admins as $admin) {
                    $admin->notify(new OrderCompletedNotification($order));
                }
                
                // Notify customer
                if ($customer) {
                    $customer->notify(new OrderCompletedNotification($order));
                }
            } else {
                // Just notify about payment
                $admins = User::where('role', 'admin')->get();
                $customer = User::find($order->user_id);
                
                foreach ($admins as $admin) {
                    $admin->notify(new OrderPaidNotification($order));
                }
                
                // Notify customer
                if ($customer) {
                    $customer->notify(new OrderPaidNotification($order));
                }
            }
            
            DB::commit();
            return redirect()->back()->with('success', 'Xác nhận thanh toán thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi khi cập nhật trạng thái thanh toán: ' . $e->getMessage());
        }
    }

    public function cancel($orderId)
    {
        $order = Order::findOrFail($orderId);

        DB::beginTransaction();
        try {
            $payment = Payment::where('order_id', $order->id)->get();
            $payment->payment_status = 'failed';
            foreach ($order->orderDetail as $detail) {
                $product = $detail->product;
                $product->stock_quantity += $detail->quantity;
                $product->save();
            }
            $order->status = 'cancelled';
            $order->save();

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderCancelledNotification($order));
            }

            DB::commit();

            return redirect()->route('admin.order')->with('success', 'Order has been cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while cancelling the order: ' . $e->getMessage());
        }
    }
}
