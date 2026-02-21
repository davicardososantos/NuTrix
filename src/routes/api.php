<?php

use App\Http\Controllers\WaterConsumptionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // CRUD básico de consumo de água
    Route::apiResource('water-consumptions', WaterConsumptionController::class);

    // Endpoints utilitários
    Route::get('water-consumptions/stats/today', [WaterConsumptionController::class, 'totalToday'])->name('water-consumptions.today');
    Route::post('water-consumptions/stats/period', [WaterConsumptionController::class, 'periodTotal'])->name('water-consumptions.period');
});
