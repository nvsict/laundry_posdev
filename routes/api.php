<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController; 
use App\Http\Controllers\Api\ServiceTypeController;
use App\Http\Controllers\Api\CustomerController; 
use App\Http\Controllers\Api\ExpenseController;  
use App\Http\Controllers\Api\ExpenseCategoryController;  
use App\Http\Controllers\Api\UnitController; 
use App\Http\Controllers\Api\InventoryCategoryController; 
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController; 
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\ReportController; // <-- Add this
use App\Http\Controllers\Api\OrderController; // <-- Add this
use App\Http\Controllers\Api\StaffController; // <-- Add this
use App\Http\Controllers\Api\SettingController; // <-- Add this
use App\Http\Controllers\Api\RolePermissionController; // <-- Add this
use App\Http\Controllers\Api\DashboardController; // <-- Add this
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('services', ServiceController::class);
Route::get('/services/barcode/{barcode}', [ServiceController::class, 'findByBarcode']);
Route::apiResource('service-types', ServiceTypeController::class); 
Route::apiResource('customers', CustomerController::class); 
Route::apiResource('expenses', ExpenseController::class); 
Route::apiResource('expense-categories', ExpenseCategoryController::class);  
Route::apiResource('units', UnitController::class); 
Route::apiResource('inventory-categories', InventoryCategoryController::class); 
Route::apiResource('products', ProductController::class); 
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('purchases', PurchaseController::class);
Route::apiResource('orders', OrderController::class); 
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
Route::get('/reports/purchases', [ReportController::class, 'purchaseReport']); 
Route::get('/reports/sales', [ReportController::class, 'salesReport']);
Route::get('/reports/expenses', [ReportController::class, 'expenseReport']);
Route::get('/reports/dashboard-summary', [ReportController::class, 'dashboardSummary']);
Route::apiResource('staff', StaffController::class); // <-- And add this
Route::get('/settings', [SettingController::class, 'index']);
Route::post('/settings', [SettingController::class, 'update']);
Route::get('/roles-permissions', [RolePermissionController::class, 'index']);
Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'syncPermissions']);
Route::get('/dashboard', [DashboardController::class, 'index']);
