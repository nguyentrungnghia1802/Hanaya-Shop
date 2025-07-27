<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    //
    public function index()
    {
        // Redirect to products page since reviews are managed there
        return redirect()->route('admin.product')->with('info', 'Reviews are managed within the product details pages.');
    }
}
