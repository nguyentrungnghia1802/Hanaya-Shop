<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product; // Assuming you have a Product model
use App\Models\Product\Category; // Assuming you have a Category model
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    //
    public function index()
    {
        $products = Cache::remember('admin_products_all', 600, function () {
            return Product::with('category')->get();
        });
        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        $categories = Category::all(); // Fetch all categories
        return view('admin.products.create', [
            'categories' => $categories,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'descriptions' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        $generatedFileName = null;
        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $generatedFileName = $imageName;
        } else {
            $generatedFileName = 'base.jpg'; // Default image if none is uploaded
        }
        $product = new Product();
        $product->name = $request->input('name');
        $product->descriptions = $request->input('descriptions');
        $product->price = $request->input('price');
        $product->stock_quantity = $request->input('stock_quantity');
        $product->image_url = $generatedFileName;
        $product->category_id = $request->input('category_id');
        if ($product->save()) {
            Cache::forget('admin_products_all');
            return redirect()->route('admin.product')->with('success', 'Product created successfully!');
        } else {
            return back()->with('error', 'Lưu sản phẩm thất bại!');
        }
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
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

        if ($request->hasFile('image_url')) {
            // Xóa ảnh cũ nếu không phải ảnh mặc định
            if ($product->image_url && $product->image_url !== 'base.jpg' && file_exists(public_path('images/' . $product->image_url))) {
                unlink(public_path('images/' . $product->image_url));
            }
            $image = $request->file('image_url');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $product->image_url = $imageName;
        }

        $product->save();
        Cache::forget('admin_products_all');
        return redirect()->route('admin.product')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh nếu không phải ảnh mặc định
        if ($product->image_url && $product->image_url !== 'base.jpg' && file_exists(public_path('images/' . $product->image_url))) {
            unlink(public_path('images/' . $product->image_url));
        }

        $product->delete();
        Cache::forget('admin_products_all');
        
        // Nếu là AJAX thì trả về JSON, không thì redirect
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.product')->with('success', 'Product deleted successfully!');
    }

    public function show($id, Request $request)
    {
        $product = Product::with('category')->findOrFail($id);

        // AJAX Quick View
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

        // Nếu không phải AJAX, trả về view chi tiết (bạn có thể tạo view này nếu muốn)
        return view('admin.products.show', [
            'product' => $product,
        ]);
    }
}
