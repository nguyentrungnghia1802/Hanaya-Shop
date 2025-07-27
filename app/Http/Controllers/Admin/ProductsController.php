<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Product\Review;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    /**
     * Display a listing of all products with their associated category.
     * Uses cache for performance optimization.
     */
    public function index()
    {
        // Không dùng cache cho phân trang
        $products = Product::with('category')->paginate(20); // 20 sản phẩm mỗi trang
        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all(); // Fetch all categories for the dropdown
        return view('admin.products.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created product in the database.
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'name' => 'required|string|max:255',
            'descriptions' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'view_count' => 'nullable|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload or fallback to default
        $generatedFileName = 'default-product.jpg'; // Default image
        if ($request->hasFile('image_url')) {
            $imageName = time() . '.' . $request->file('image_url')->extension();
            $request->file('image_url')->move(public_path('images/products'), $imageName);
            $generatedFileName = $imageName;
        }

        // Create and save product
        $product = new Product();
        $product->name = $request->input('name');
        $product->descriptions = $request->input('descriptions');
        $product->price = $request->input('price');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->image_url = $generatedFileName;
        $product->category_id = $request->input('category_id');
        $product->discount_percent = $request->input('discount_percent', 0);
        $product->view_count = $request->input('view_count', 0);

        if ($product->save()) {
            Cache::forget('admin_products_all'); // Invalidate cache
            return redirect()->route('admin.product')->with('success', 'Product created successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to create product.');
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); // Fetch categories for dropdown

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

        /**
     * Update the specified product in the database.
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'descriptions' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'view_count' => 'nullable|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->input('name');
        $product->descriptions = $request->input('descriptions');
        $product->price = $request->input('price');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->category_id = $request->input('category_id');
        $product->discount_percent = $request->input('discount_percent', 0);
        $product->view_count = $request->input('view_count', $product->view_count ?? 0);

        // Handle image replacement
        if ($request->hasFile('image_url')) {
            // Delete old image if it exists and is not default
            if ($product->image_url && $product->image_url !== 'default-product.jpg') {
                $oldImagePath = public_path('images/products/' . $product->image_url);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Upload new image
            $imageName = time() . '.' . $request->file('image_url')->extension();
            $request->file('image_url')->move(public_path('images/products'), $imageName);
            $product->image_url = $imageName;
        }

        $product->save();
        Cache::forget('admin_products_all'); // Invalidate cache
        
        return redirect()->route('admin.product')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from the database.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete associated image if not default
        if (
            $product->image_url &&
            $product->image_url !== 'base.jpg' &&
            file_exists(public_path('images/products/' . $product->image_url))
        ) {
            unlink(public_path('images/products/' . $product->image_url));
        }

        $product->delete();
        Cache::forget('admin_products_all'); // Invalidate cache

        // Return JSON if AJAX request, otherwise redirect
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.product')->with('success', 'Product deleted successfully!');
    }

    /**
     * Display the specified product details.
     * Supports JSON response for AJAX quick view.
     */
    public function show($id, Request $request)
    {
        $product = Product::with('category')->findOrFail($id);

        // Determine if this is an AJAX/JSON request
        if (
            $request->ajax() ||
            $request->wantsJson() ||
            $request->expectsJson() ||
            $request->header('X-Requested-With') === 'XMLHttpRequest' ||
            strpos($request->header('Accept', ''), 'application/json') !== false ||
            $request->query('ajax') === '1'
        ) {
            return response()->json([
                'success' => true,
                'id' => $product->id,
                'name' => $product->name,
                'descriptions' => $product->descriptions,
                'price' => $product->price,
                'stock_quantity' => $product->stock_quantity,
                'category_name' => $product->category ? $product->category->name : '',
                'image_url' => asset('images/products/' . ($product->image_url ?? 'base.jpg')),
            ]);
        }

        // Get reviews for this product
        $reviews = Review::with(['user', 'order'])
            ->where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Return product details view
        return view('admin.products.show', [
            'product' => $product,
            'reviews' => $reviews,
        ]);
    }

    public function search(Request $request)
    {
        $query = trim($request->input('query', ''));

        if (empty($query)) {
            $products = Product::with('category')->get();
        } else {
            // Tách từ khóa thành các từ riêng biệt
            $keywords = preg_split('/\s+/', $query);

            $products = Product::with('category')
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $q->where(function ($subQuery) use ($keyword) {
                            $subQuery->where('name', 'LIKE', "%{$keyword}%")
                                ->orWhere('descriptions', 'LIKE', "%{$keyword}%");
                        });
                    }
                })
                ->get();
        }

        $html = view('admin.products.partials.table_rows', compact('products'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Delete a review (for inappropriate content)
     */
    public function deleteReview($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
