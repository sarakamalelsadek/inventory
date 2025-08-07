<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/stock-transfers', [StockTransferController::class, 'store']);
    Route::get('/inventory', [InventoryController::class, 'index']); 
    Route::get('/warehouses/{id}/inventory', [InventoryController::class, 'warehouseInventory']);



});


//admin apis
Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::get('/admin-dashboard', function () {
        return response()->json(['message' => 'Welcome, Admin!']);
    });

    Route::get('/users', [UserController::class, 'index']);
});
