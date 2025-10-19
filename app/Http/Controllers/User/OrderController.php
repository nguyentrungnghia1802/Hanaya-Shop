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
    //
    public function store(Request $request)
{
    $user = Auth::user();
    //dd($user);
    if (!$user) {
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt hàng');
    }

    $itemsJson = $request->input('selected_items_json');
    $selectedItems = json_decode($itemsJson, true); 

    if (empty($selectedItems)) {
        return back()->with('error', 'Không có sản phẩm nào để đặt hàng.');
    }

    DB::beginTransaction();

    try {
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => array_sum(array_column($selectedItems, 'subtotal')) + 20000,
            'status' => 'pending',
        ]);

        foreach ($selectedItems as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        DB::commit();

        session()->forget('selectedItems');

        return redirect()->route('checkout.success')->with('success', 'Đặt hàng thành công!');
    } catch (\Exception $e) {
        dd('Lỗi khi tạo đơn hàng:', $e->getMessage(), $e->getTraceAsString());
        DB::rollBack();
        return back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
    }
}

}
