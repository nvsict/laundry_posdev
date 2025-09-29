<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authenticated users
Route::middleware(['auth', 'verified'])->group(function () {

    // All authenticated users
    Route::get('/dashboard', function () { 
        return view('pos-dashboard'); 
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Permission-protected pages
    Route::get('/pos', function () { return view('pos'); })
        ->middleware('permission:create order');

    Route::get('/orders', function () { return view('orders'); })
        ->middleware('permission:view all orders');

    Route::get('/order-status', function () { return view('order-status'); })
        ->middleware('permission:update order status');

    Route::get('/orders/{order}/invoice', [InvoiceController::class, 'generateInvoice'])
        ->name('order.invoice')
        ->middleware('permission:view all orders');
     Route::get('/orders/{order}/receipt', [InvoiceController::class, 'generateReceipt'])->name('order.receipt')->middleware('permission:view all orders'); // ## ADDED: The missing route for receipts ##

    Route::get('/customers', function () { return view('customers'); });
    Route::get('/expenses', function () { return view('expenses'); });

    Route::get('/services', function () { return view('services'); })
        ->middleware('permission:manage services');

    Route::get('/inventory', function () { return view('inventory'); })
        ->middleware('permission:manage stock');

    Route::get('/purchases', function () { return view('purchases'); })
        ->middleware('permission:manage stock');

    // Admin-only pages
    Route::middleware(['role:admin'])->group(function () {

        Route::get('/reports', function () { return view('reports'); });
        Route::get('/staff', function () { return view('staff'); });
        Route::get('/permissions', function () { return view('permissions'); });

        // Settings routes (admin only)
        // Show settings page
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

// Auth routes (login, registration, password reset, etc.)
require __DIR__.'/auth.php';
