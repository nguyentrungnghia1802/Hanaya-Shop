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
        try {
            $sessionId = Session::getId();

            $product = Product::findOrFail($productId);

            // Check if product already exists in cart
            $existing = Cart::where('session_id', $sessionId)
                ->where('product_id', $product->id)
                ->first();

            if ($existing) {
                // If exists → update quantity
                $existing->quantity += $request->input('quantity', 1);
                $existing->save();
            } else {
                // If not exists → create new
                Cart::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(), // if logged in
                    'quantity'   => $request->input('quantity', 1),
                    'session_id' => $sessionId,
                ]);
            }

            // Get updated cart count
            $cartCount = Cart::where('session_id', $sessionId)->sum('quantity');

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully!',
                    'cart_count' => $cartCount
                ]);
            }

            return redirect()->back()->with('success', 'Product added to cart successfully!');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while adding the product to cart'
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while adding the product to cart');
        }
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
