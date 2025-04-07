<?php

use App\Http\Controllers\CheckoutVehicleController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('checkout/vehicles/{vehicle}', [CheckoutVehicleController::class, 'show'])->name('vehicles.checkout.show');
    Route::post('/checkout/vehicles/{vehicle}', [CheckoutVehicleController::class, 'createOrder'])->name('vehicles.checkout.createOrder');
    Route::get('/checkout/vehicles/complete-payment/{order}', [CheckoutVehicleController::class, 'completePayment'])->name('vehicles.checkout.completePayment');
    Route::post('/checkout/vehicles/{vehicle}/process', [CheckoutVehicleController::class, 'checkout'])->name('vehicles.checkout.process');

    Route::get('/checkout/success', [CheckoutVehicleController::class, 'success'])->name('checkout-success');
    Route::get('/checkout/cancel', [CheckoutVehicleController::class, 'cancel'])->name('checkout-cancel');
    
});