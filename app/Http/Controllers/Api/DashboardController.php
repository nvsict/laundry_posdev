<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer; // <-- This was missing
use App\Models\Expense;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Data for Cards & Status Chart
        $orderStatusCounts = Order::query()->select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');

        // Data for Payment Overview Chart
        $paymentStatusCounts = Order::query()->select('payment_status', DB::raw('count(*) as count'))->groupBy('payment_status')->pluck('count', 'payment_status');

        // Data for Today's Deliveries (can be searched and filtered)
        $deliveriesQuery = Order::with('customer')->whereDate('updated_at', today()); // Simplified for example

        if ($request->filled('search')) {
            $search = $request->search;
            $deliveriesQuery->where(function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('status')) {
            $deliveriesQuery->where('status', $request->status);
        }

        // Data for Revenue vs Expense Chart
        $daysInMonth = now()->daysInMonth;
        $revenueByDay = Order::query()->where('payment_status', 'paid')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->select(DB::raw('DAY(created_at) as day'), DB::raw('SUM(grand_total) as total'))->groupBy('day')->pluck('total', 'day');
        $expensesByDay = Expense::query()->whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->select(DB::raw('DAY(expense_date) as day'), DB::raw('SUM(amount) as total'))->groupBy('day')->pluck('total', 'day');
        
        $revenueData = []; $expenseData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $revenueData[] = $revenueByDay->get($i, 0);
            $expenseData[] = $expensesByDay->get($i, 0);
        }

        return response()->json([
            'cards' => [
                'pending' => $orderStatusCounts->get('pending', 0),
                'processing' => $orderStatusCounts->get('processing', 0),
                'ready' => $orderStatusCounts->get('ready', 0),
                'completed' => $orderStatusCounts->get('completed', 0),
            ],
            'todays_deliveries' => $deliveriesQuery->latest()->get(),
            'charts' => [
                'status_overview' => ['labels' => $orderStatusCounts->keys(), 'data' => $orderStatusCounts->values()],
                'payment_overview' => ['labels' => $paymentStatusCounts->keys(), 'data' => $paymentStatusCounts->values()],
                'payment_vs_expense' => ['labels' => range(1, $daysInMonth), 'revenue' => $revenueData, 'expenses' => $expenseData]
            ]
        ]);
    }
}