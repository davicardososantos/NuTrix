<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaterConsumptionUIController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Water consumption routes
    Route::get('/water-consumptions', [WaterConsumptionUIController::class, 'index'])->name('water-consumptions.index');
    Route::post('/water-consumptions', [WaterConsumptionUIController::class, 'store'])->name('water-consumptions.store');
    Route::get('/water-consumptions/{waterConsumption}/edit', [WaterConsumptionUIController::class, 'edit'])->name('water-consumptions.edit');
    Route::put('/water-consumptions/{waterConsumption}', [WaterConsumptionUIController::class, 'update'])->name('water-consumptions.update');
    Route::delete('/water-consumptions/{waterConsumption}', [WaterConsumptionUIController::class, 'destroy'])->name('water-consumptions.destroy');
});

require __DIR__.'/auth.php';
