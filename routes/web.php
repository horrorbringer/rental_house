<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomPublicController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UtilityUsageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/rooms', [RoomPublicController::class, 'index'])->name('tenant.rooms.index');
Route::get('/rooms/search', [RoomPublicController::class, 'search'])->name('tenant.rooms.search');
Route::get('/rooms/{room}', [RoomPublicController::class, 'show'])->name('tenant.rooms.show');

Route::prefix('rooms')->group(function () {
    Route::get('/search', [TenantController::class, 'search'])->name('rooms.search');
    Route::post('/register', [TenantController::class, 'register'])->name('tenants.register');
    Route::get('/{room}/public', [TenantController::class, 'showPublic'])->name('rooms.public.show');
});

// Admin Routes
Route::middleware(['auth','verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('utility-usages', UtilityUsageController::class);
    Route::post('utility-usages/generate-monthly', [UtilityUsageController::class, 'generateMonthly'])
        ->name('utility-usages.generate-monthly');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Building and Room management
    Route::resource('buildings', BuildingController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('rentals', RentalController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    // Tenant management
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');
    Route::get('/tenants/{tenant}/edit', [TenantController::class, 'edit'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');
    Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');
});

require __DIR__.'/auth.php';
