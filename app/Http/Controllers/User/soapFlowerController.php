<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class soapFlowerController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm, phân trang 12 sản phẩm mỗi trang
        $products = Product::orderBy('created_at', 'desc')->paginate(12);

        // Trả về view với biến $products
        return view('page.soapFlower', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Lấy các sản phẩm khác để gợi ý (trừ sản phẩm hiện tại)
        $relatedProducts = Product::where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('page.productDetail', compact('product', 'relatedProducts'));
    }
}
