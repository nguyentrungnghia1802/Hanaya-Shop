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

    // Nếu hợp lệ thì lưu session và chuyển hướng
    session(['selectedItems' => $selectedItems]);

    return redirect()->route('checkout.index');
}

    public function index()
    {
        $selectedItems = session('selectedItems', []);
        if (empty($selectedItems)) {
        return redirect()->back()->with('error', 'Không có sản phẩm nào được chọn để đặt hàng.');
    }
        return view('page.checkout', compact('selectedItems'));
    }

    public function success(Request $request)
    {
        $orderId = $request->get('order_id');
        return view('page.checkout_success', compact('orderId'));
    }


    public function store(Request $request)
{
    $user = Auth::user();
    $itemsJson = $request->input('selected_items_json');
    $selectedItems = json_decode($itemsJson, true);
    DB::beginTransaction();


    try {
        $order = Order::create([
            'user_id'     => $user->id,
            'total_price' => array_sum(array_column($selectedItems, 'subtotal')) + 20000,
            'status'      => 'pending',
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

        $cartIds = array_column($selectedItems, 'id'); // id là ID của cart
            Cart::where('user_id', $user->id)
            ->whereIn('id', $cartIds)
            ->delete();

        DB::commit();

        session()->forget('selectedItems');

        return redirect()
            ->route('checkout.success', ['order_id' => $order->id])
            ->with('success', 'Đặt hàng thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        dd('Lỗi khi tạo đơn hàng:', $e->getMessage(), $e->getTraceAsString());
    }
}

}
