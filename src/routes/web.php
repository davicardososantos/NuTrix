<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaterConsumptionUIController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\PatientRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Patient registration routes (public - no authentication required)
Route::get('/paciente/{code}', [PatientRegistrationController::class, 'create'])->name('patient-registration');
Route::post('/paciente/{code}', [PatientRegistrationController::class, 'store'])->name('patient-registration.store');

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

    // Patient management routes (only for nutritionists)
    Route::middleware('nutritionist')->group(function () {
        Route::resource('patients', PatientController::class);
        Route::get('/patients/{patient}/code', [PatientController::class, 'showCode'])->name('patients.show-code');
    });
});

require __DIR__.'/auth.php';
