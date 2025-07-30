<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AddressController extends Controller
{
    //
    public function store(Request $request)
    {
        dd('vao controller');
    $validated = $request->validate([
        'phone_number' => 'required',
        'address' => 'required',
    ]);

    $address = Address::create([
        'user_id' => auth()->id(),
        'phone_number' => $validated['phone_number'],
        'address' => $validated['address'],
    ]);

    return response()->json([
        'status' => 'success',
        'address' => $address,
    ]);
    }




    

}
