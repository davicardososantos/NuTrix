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
    Route::apiResource('consumos-agua', WaterConsumptionController::class);

    // Endpoints utilitários
    Route::get('consumos-agua/estatisticas/hoje', [WaterConsumptionController::class, 'totalToday'])->name('consumos-agua.hoje');
    Route::post('consumos-agua/estatisticas/periodo', [WaterConsumptionController::class, 'periodTotal'])->name('consumos-agua.periodo');
});
