<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    //
    public function preview(Request $request)
{
    $json = $request->input('selected_items_json');
    $selectedItems = json_decode($json, true) ?? [];

    // Lưu vào session để hiển thị lại ở bước sau
    session(['selectedItems' => $selectedItems]);

    // Sau khi POST → redirect tới route GET để hiển thị
    return redirect()->route('checkout.index');
}
public function index()
{
    $selectedItems = session('selectedItems', []);
    return view('page.checkout', compact('selectedItems'));
}


    public function success()
    {
        return view('page.checkout_success');
    }
}
