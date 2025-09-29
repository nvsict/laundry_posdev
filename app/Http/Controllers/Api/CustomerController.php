<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer; // <-- Import the Customer model
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import the Rule class

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return all customers, newest first
        return Customer::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers|max:20',
            'email' => 'nullable|email|unique:customers|max:255',
            'address' => 'nullable|string',
        ]);

        // Create the new customer
        $customer = Customer::create($validatedData);

        // Return a success response
        return response()->json($customer, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // ## NEW: Update an existing customer ##
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Ensure phone is unique, but ignore the current customer's own phone number
            'phone' => ['required', 'string', 'max:20', Rule::unique('customers')->ignore($customer->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'address' => 'nullable|string',
        ]);

        $customer->update($validatedData);

        return response()->json($customer);
    }

    // ## NEW: Delete a customer ##
    public function destroy(Customer $customer)
    {
        $customer->delete();

        // Return a 204 No Content response on success
        return response()->noContent();
    }
}
