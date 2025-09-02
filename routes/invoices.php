<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Invoice Management
    Route::prefix('invoices')->group(function () {
        // Basic CRUD routes
        Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
        
        // Additional invoice actions
        Route::get('/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.download-pdf');
        Route::post('/bulk-generate', [InvoiceController::class, 'bulkGenerate'])->name('invoices.bulk-generate');
    });
});
