<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\WaterConsumptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// Public endpoints
Route::post('check-email', function (Request $request) {
    $exists = User::where('email', $request->email)->exists();
    return response()->json(['exists' => $exists]);
})->name('check-email');

Route::middleware('auth')->group(function () {
    // CRUD básico de consumo de água
    Route::apiResource('water-consumptions', WaterConsumptionController::class);

    // Endpoints utilitários
    Route::get('water-consumptions/stats/today', [WaterConsumptionController::class, 'totalToday'])->name('water-consumptions.today');
    Route::post('water-consumptions/stats/period', [WaterConsumptionController::class, 'periodTotal'])->name('water-consumptions.period');
});
