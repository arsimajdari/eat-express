<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Retrieve the currently logged-in user
        $user = Auth::user();

        // Get the user's cart
        $cart = $user->cart;

        // Retrieve all products in the user's cart
        $productsInCart = $cart->products;

        return $productsInCart->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart;

        $validated = $request->validate([
            "name" => 'required|string',
            "color" => 'required|string',
            "price" => 'required|numeric',
        ]);

        $product = Product::create($validated);

        $quantity = 1; // Set the quantity as needed
        $cart->products()->attach($product->id, ['quantity' => $quantity]);

        return response(['message' => 'Product added to cart successfully'], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
