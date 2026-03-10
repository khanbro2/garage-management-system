<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VehicleController;

Route::middleware(['auth:sanctum', 'ensure.garage.access'])->group(function () {
    
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('vehicles', VehicleController::class);
    
    Route::get('/search/customers', [CustomerController::class, 'search']);
    Route::get('/search/vehicles', [VehicleController::class, 'search']);
});