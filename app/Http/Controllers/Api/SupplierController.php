<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Supplier::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:suppliers|max:20',
            'email' => 'nullable|email|unique:suppliers|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier = Supplier::create($validatedData);
        return response()->json($supplier, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required','string','max:20', Rule::unique('suppliers')->ignore($supplier->id)],
            'email' => ['nullable','email','max:255', Rule::unique('suppliers')->ignore($supplier->id)],
            'address' => 'nullable|string',
        ]);
        $supplier->update($validatedData);
        return response()->json($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->noContent();
    }
}
