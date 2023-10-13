<?php

namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return ShippingAddress::all()->where('user_id', $request->user()->id);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'zip' => ['required', 'string'],
            'country' => ['required', 'string'],
            'phone' => ['required', 'string'],
        ]);

        ShippingAddress::create(array_merge($validated, [
            'user_id' => request()->user()->id,
            'number' => (new ShippingAddress())->generateUniqueCode(),
        ]));

        return response("Address created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingAddress $address)
    {
        return response($address, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingAddress $address)
    {
    
        $validated = $request->validate([
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'zip' => ['required', 'string'],
            'country' => ['required', 'string'],
            'phone' => ['required', 'string'],
        ]);



        $address->update($validated);

        return response($address, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingAddress $address)
    {

        $address->delete();

        return response("Address deleted successfully");
    }
}
