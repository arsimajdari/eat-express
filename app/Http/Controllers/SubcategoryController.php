<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubcategoryResource;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = Subcategory::all();
        return SubcategoryResource::collection($subcategories);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        // Check if a subcategory with the same name and category already exists
        $existingSubcategory = Subcategory::where('name', $validated['name'])
            ->where('category_id', $validated['category_id'])
            ->first();

        if ($existingSubcategory) {
            return response()->json(['error' => 'Subcategory with the same name and category already exists'], 409);
        }

        // Create the subcategory if it doesn't exist
        $subcategory = Subcategory::create($validated);

        return new SubcategoryResource($subcategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        return new SubcategoryResource($subcategory);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'category_id' => ['exists:categories,id']
        ]);

        // Check if a subcategory with the same name and category already exists
        $existingSubcategory = Subcategory::where('name', $validated['name'])
            ->where('category_id', $validated['category_id'])
            ->where('id', '<>', $subcategory->id) // Exclude the current subcategory
            ->first();

        if ($existingSubcategory) {
            return response()->json(['error' => 'Subcategory with the same name and category already exists'], 409);
        }

        $subcategory->update($validated);

        return new SubcategoryResource($subcategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return response('Subcategory deleted successfully', 200);
    }
}
