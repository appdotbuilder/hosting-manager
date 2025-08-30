<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Customer management routes
    Route::resource('customers', CustomerController::class);
    
    // Order management routes
    Route::resource('orders', OrderController::class);
    
    // Invoice management routes
    Route::resource('invoices', InvoiceController::class)->except(['create', 'store', 'edit']);
    
    // Service management routes
    Route::resource('services', ServiceController::class)->except(['create', 'store', 'edit']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
