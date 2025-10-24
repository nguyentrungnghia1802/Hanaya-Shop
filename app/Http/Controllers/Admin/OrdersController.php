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


class OrdersController extends Controller
{
    //
    public function index()
    {
        $order = Order::all();
        return view('admin.orders.index', compact('order'));
    }

     public function show($orderId)
    {
        $order = Order::with('orderDetail.product', 'user')->findOrFail($orderId);
        return view('admin.orders.show', compact('order'));
    }
    public function confirm(Order $order)
    {
        $order->status = 'Shipped';
        $order->save();

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
             $admin->notify(new OrderConfirmedNotification($order));
        }

        return redirect()->back()->with('success', 'Đơn hàng đã được xác nhận và chuyển sang trạng thái Shipped.');
    }

}
