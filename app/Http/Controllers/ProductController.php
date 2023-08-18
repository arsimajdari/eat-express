<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return Product::all()->toJson();
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
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'sku' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            // 'categories' => ['required', 'array'],
            // 'categories.*' => ['integer', 'exists:subcategories,id'],
            'image_src' => ['required', 'string'],
            'price' => ['required', 'string'],
            'discount' => ['nullable', 'string'],
            'available' => ['boolean'],
            'images' => ['array', 'nullable'],
            'images.*' => ['image', 'nullable'],
        ]);


        if (!is_null(Product::where('slug', Str::slug($validated['name']))->first()))
            return response('error Product with same name already exists', 204);

        $product = Product::create(array_merge($validated, ['slug' => Str::slug($validated['name'])]));

        // if (isset($validated['categories']))
        //     $product->subcategories()->sync($validated['categories']);

        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $file) {
        //         $path = $file->storePublicly('images', 'public');

        //         $image = Image::create([
        //             'path' => $path,
        //         ]);

        //         $product->images()->save($image);
        //     }
        // }

        // Calculate 18% VAT from gross price
        $price = $product->discount ?? $product->price;
        $product->tax = (($price / 1.18) - $price) * -1;

        $product->save();

        return response('Product created successfully', 200);
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
        // $action = $request->input('action'); // Get the action from the request

        // $cart = auth()->user()->cart; // Get the cart for the logged-in user

        // if ($action === 'decrease') {
        //     $currentQuantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity;

        //     if ($currentQuantity > 1) {
        //         $cart->products()->updateExistingPivot($product->id, ['quantity' => DB::raw('quantity - 1')]);
        //     } else {
        //         $cart->products()->detach($product->id);
        //     }
        // } elseif ($action === 'remove') {
        //     $cart->products()->detach($product->id);
        // }

        // return response('Product action performed successfully');

        if ($product->trashed()) $product->forceDelete();
        else $product->delete();

        return response('Product deleted successfully', 200);
    }
    public function getDetailedCartItems(Request $request)
    {
        $productIds = $request->input('productIds');

        $products = Product::whereIn('id', $productIds)->get();

        return response()->json($products);
    }
}
