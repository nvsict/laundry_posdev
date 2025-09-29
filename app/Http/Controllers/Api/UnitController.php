<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit; // <-- THIS LINE WAS MISSING
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- Import the Rule class

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Unit::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:units|max:255',
            'short_name' => 'required|string|unique:units|max:10',
        ]);

        $unit = Unit::create($validatedData);
        return response()->json($unit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // ## NEW: Update an existing unit ##
    public function update(Request $request, Unit $unit)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('units')->ignore($unit->id)],
            'short_name' => ['required', 'string', 'max:10', Rule::unique('units')->ignore($unit->id)],
        ]);

        $unit->update($validatedData);
        return response()->json($unit);
    }

    // ## NEW: Delete a unit ##
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->noContent();
    }
}
