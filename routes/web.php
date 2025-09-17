<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UtilityUsageController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::middleware(['auth','verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('utility-usages', UtilityUsageController::class);
    Route::post('utility-usages/generate-monthly', [UtilityUsageController::class, 'generateMonthly'])
        ->name('utility-usages.generate-monthly');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('buildings', BuildingController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('rentals', RentalController::class);
    Route::resource('tenants', TenantController::class);

    Route::get('/rooms/{room}/quick-add-rental', [RoomController::class, 'quickAddRental'])->name('rooms.quick-add-rental');
    Route::post('/rooms/{room}/quick-add-rental', [RoomController::class, 'storeQuickAddRental'])->name('rooms.store-quick-add-rental');
    Route::get('/rooms/quick-utility/{rental}', [RoomController::class, 'quickUtility'])->name('rooms.quick-utility');
    Route::post('/rooms/quick-utility/{rental}', [RoomController::class, 'storeQuickUtility'])->name('rooms.store-quick-utility');
});

// Include other route files
require __DIR__.'/auth.php';
require __DIR__.'/invoices.php';
