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
        
    }
}
