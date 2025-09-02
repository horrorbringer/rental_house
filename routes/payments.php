<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Payment Management
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });

    // Invoice-specific payment routes
    Route::prefix('invoices')->group(function () {
        Route::get('/{invoice}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
    });
});
