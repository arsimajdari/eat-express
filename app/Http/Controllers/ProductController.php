<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $existingProduct = Product::where('name', $validated['name'])
            ->where('color', $validated['color'])
            ->where('price', $validated['price'])
            ->first();

        if ($existingProduct) {
            // Product exists in the database
            $existingCartItem = $cart->products()->find($existingProduct->id);

            if ($existingCartItem) {
                // Product also exists in the cart, increase quantity by 1
                $existingCartItem->pivot->quantity += 1;
                $existingCartItem->pivot->save();
            } else {
                // Product doesn't exist in cart, add it with quantity 1
                $cart->products()->attach($existingProduct->id, ['quantity' => 1]);
            }
        } else {
            // Product doesn't exist in the database, create and add to cart
            $product = Product::create($validated);
            $cart->products()->attach($product->id, ['quantity' => 1]);
        }

        return response(['message' => 'Product added to cart successfully'], 200);
    }



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        return $product->toJson();
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
    public function destroy(Product $product, Request $request)
    {
        $action = $request->input('action'); // Get the action from the request

        $cart = auth()->user()->cart; // Get the cart for the logged-in user

        if ($action === 'decrease') {
            $currentQuantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity;

            if ($currentQuantity > 1) {
                $cart->products()->updateExistingPivot($product->id, ['quantity' => DB::raw('quantity - 1')]);
            } else {
                $cart->products()->detach($product->id);
            }
        } elseif ($action === 'remove') {
            $cart->products()->detach($product->id);
        }

        return response('Product action performed successfully');
    }
}
