<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Order::with('customer', 'services')->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'order_number' => 'required|string|unique:orders',
            'status' => 'required|string|in:pending,processing,ready,completed,cancelled',
            'payment_status' => 'required|string|in:paid,unpaid',
            'order_date' => 'required|date',
            'services' => 'required|array',
            'services.*.service_id' => 'required|integer|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Use a transaction for safety
        DB::beginTransaction();
        try {
            // 1. Fetch settings from the database
            $settings = Setting::pluck('value', 'key');

            // 2. Calculate Subtotal from the incoming services array
            $subtotal = collect($validatedData['services'])->sum(function ($service) {
                return $service['quantity'] * $service['unit_price'];
            });

            // 3. Calculate Service Charge based on settings
            $serviceChargeAmount = 0;
            if (($settings->get('enable_service_charge')) === '1') {
                $chargeValue = floatval($settings->get('service_charge_value', 0));
                if ($settings->get('service_charge_type') === 'percentage') {
                    $serviceChargeAmount = $subtotal * ($chargeValue / 100);
                } else {
                    $serviceChargeAmount = $chargeValue;
                }
            }

            // 4. Calculate Tax (GST)
            $taxRate = floatval($settings->get('default_gst_rate', 0));
            $taxableAmount = $subtotal + $serviceChargeAmount;
            $taxAmount = $taxableAmount * ($taxRate / 100);
            
            // 5. Calculate Grand Total
            $grandTotal = $subtotal + $serviceChargeAmount + $taxAmount;

            // 6. Now, create the order with all calculated values
            $order = Order::create([
                'customer_id' => $validatedData['customer_id'],
                'order_number' => $validatedData['order_number'],
                'subtotal' => $subtotal,
                'service_charge_amount' => $serviceChargeAmount,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'status' => $validatedData['status'],
                'payment_status' => $validatedData['payment_status'],
                'order_date' => $validatedData['order_date'],
            ]);

            // 7. Attach the services to the order
            foreach ($validatedData['services'] as $serviceData) {
                $order->services()->attach($serviceData['service_id'], [
                    'quantity' => $serviceData['quantity'],
                    'unit_price' => $serviceData['unit_price'],
                ]);
            }

            DB::commit();
            return response()->json($order->load('services'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create order', 'error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Update an existing order's status.
     */
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:pending,processing,ready,completed,cancelled',
            'payment_status' => 'required|string|in:paid,unpaid',
        ]);
        $order->update($validatedData);
        return response()->json($order->load('customer'));
    }
}