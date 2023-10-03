<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
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
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'image_src' => ['required', 'string'],
            'price' => ['required', 'string'],
            'discount' => ['nullable', 'string'],
            'available' => ['boolean'],
            'images' => ['array', 'nullable'],
            'images.*' => ['image', 'nullable'],
        ]);

        if (!is_null(Product::where('slug', Str::slug($validated['name']))->first())) {
            return abort(response()->json(["error" => "Product with the same name already exists"], 409));
        }

        // After validating the request, check if the category and subcategory are related
        if (isset($validated['subcategory_id'])) {
            $category = Category::find($validated['category_id']);
            $subcategory = Subcategory::find($validated['subcategory_id']);

            // Check if the category and subcategory exist and are related
            if (!$category || !$subcategory || $category->id !== $subcategory->category_id) {
                return response()->json(['error' => 'Invalid category or subcategory relationship'], 422);
            }
        }

        // Create the product if everything is valid
        $product = Product::create(array_merge($validated, ['slug' => Str::slug($validated['name'])]));

        if (isset($validated['subcategory_id'])) {
            $product->subcategory()->associate($subcategory);
        }

        // Calculate 18% VAT from gross price
        $price = $product->discount ?? $product->price;
        $product->tax = (($price / 1.18) - $price) * -1;

        $product->save();

        return new ProductResource($product);
    }



    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return new ProductResource($product);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'sku' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'image_src' => ['required', 'string'],
            'price' => ['required', 'string'],
            'discount' => ['nullable', 'string'],
            'available' => ['boolean'],
            'images' => ['array', 'nullable'],
            'images.*' => ['image', 'nullable'],
        ]);

        $product->update($validated);
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Request $request)
    {

        if ($product->trashed()) $product->forceDelete();
        else $product->delete();

        return response('Product deleted successfully', 200);
    }

    public function getDetailedCartItems(Request $request)
    {
        $productIds = $request->input('productIds');

        $products = Product::whereIn('id', $productIds)->get();

        return ProductResource::collection($products);
    }

    public function getByCategory(Request $request)
    {
        $categoryId = $request->categoryId;


        $products = Product::where('category_id', $categoryId)->get();


        return ProductResource::collection($products);
    }


    public function getByCategoryAndSubcategory(Request $request)
    {
        $categoryId = $request->categoryId;
        $subcategoryId = $request->subcategoryId;

        $products = Product::where('category_id', $categoryId)->where('subcategory_id', $subcategoryId)->get();

        return ProductResource::collection($products);
    }
}
