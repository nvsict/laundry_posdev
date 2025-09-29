<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index() { return Expense::with('expenseCategory')->latest()->get(); }

    public function store(Request $request) {
        $validatedData = $request->validate(['expense_category_id' => 'required|integer|exists:expense_categories,id', 'amount' => 'required|numeric|min:0', 'description' => 'nullable|string', 'expense_date' => 'required|date']);
        $expense = Expense::create($validatedData);
        return response()->json($expense->load('expenseCategory'), 201);
    }

    public function update(Request $request, Expense $expense) {
        $validatedData = $request->validate(['expense_category_id' => 'required|integer|exists:expense_categories,id', 'amount' => 'required|numeric|min:0', 'description' => 'nullable|string', 'expense_date' => 'required|date']);
        $expense->update($validatedData);
        return response()->json($expense->load('expenseCategory'));
    }

    public function destroy(Expense $expense) {
        $expense->delete();
        return response()->noContent();
    }
}