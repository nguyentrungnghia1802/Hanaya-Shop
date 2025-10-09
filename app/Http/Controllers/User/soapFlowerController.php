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
        // Lấy sản phẩm cần xem chi tiết
        $product = Product::findOrFail($id);

        // Lấy lại danh sách sản phẩm để hiển thị bên dưới
        $products = Product::orderBy('created_at', 'desc')->paginate(12);

        // Trả về cùng view nhưng có thêm biến $product
        return view('page.soapFlower', compact('products', 'product'));
    }
}
