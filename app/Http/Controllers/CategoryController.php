<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        return CategoryResource::collection($categories);
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
        // Validate the request data
        $validated = $request->validate([
            'name' => ['required', 'string'],
        ]);

        // Check if a category with the same name already exists
        $existingCategory = Category::where('name', $validated['name'])->first();

        if ($existingCategory) {
            return response()->json(['error' => 'Category with the same name already exists'], 409);
        }

        // Create the category if it doesn't exist
        $category = Category::create($validated);

        return new CategoryResource($category);
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            "name" => ['string', 'required', 'unique:categories,name,' . $category->id],
        ]);


        $category->update($validated);

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();

        return response('Category deleted successfully', 200);
    }
}
