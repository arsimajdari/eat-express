<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    //

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $itemsToAdd = $request->input('items');

        if (empty($itemsToAdd)) {
            return response("No items specified", 400);
        }

        foreach ($itemsToAdd as $item) {
            $productId = $item['id'];
            $quantity = $item['quantity'];

            $product = Product::find($productId);

            if (!$product) {
                continue; // Skip processing if the product doesn't exist
            }

            $existingCartItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();

            if ($existingCartItem) {
                // Update the existing cart item's quantity
                $existingCartItem->quantity += $quantity;
                $existingCartItem->save();
            } else {
                // Create a new cart item
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->discount ? $product->discount : $product->price,
                    'tax' => $product->tax,
                    'quantity' => $quantity,
                    'description' => $product->description,
                    'image_src' => $product->image_src,
                ]);
            }
        }

        return response("Items added to cart successfully", 200);
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
