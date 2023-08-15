<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return CartItem::all()->toJson();
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
    public function store(Product $product, Request $request)
    {
        //
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        $user = Auth::user();


        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->discount ? $product->discount : $product->price,
            'tax' => $product->tax,
            'quantity' => $validated['quantity'],
            'description'=>$product->description,
            'image_src'=>$product->image_src,
        ]);

        return response('Item added to cart successfully', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cart)
    {

        // Delete a single item from cart

        if ($cart->user->id !== Auth::id()) {
            return response('Item could not be removed', 400);
        }

        $cart->delete();

        return response('Item removed successfully from cart', 200);
    }

    public function clear()
    {
        $user = Auth::user();
        $user->items()->delete();
        return response("Cart cleared successfully", 200);
    }
}
