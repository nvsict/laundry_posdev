<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products|max:255',
            'quantity' => 'required|integer|min:0',
            'unit_id' => 'required|integer|exists:units,id',
            'inventory_category_id' => 'required|integer|exists:inventory_categories,id',
        ]);

        $product = Product::create($validatedData);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product) // <-- This line is the fix
    {
        return $product;
    }
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => ['nullable','string','max:255', Rule::unique('products')->ignore($product->id)],
            'quantity' => 'required|integer|min:0',
            'unit_id' => 'required|integer|exists:units,id',
            'inventory_category_id' => 'required|integer|exists:inventory_categories,id',
        ]);

        $product->update($validatedData);
        return response()->json($product);
    }

    // ProductController.php
public function getByBarcode($code) {
    $product = Product::where('barcode', $code)->first();

    if(!$product){
        return response()->json(['error' => 'Product not found'], 404);
    }

    return response()->json([
        'id' => $product->id,
        'name' => $product->name,
        'price' => $product->price
    ]);
}


    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
