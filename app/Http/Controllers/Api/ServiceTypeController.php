<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceTypeController extends Controller
{
    public function index()
    {
        return ServiceType::latest()->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:service_types|max:255',
        ]);
        $serviceType = ServiceType::create($validatedData);
        return response()->json($serviceType, 201);
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $validatedData = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('service_types')->ignore($serviceType->id)],
        ]);
        $serviceType->update($validatedData);
        return response()->json($serviceType);
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();
        return response()->noContent();
    }
}