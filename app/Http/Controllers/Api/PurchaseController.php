<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        // 'with()' loads the related supplier and products for each purchase
        return Purchase::with('supplier', 'products')->latest()->get();
    }

    public function show(Purchase $purchase)
    {
        return $purchase->load('supplier', 'products');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'reference_no' => 'required|string|unique:purchases',
            'total_amount' => 'required|numeric',
            'status' => 'required|string|in:received,pending,ordered',
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'supplier_id' => $validatedData['supplier_id'],
                'purchase_date' => $validatedData['purchase_date'],
                'reference_no' => $validatedData['reference_no'],
                'total_amount' => $validatedData['total_amount'],
                'status' => $validatedData['status'],
            ]);

            foreach ($validatedData['products'] as $productData) {
                $purchase->products()->attach($productData['product_id'], [
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                ]);

                $product = Product::find($productData['product_id']);
                $product->quantity += $productData['quantity'];
                $product->save();
            }

            DB::commit();

            return response()->json($purchase->load('products'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create purchase', 'error' => $e->getMessage()], 500);
        }
    }

        // ## NEW: Update an existing purchase's status ##
    public function update(Request $request, Purchase $purchase)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:received,pending,ordered',
        ]);

        $purchase->update($validatedData);
        return response()->json($purchase->load('supplier'));
    }
    
    public function destroy(Purchase $purchase)
    {
        // NOTE: A real-world app would need logic to adjust stock levels here.
        // This simple version just deletes the record.
        $purchase->products()->detach(); // Remove entries from the pivot table
        $purchase->delete(); // Delete the main purchase record
        
        return response()->noContent();
    }
}