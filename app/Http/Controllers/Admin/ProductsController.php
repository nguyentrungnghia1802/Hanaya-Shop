<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\Product\Category;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    /**
     * Display a listing of all products with their associated category.
     * Uses cache for performance optimization.
     */
    public function index()
    {
        $products = Cache::remember('admin_products_all', 3600, function () {
            return Product::with('category')->get(); // Eager load categories
        });

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
            'descriptions' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Handle image upload or fallback to default
        $generatedFileName = null;
        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $generatedFileName = $imageName;
        } else {
            $generatedFileName = 'base.jpg'; // Default placeholder image
        }

        // Create and save product
        $product = new Product();
        $product->name = $request->input('name');
        $product->descriptions = $request->input('descriptions');
        $product->price = $request->input('price');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->image_url = $generatedFileName;
        $product->category_id = $request->input('category_id');

        if ($product->save()) {
            Cache::forget('admin_products_all'); // Invalidate product cache
            return redirect()->route('admin.product')->with('success', 'Product created successfully!');
        } else {
            return back()->with('error', 'Failed to save the product!');
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
            'descriptions' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->input('name');
        $product->descriptions = $request->input('descriptions');
        $product->price = $request->input('price');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->category_id = $request->input('category_id');

        // Handle image replacement
        if ($request->hasFile('image_url')) {
            // Delete old image if it exists and is not the default
            if (
                $product->image_url &&
                $product->image_url !== 'base.jpg' &&
                file_exists(public_path('images/' . $product->image_url))
            ) {
                unlink(public_path('images/' . $product->image_url));
            }

            // Upload new image
            $image = $request->file('image_url');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
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
            file_exists(public_path('images/' . $product->image_url))
        ) {
            unlink(public_path('images/' . $product->image_url));
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
                'image_url' => asset('images/' . ($product->image_url ?? 'base.jpg')),
            ]);
        }

        // Return product details view
        return view('admin.products.show', [
            'product' => $product,
        ]);
    }
}
