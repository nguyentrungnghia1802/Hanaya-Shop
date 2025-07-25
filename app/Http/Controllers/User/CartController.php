<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart\Cart;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function add(Request $request, $productId)
    {
        $sessionId = Session::getId();
        $product = Product::findOrFail($productId);
        $quantityToAdd = $request->input('quantity', 1);

        $existing = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        $currentQuantity = $existing ? $existing->quantity : 0;
        $newTotalQuantity = $currentQuantity + $quantityToAdd;

        if ($newTotalQuantity > $product->stock_quantity) {
            return redirect()->back()->with('error', 'Số lượng vượt quá số lượng tồn kho.');
        }

        if ($existing) {
            $existing->quantity = $newTotalQuantity;
            $existing->save();
        } else {
            Cart::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'quantity' => $quantityToAdd,
                'session_id' => $sessionId,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }


    /**
     * Display shopping cart
     */
    public function index()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $query = Cart::with('product');
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        $cartItems = $query->get();

        $cart = [];

        foreach ($cartItems as $item) {
            $cart[$item->id] = [
                'product_id' => $item->product->id,
                'product_quantity' => $item->product->stock_quantity,
                'name'       => $item->product->name,
                'image_url'  => $item->product->image_url,
                'price'      => $item->product->price,
                'quantity'   => $item->quantity,
            ];
        }


        return view('page.cart.index', compact('cart'));
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $sessionId = Session::getId();
        $userId = Auth::id();

        $query = Cart::where('id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $query->delete();

        return redirect()->back()->with('success', 'Product removed from cart successfully.');
    }

    public function buyNow(Request $request)
    {
        $productId = $request->input('product_id');
        $sessionId = Session::getId();

        $product = Product::findOrFail($productId);
        $existing = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->quantity += $request->input('quantity', 1);
            $existing->save();
        } else {
            Cart::create([
                'product_id' => $product->id,
                'quantity'   => $request->input('quantity', 1),
                'session_id' => $sessionId,
            ]);
        }

        return redirect()->route('cart.index')->with('product_id', $product->id);
    }
}
