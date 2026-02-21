<?php

namespace App\Http\Controllers;

use App\Models\WaterConsumption;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WaterConsumptionController extends Controller
{
    /**
     * Listar todos os consumos de água do usuário autenticado.
     */
    public function index(): JsonResponse
    {
        $consumptions = auth()->user()->waterConsumptions()->get();

        return response()->json([
            'success' => true,
            'data' => $consumptions,
        ]);
    }

    /**
     * Registrar um novo consumo de água.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount_ml' => 'required|integer|min:1|max:10000',
            'consumption_date' => 'required|date|before_or_equal:today',
        ]);

        $consumption = auth()->user()->waterConsumptions()->create([
            'amount_ml' => $validated['amount_ml'],
            'consumption_date' => $validated['consumption_date'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Consumo de água registrado com sucesso',
            'data' => $consumption,
        ], 201);
    }

    /**
     * Exibir um consumo específico.
     */
    public function show(WaterConsumption $waterConsumption): JsonResponse
    {
        $this->authorize('view', $waterConsumption);

        return response()->json([
            'success' => true,
            'data' => $waterConsumption,
        ]);
    }

    /**
     * Atualizar um consumo de água.
     */
    public function update(Request $request, WaterConsumption $waterConsumption): JsonResponse
    {
        $this->authorize('update', $waterConsumption);

        $validated = $request->validate([
            'amount_ml' => 'sometimes|integer|min:1|max:10000',
            'consumption_date' => 'sometimes|date|before_or_equal:today',
        ]);

        $waterConsumption->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Consumo de água atualizado com sucesso',
            'data' => $waterConsumption,
        ]);
    }

    /**
     * Deletar um consumo de água.
     */
    public function destroy(WaterConsumption $waterConsumption): JsonResponse
    {
        $this->authorize('delete', $waterConsumption);

        $waterConsumption->delete();

        return response()->json([
            'success' => true,
            'message' => 'Consumo de água deletado com sucesso',
        ]);
    }

    /**
     * Obter consumo total do dia.
     */
    public function totalToday(): JsonResponse
    {
        $total = auth()->user()
            ->waterConsumptions()
            ->whereDate('consumption_date', today())
            ->sum('amount_ml');

        return response()->json([
            'success' => true,
            'date' => today(),
            'total_ml' => $total,
            'total_liters' => $total / 1000,
        ]);
    }

    /**
     * Obter consumo total de um período.
     */
    public function periodTotal(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $total = auth()->user()
            ->waterConsumptions()
            ->whereBetween('consumption_date', [
                $validated['start_date'],
                $validated['end_date'],
            ])
            ->sum('amount_ml');

        return response()->json([
            'success' => true,
            'period' => [
                'start' => $validated['start_date'],
                'end' => $validated['end_date'],
            ],
            'total_ml' => $total,
            'total_liters' => $total / 1000,
        ]);
    }
}
