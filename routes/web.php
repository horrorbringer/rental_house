<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomPublicController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UtilityRateController;
use App\Http\Controllers\UtilityUsageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public Room Routes (must be defined before the admin routes to prevent conflicts)
Route::prefix('public/rooms')->group(function () {
    Route::get('/', [RoomPublicController::class, 'index'])->name('tenant.rooms.index');
    Route::get('/search', [RoomPublicController::class, 'search'])->name('tenant.rooms.search');
    Route::get('/{room}', [RoomPublicController::class, 'show'])->name('tenant.rooms.show');
    Route::post('/register', [TenantController::class, 'register'])->name('tenants.register');
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

    // Utilities management
    Route::resource('utility-rates', UtilityRateController::class);

    // Tenant management
    Route::resource('tenants', TenantController::class);
});

// Include other route files
require __DIR__.'/auth.php';
require __DIR__.'/invoices.php';
require __DIR__.'/payments.php';
