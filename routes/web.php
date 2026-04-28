<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\PaymentRecordController;
use App\Http\Controllers\PaymentMethodController;

Route::get('/', function () {
    return redirect()->route('rooms.index');
})->name('home');

Route::middleware(['auth:landlord'])->group(function () {
    // Resource routes
    Route::resource('rooms', RoomController::class);
    Route::resource('tenants', TenantController::class);
    Route::resource('leases', LeaseController::class);
    Route::resource('payments', PaymentRecordController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
