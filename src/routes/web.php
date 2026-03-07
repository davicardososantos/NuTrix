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

Route::get('/painel', [PlatformController::class, 'entry'])
    ->middleware(['auth', 'verified'])
    ->name('painel');

// Patient registration routes (public - no authentication required)
Route::get('/paciente/{code}', [PatientRegistrationController::class, 'create'])->name('paciente.cadastro');
Route::post('/paciente/{code}', [PatientRegistrationController::class, 'store'])->name('paciente.cadastro.salvar');

Route::middleware('auth')->group(function () {
    // Platform selection
    Route::get('/portal', [PlatformController::class, 'select'])->name('portal.selecionar');
    Route::post('/portal', [PlatformController::class, 'set'])->name('portal.definir');

    // Profile routes
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil.editar');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('perfil.atualizar');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('perfil.excluir');

    // Weight tracking (quick)
    Route::post('/peso', [WeightController::class, 'storeQuick'])->name('peso.atualizar');

    // Water consumption routes
    Route::middleware('role:patient')->group(function () {
        Route::get('/consumos-agua', [WaterConsumptionController::class, 'index'])->name('consumos-agua.index');
        Route::post('/consumos-agua', [WaterConsumptionController::class, 'store'])->name('consumos-agua.store');
        Route::get('/consumos-agua/{waterConsumption}/editar', [WaterConsumptionController::class, 'edit'])->name('consumos-agua.editar');
        Route::put('/consumos-agua/{waterConsumption}', [WaterConsumptionController::class, 'update'])->name('consumos-agua.atualizar');
        Route::delete('/consumos-agua/{waterConsumption}', [WaterConsumptionController::class, 'destroy'])->name('consumos-agua.excluir');

        // Weight tracking routes
        Route::get('/pesos', [WeightController::class, 'index'])->name('pesos.index');
        Route::post('/pesos', [WeightController::class, 'store'])->name('pesos.store');
        Route::get('/pesos/{weightEntry}/editar', [WeightController::class, 'edit'])->name('pesos.editar');
        Route::put('/pesos/{weightEntry}', [WeightController::class, 'update'])->name('pesos.atualizar');
        Route::delete('/pesos/{weightEntry}', [WeightController::class, 'destroy'])->name('pesos.excluir');
    });

    // Patient management routes (only for nutritionists)
    Route::middleware('role:nutritionist')->group(function () {
        Route::resource('pacientes', PatientController::class)
            ->parameters(['pacientes' => 'patient']);
        Route::get('/pacientes/{patient}/codigo', [PatientController::class, 'showCode'])->name('pacientes.codigo');
        Route::get('/pacientes/{patient}/pesos', [PatientController::class, 'showWeights'])->name('pacientes.pesos');
        Route::get('/pacientes/{patient}/monitoramento', [PatientController::class, 'showMonitoring'])->name('pacientes.monitoramento');
    });
});

require __DIR__.'/auth.php';
