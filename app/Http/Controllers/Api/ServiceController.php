<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Display a listing of all services.
     */
    public function index()
    {
        return Service::with('serviceType')->latest()->get();
    }

    /**
     * Store a newly created service in the database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|string|in:per_item,per_kg',
            'service_type_id' => 'required|integer|exists:service_types,id',
            'barcode' => 'nullable|string|unique:services|max:255',
        ]);

        $service = Service::create($validatedData);

        return response()->json($service->load('serviceType'), 201);
    }

    /**
     * Display a specific service.
     */
    public function show(Service $service)
    {
        return $service->load('serviceType');
    }

    /**
     * Update an existing service.
     */
    public function update(Request $request, Service $service)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_type' => 'required|string|in:per_item,per_kg',
            'service_type_id' => 'required|integer|exists:service_types,id',
            'barcode' => ['nullable', 'string', 'max:255', Rule::unique('services')->ignore($service->id)],
        ]);

        // CORRECT WAY: Just pass the validated data.
        $service->update($validatedData);

        return response()->json($service->load('serviceType'));
    }

    /**
     * Find a service by its barcode.
     */
    public function findByBarcode($barcode)
    {
        $service = Service::where('barcode', $barcode)->first();

        if ($service) {
            return response()->json($service);
        }

        return response()->json(['message' => 'Service not found'], 404);
    }

  public function destroy(Service $service)
{
    try {
        $service->delete();
        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete service: ' . $e->getMessage()
        ], 500);
    }
}


}