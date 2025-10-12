<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product; // Assuming you have a Product model
use App\Models\Product\Category; // Assuming you have a Category model

class ProductsController extends Controller
{
    //
    public function index()
    {
        $products = Product::all(); // Assuming you have a Product model
        return view('admin.products.index',[
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
            return redirect()->route('admin.product')->with('success', 'Product created successfully!');
        } else {
            return back()->with('error', 'Lưu sản phẩm thất bại!');
        }
    }
}
