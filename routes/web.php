<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecordSaleController;
use App\Http\Controllers\SalesHistoryController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));
Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store']);
Route::get('/logout', [AuthController::class, 'destroy']);

Route::middleware('pos.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
    Route::post('/products/{product}', [ProductController::class, 'update']);
    Route::post('/products/{product}/delete', [ProductController::class, 'destroy']);

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit']);
    Route::post('/customers/{customer}', [CustomerController::class, 'update']);
    Route::post('/customers/{customer}/delete', [CustomerController::class, 'destroy']);

    Route::get('/staff', [StaffController::class, 'index']);
    Route::post('/staff', [StaffController::class, 'store']);
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit']);
    Route::post('/staff/{staff}', [StaffController::class, 'update']);
    Route::post('/staff/{staff}/delete', [StaffController::class, 'destroy']);

    Route::get('/record-sale', [RecordSaleController::class, 'index']);
    Route::post('/record-sale', [RecordSaleController::class, 'store']);

    Route::get('/sales-history', [SalesHistoryController::class, 'index']);
});
