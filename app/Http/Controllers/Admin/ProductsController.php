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
    public function index(Request $request)
    {
        // Get filter parameters
        $categoryId = $request->input('category_id');
        $stockFilter = $request->input('stock_filter');
        
        // Start building the query
        $query = Product::with('category')->orderBy('created_at', 'desc'); // Sort by newest first
        
        // Apply category filter if selected
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        // Apply stock filter if selected
        if ($stockFilter) {
            if ($stockFilter === 'low_stock') {
                $query->where('stock_quantity', '<', 2)->where('stock_quantity', '>', 0);
            } elseif ($stockFilter === 'out_of_stock') {
                $query->where('stock_quantity', 0);
            }
        }
        
        // Paginate the results
        $products = $query->paginate(20)->withQueryString(); // Add withQueryString to maintain filters in pagination
        
        // Get all categories for the filter dropdown
        $categories = Category::all();
        
        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'selectedStockFilter' => $stockFilter,
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
        $searchQuery = trim($request->input('query', ''));
        $categoryId = $request->input('category_id');
        $stockFilter = $request->input('stock_filter');

        // Start building the query with ordering by newest first
        $productsQuery = Product::with('category')->orderBy('created_at', 'desc');

        // Apply search filter
        if (!empty($searchQuery)) {
            // Split keywords into separate words
            $keywords = preg_split('/\s+/', $searchQuery);

            $productsQuery->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('name', 'LIKE', "%{$keyword}%")
                            ->orWhere('descriptions', 'LIKE', "%{$keyword}%");
                    });
                }
            });
        }

        // Apply category filter if selected
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }
        
        // Apply stock filter if selected
        if ($stockFilter) {
            if ($stockFilter === 'low_stock') {
                $productsQuery->where('stock_quantity', '<', 2)->where('stock_quantity', '>', 0);
            } elseif ($stockFilter === 'out_of_stock') {
                $productsQuery->where('stock_quantity', 0);
            }
        }

        $products = $productsQuery->get();
        
        $html = view('admin.products.partials.table_rows', compact('products'))->render();

        return response()->json([
            'html' => $html,
            'count' => $products->count()
        ]);
    }

    /**
     * Delete a review (for inappropriate content)
     */
    public function deleteReview($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        
        // Delete associated image if exists
        if ($review->image_path && $review->image_path !== 'base.jpg') {
            $imagePath = public_path('images/reviews/' . $review->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $review->delete();

        return back()->with('success', 'Review and associated image deleted successfully.');
    }
}
