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
        $order = Order::all();
        $payment = Payment::all();
        return view('admin.orders.index', compact('order', 'payment'));
    }

    public function show($orderId)
    {
        $order = Order::with('orderDetail.product', 'user')->findOrFail($orderId);
        return view('admin.orders.show', compact('order'));
    }

    public function confirm(Order $order)
    {
        $order->status = 'processing';
        $order->save();

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderConfirmedNotification($order));
        }

        return redirect()->back()->with('success', 'Đơn hàng đã được xác nhận và chuyển sang trạng thái processing.');
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
        if ($order->status === 'shipped') {
            $payment->payment_status = 'completed';
            $order->status = 'completed';
            $order->save();
            $payment->save();
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderCompletedNotification($order));
                $admin->notify(new OrderCompletedNotification($order));
            }
        } else {
            $payment->payment_status = 'completed';
            $payment->save();
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderPaidNotification($order));
            }
        }

        return redirect()->back()->with('success', 'Đơn hàng đã được xác nhận và chuyển sang trạng thái processing.');
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
