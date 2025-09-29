<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return InventoryCategory::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:inventory_categories|max:255',
        ]);

        $category = InventoryCategory::create($validatedData);
        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function update(Request $request, InventoryCategory $inventoryCategory)
    {
        $validatedData = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('inventory_categories')->ignore($inventoryCategory->id)],
        ]);

        $inventoryCategory->update($validatedData);
        return response()->json($inventoryCategory);
    }

    public function destroy(InventoryCategory $inventoryCategory)
    {
        $inventoryCategory->delete();
        return response()->noContent();
    }

}
