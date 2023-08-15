<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //

    public function checkout(Request $request)
{
    $itemsToAdd = collect(explode(",", $request->query('items')));

    if ($itemsToAdd->isEmpty()) {
        return response("No items specified", 400);
    }

    if (!auth()->check()) {
        return response("You should be logged in to add items to the cart", 400);
    }

    foreach ($itemsToAdd as $item) {
        $product = Product::find($item);

        if ($product) {
            CartItem::create([
                'user_id' => auth()->user()->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'tax' => $product->tax,
                'price' => $product->discount ? $product->discount : $product->price,
                'quantity' => 1,
            ]);
        }
    }
    
    return response("Items added to cart successfully", 200);



        //     $user = auth()->user();
        //     $cartItems = $request->input('cart');

        //     foreach ($cartItems as $item) {
        //         CartItem::create([
        //             'user_id' => $user->id,
        //             'product_id' => $item['id'],
        //             'quantity' => $item['quantity'],
        //         ]);
        //     }

        //     return response()->json(['message' => 'Cart items added successfully']);
        // }
    }

    public function builder()
    {
        $categories = Category::with('subcategories')->get();
        $products = Product::all();
        return response()->json([
            "categories" => $categories,
            "products" => $products,
            "hub" => $products->first(function ($value, $key) {
                return $value->sky == 'HH100';
            }),
        ]);
    }
}
