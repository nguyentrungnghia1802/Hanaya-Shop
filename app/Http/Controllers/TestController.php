<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function checkSchema()
    {
        $schema = DB::select('DESCRIBE payments');
        return response()->json($schema);
    }
}
