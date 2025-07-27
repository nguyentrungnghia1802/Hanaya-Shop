<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Review;
use App\Models\Product\Product;
use App\Models\Order\Order;
use App\Models\Order\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:10000',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;
        $orderId = $request->order_id;

        // Kiểm tra xem order có thuộc về user hiện tại không
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found or you do not have permission.');
        }

        // Kiểm tra xem order có status là shipped không
        $canReviewStatus = config('constants.review.can_review_status');
        if ($order->status !== $canReviewStatus) {
            return back()->with('error', 'You can only review products from shipped orders.');
        }

        // Kiểm tra xem product có trong order không
        $orderDetail = OrderDetail::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->first();

        if (!$orderDetail) {
            return back()->with('error', 'Product not found in this order.');
        }

        // Kiểm tra xem user đã review chưa
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product for this order.');
        }

        // Tạo review mới
        Review::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'order_id' => $orderId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    /**
     * Get reviews for a product
     */
    public function getProductReviews($productId)
    {
        $reviews = Review::with('user')
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($reviews);
    }

    /**
     * Show create review form
     */
    public function create(Request $request)
    {
        $productId = $request->get('product_id');
        $orderId = $request->get('order_id');
        $userId = Auth::id();

        // Validate order and product
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return redirect()->route('order.index')->with('error', 'Order not found.');
        }

        $product = Product::find($productId);
        if (!$product) {
            return redirect()->route('order.index')->with('error', 'Product not found.');
        }

        // Check if product is in order
        $orderDetail = OrderDetail::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->first();

        if (!$orderDetail) {
            return redirect()->route('order.index')->with('error', 'Product not found in this order.');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();

        if ($existingReview) {
            return redirect()->route('order.index')->with('error', 'You have already reviewed this product.');
        }

        return view('page.reviews.create', compact('product', 'order', 'orderDetail'));
    }
}
