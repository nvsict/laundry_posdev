<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    public function index() { return ExpenseCategory::latest()->get(); }

    public function store(Request $request) {
        $validatedData = $request->validate(['name' => 'required|string|unique:expense_categories|max:255']);
        $category = ExpenseCategory::create($validatedData);
        return response()->json($category, 201);
    }

    public function update(Request $request, ExpenseCategory $expenseCategory) {
        $validatedData = $request->validate(['name' => ['required','string','max:255', Rule::unique('expense_categories')->ignore($expenseCategory->id)]]);
        $expenseCategory->update($validatedData);
        return response()->json($expenseCategory);
    }

    public function destroy(ExpenseCategory $expenseCategory) {
        $expenseCategory->delete();
        return response()->noContent();
    }
}