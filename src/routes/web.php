<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaterConsumptionController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\WeightController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\Auth\PatientRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PlatformController::class, 'entry'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Patient registration routes (public - no authentication required)
Route::get('/paciente/{code}', [PatientRegistrationController::class, 'create'])->name('patient-registration');
Route::post('/paciente/{code}', [PatientRegistrationController::class, 'store'])->name('patient-registration.store');

Route::middleware('auth')->group(function () {
    // Platform selection
    Route::get('/portal', [PlatformController::class, 'select'])->name('platform.select');
    Route::post('/portal', [PlatformController::class, 'set'])->name('platform.set');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Weight tracking (quick)
    Route::post('/weight', [WeightController::class, 'store'])->name('weight.store');

    // Water consumption routes
    Route::middleware('role:patient')->group(function () {
        Route::get('/water-consumptions', [WaterConsumptionController::class, 'index'])->name('water-consumptions.index');
        Route::post('/water-consumptions', [WaterConsumptionController::class, 'store'])->name('water-consumptions.store');
        Route::get('/water-consumptions/{waterConsumption}/edit', [WaterConsumptionController::class, 'edit'])->name('water-consumptions.edit');
        Route::put('/water-consumptions/{waterConsumption}', [WaterConsumptionController::class, 'update'])->name('water-consumptions.update');
        Route::delete('/water-consumptions/{waterConsumption}', [WaterConsumptionController::class, 'destroy'])->name('water-consumptions.destroy');
    });

    // Patient management routes (only for nutritionists)
    Route::middleware('role:nutritionist')->group(function () {
        Route::resource('patients', PatientController::class);
        Route::get('/patients/{patient}/code', [PatientController::class, 'showCode'])->name('patients.show-code');
    });
});

require __DIR__.'/auth.php';
