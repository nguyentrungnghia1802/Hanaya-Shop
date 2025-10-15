<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart\Cart;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request, $productId)
    {
        $sessionId = Session::getId();

        $product = Product::findOrFail($productId);

        // Kiểm tra xem sản phẩm đã có trong cart chưa
        $existing = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            // Nếu có rồi → cập nhật số lượng
            $existing->quantity += $request->input('quantity', 1);
            $existing->save();
        } else {
            // Nếu chưa có → tạo mới
            Cart::create([
                'product_id' => $product->id,
                'quantity'   => $request->input('quantity', 1),
                'session_id' => $sessionId,
                // 'user_id' => auth()->id(), // nếu có đăng nhập
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $sessionId = Session::getId();

        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();

        $cart = [];

        foreach ($cartItems as $item) {
            $cart[$item->id] = [
                'name'       => $item->product->name,
                'image_url'  => $item->product->image_url,
                'price'      => $item->product->price,
                'quantity'   => $item->quantity,
            ];
        }

        return view('page.cart.index', compact('cart'));
    }

    /**
     * Xoá 1 item khỏi giỏ hàng
     */
    public function remove($id)
    {
        $sessionId = Session::getId();

        Cart::where('id', $id)
            ->where('session_id', $sessionId)
            ->delete();

        return redirect()->back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng.');
    }
}
