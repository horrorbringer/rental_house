<?php

use App\Http\Controllers\Tenant\RoomController;
use Illuminate\Support\Facades\Route;

Route::name('tenant.')->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
});
