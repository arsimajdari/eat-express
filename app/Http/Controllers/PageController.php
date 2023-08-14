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

        if (!empty($request->query('items'))) {
            foreach ($itemsToAdd as $item) {
                $product = Product::find($item);

                if (auth()->check()) {
                    CartItem::create([
                        'user_id' => auth()->user()->id,
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'tax' => $product->tax,
                        'price' => $product->discount ? $product->discount : $product->price,
                        'quantity' => 1,
                    ]);
                    return response("The item added successfully", 200);
                } else {
                    return response("You should be logged in to add item to cart", 400);
                }
            }
        }

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
