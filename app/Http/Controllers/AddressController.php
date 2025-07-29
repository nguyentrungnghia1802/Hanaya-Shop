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
    $request->validate([
        'phone_number' => 'required',
        'address' => 'required',
    ]);

    // Nếu có logic lấy toạ độ thì bật lại
    // $coordinates = $this->getCoordinatesFromAddress($request->address);
    // $coordinates = null;

    // if (!$coordinates) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Unable to fetch coordinates.'
    //     ], 422);
    // }

    $address = auth()->user()->addresses()->create([
        'phone_number' => $request->phone_number,
        'address' => $request->address,
        'latitude' => null,
        'longitude' => null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Address saved successfully.',
        'address' => $address,
    ]);
}



    private function getCoordinatesFromAddress($address)
{
    $apiKey = config('services.google.api_key');
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $apiKey;

    $response = Http::get($url);
    $data = $response->json();

    if (!empty($data['results']) && $data['status'] === 'OK') {
        $location = $data['results'][0]['geometry']['location'];
        return [
            'latitude' => $location['lat'],
            'longitude' => $location['lng'],
        ];
    }

    return null;
}

}
