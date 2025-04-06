<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\transactionsController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('orders', [orderController::class, 'index'])->name('orders.index');
Route::get('trasactions', [transactionsController::class, 'index'])->name('transactions.index');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/checkout.php';
