<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    // Debug: Log the structure of selectedItems
    Log::info('Selected Items Structure:', ['items' => $selectedItems]);

    // Validate structure of each item
    foreach ($selectedItems as $index => $item) {
        if (!isset($item['id']) || !isset($item['quantity']) || !isset($item['price'])) {
            Log::error('Invalid item structure at index ' . $index, ['item' => $item]);
            return back()->with('error', 'Dữ liệu sản phẩm không đầy đủ tại vị trí ' . ($index + 1));
        }
    }

    // Validate all products exist before creating order
    $productIds = array_column($selectedItems, 'id');
    $existingProducts = Product::whereIn('id', $productIds)->pluck('id')->toArray();
    $missingProducts = array_diff($productIds, $existingProducts);
    
    if (!empty($missingProducts)) {
        return back()->with('error', 'Một số sản phẩm không tồn tại: ' . implode(', ', $missingProducts));
    }

    DB::beginTransaction();

    try {
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => array_sum(array_column($selectedItems, 'subtotal')) + 20000,
            'status' => 'pending',
        ]);

        foreach ($selectedItems as $item) {
            // Kiểm tra product tồn tại
            $product = Product::find($item['id']);
            if (!$product) {
                throw new \Exception('Product not found: ' . $item['id']);
            }
            
            // Tính toán subtotal từ dữ liệu hiện tại thay vì tin tưởng frontend
            $actualSubtotal = $item['quantity'] * $product->price;
            
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price, // Sử dụng giá từ database
                'subtotal' => $actualSubtotal,
            ]);
        }

        DB::commit();

        // Xóa các items đã đặt hàng khỏi cart
        $cartIds = array_keys(session('selectedItems', []));
        if (!empty($cartIds)) {
            Cart::whereIn('id', $cartIds)->delete();
        }

        session()->forget('selectedItems');

        return redirect()->route('checkout.success')->with('success', 'Đặt hàng thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Order creation failed: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'selected_items' => $selectedItems,
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
    }
}

}
