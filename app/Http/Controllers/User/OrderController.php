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


class OrderController extends Controller
{
    public function index(){
        $userId = Auth::id();
        $orders = Order::with('orderDetail.product')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('page.order.index', compact('orders'));
    }

    public function show($orderId)
    {
        $order = Order::with('orderDetail.product')->findOrFail($orderId);
        return view('page.order.show', compact('order'));
    }

}
