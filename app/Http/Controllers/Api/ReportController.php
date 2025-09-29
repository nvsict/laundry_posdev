<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\Expense; // <-- This line was missing
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function purchaseReport(Request $request)
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // 2. Query the database for purchases within the date range
        $purchases = Purchase::whereBetween('purchase_date', [
            $validated['start_date'],
            $validated['end_date']
        ])->get();

        // 3. Calculate the totals
        $totalAmount = $purchases->sum('total_amount');
        $totalPurchases = $purchases->count();

        // 4. Return the data as a JSON response
        return response()->json([
            'report_period' => [
                'start' => $validated['start_date'],
                'end' => $validated['end_date'],
            ],
            'summary' => [
                'total_purchases' => $totalPurchases,
                'total_amount_spent' => $totalAmount,
            ],
            'data' => $purchases, // Include the detailed purchase records
        ]);
    }
    // In app/Http/Controllers/Api/ReportController.php

public function dashboardSummary(Request $request)
{
    // --- Card Summaries (as before) ---
    $totalCustomers = Customer::count();
    $totalOrders = Order::count();
    $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');
    $recentOrders = Order::with('customer')->latest()->take(5)->get();

    // --- NEW: Data for Weekly Sales Bar Chart ---
    $salesLast7Days = Order::where('order_date', '>=', now()->subDays(7))
        ->select(DB::raw('DATE(order_date) as date'), DB::raw('sum(grand_total) as total'))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    // --- NEW: Data for Order Status Doughnut Chart ---
    $orderStatusSummary = Order::select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();

    return response()->json([
        'total_customers' => $totalCustomers,
        'total_orders' => $totalOrders,
        'total_revenue' => $totalRevenue,
        'recent_orders' => $recentOrders,
        
        // NEW chart data
        'sales_chart_data' => [
            'labels' => $salesLast7Days->pluck('date'),
            'data' => $salesLast7Days->pluck('total'),
        ],
        'status_chart_data' => [
            'labels' => $orderStatusSummary->pluck('status'),
            'data' => $orderStatusSummary->pluck('count'),
        ]
    ]);
}
// Inside the ReportController class

public function salesReport(Request $request)
{
    // 1. Validate the dates
    $validated = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // 2. Query the orders table within the date range
    $orders = Order::with('customer') // Eager load customer details
        ->whereBetween('order_date', [
            $validated['start_date'],
            $validated['end_date']
        ])->get();

    // 3. Calculate totals
    $totalRevenue = $orders->sum('grand_total');
    $totalOrders = $orders->count();

    // 4. Return the response
    return response()->json([
        'report_period' => $validated,
        'summary' => [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
        ],
        'data' => $orders,
    ]);
}
    public function expenseReport(Request $request)
{
    $validated = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Query expenses and eager load their categories
    $expenses = Expense::with('expenseCategory')
        ->whereBetween('expense_date', [
            $validated['start_date'],
            $validated['end_date']
        ])->get();

    $totalAmount = $expenses->sum('amount');
    $totalExpenses = $expenses->count();

    return response()->json([
        'report_period' => $validated,
        'summary' => [
            'total_expenses' => $totalExpenses,
            'grand_total_spent' => $totalAmount,
        ],
        'data' => $expenses,
    ]);
}
}


