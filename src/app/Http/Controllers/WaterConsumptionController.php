<?php

namespace App\Http\Controllers;

use App\Models\WaterConsumption;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class WaterConsumptionController extends Controller
{
    // ===========================================
    // WEB ROUTES - Retornam Views
    // ===========================================

    /**
     * Exibir página principal de consumo de água com estatísticas.
     */
    public function index(Request $request)
    {
        // Para requisições API, retornar JSON
        if ($request->expectsJson()) {
            $consumptions = auth()->user()->waterConsumptions()->get();

            return response()->json([
                'success' => true,
                'data' => $consumptions,
            ]);
        }

        // Para requisições web, retornar view
        $user = auth()->user();

        $consumptions = $user
            ->waterConsumptions()
            ->orderByDesc('consumption_date')
            ->paginate(10);

        $totalToday = $user
            ->waterConsumptions()
            ->whereDate('consumption_date', today())
            ->sum('amount_ml');

        $dailyWaterGoal = $this->calculateWaterGoal();
        $sevenDaysData = $this->getSevenDaysData();

        return view('water-consumptions.index', [
            'consumptions' => $consumptions,
            'totalToday' => $totalToday,
            'dailyWaterGoal' => $dailyWaterGoal,
            'sevenDaysData' => $sevenDaysData,
        ]);
    }

    /**
     * Exibir página de edição de consumo.
     */
    public function edit(WaterConsumption $waterConsumption): View
    {
        $this->authorize('update', $waterConsumption);

        return view('water-consumptions.edit', [
            'consumption' => $waterConsumption,
        ]);
    }

    // ===========================================
    // CRUD - Respondem com JSON ou Redirect
    // ===========================================

    /**
     * Registrar um novo consumo de água.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount_ml' => 'required|integer|min:1|max:10000',
            'consumption_date' => 'required|date|before_or_equal:today',
        ]);

        $consumption = auth()->user()->waterConsumptions()->create($validated);

        // Resposta para API
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Consumo de água registrado com sucesso',
                'data' => $consumption,
            ], 201);
        }

        // Resposta para Web
        return redirect()->route('water-consumptions.index')
            ->with('success', 'Consumo de água registrado com sucesso!');
    }

    /**
     * Atualizar um consumo de água.
     */
    public function update(Request $request, WaterConsumption $waterConsumption)
    {
        $this->authorize('update', $waterConsumption);

        $validated = $request->validate([
            'amount_ml' => 'required|integer|min:1|max:10000',
            'consumption_date' => 'required|date|before_or_equal:today',
        ]);

        $waterConsumption->update($validated);

        // Resposta para API
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Consumo de água atualizado com sucesso',
                'data' => $waterConsumption,
            ]);
        }

        // Resposta para Web
        return redirect()->route('water-consumptions.index')
            ->with('success', 'Consumo atualizado com sucesso!');
    }

    /**
     * Deletar um consumo de água.
     */
    public function destroy(Request $request, WaterConsumption $waterConsumption)
    {
        $this->authorize('delete', $waterConsumption);

        $waterConsumption->delete();

        // Resposta para API
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Consumo de água deletado com sucesso',
            ]);
        }

        // Resposta para Web
        return redirect()->route('water-consumptions.index')
            ->with('success', 'Consumo deletado com sucesso!');
    }

    // ===========================================
    // API ROUTES - Retornam apenas JSON
    // ===========================================

    /**
     * Exibir um consumo específico (API).
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
     * Obter consumo total do dia (API).
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
            'total_liters' => round($total / 1000, 2),
        ]);
    }

    /**
     * Obter consumo total de um período (API).
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
            'total_liters' => round($total / 1000, 2),
        ]);
    }

    // ===========================================
    // MÉTODOS PRIVADOS - Lógica de Negócio
    // ===========================================

    /**
     * Calcular meta diária de água baseado em peso e altura.
     *
     * Usa fórmula da OMS: peso (kg) × 35ml
     * Se não houver peso, estima com IMC de 22 usando a altura.
     * Valor padrão: 2500ml (2.5L)
     */
    private function calculateWaterGoal(): int
    {
        $patient = auth()->user()->patient;

        // Usa peso direto se disponível
        if ($patient && $patient->weight) {
            return (int) round($patient->weight * 35);
        }

        // Estimar peso usando altura e IMC médio (22)
        if ($patient && $patient->height) {
            $heightCm = $patient->height;
            $heightM = $heightCm > 3 ? ($heightCm / 100) : $heightCm;
            $estimatedWeight = 22 * ($heightM * $heightM);
            return (int) round($estimatedWeight * 35);
        }

        // Valor padrão recomendado pela OMS
        return 2500;
    }

    /**
     * Obter dados de consumo dos últimos 7 dias.
     *
     * Retorna array com dados diários incluindo dias sem consumo.
     */
    private function getSevenDaysData(): array
    {
        $sevenDaysAgo = now()->subDays(6);

        $consumptions = auth()->user()
            ->waterConsumptions()
            ->where('consumption_date', '>=', $sevenDaysAgo)
            ->select(
                DB::raw('DATE(consumption_date) as date'),
                DB::raw('SUM(amount_ml) as total_ml')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Criar array com todos os 7 dias
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->locale('pt_BR')->dayName;
            $shortDay = now()->subDays($i)->format('d/m');

            $consumption = $consumptions->firstWhere('date', $date);

            $data[] = [
                'date' => $date,
                'day' => $dayName,
                'shortDay' => $shortDay,
                'amount_ml' => $consumption ? (int) $consumption->total_ml : 0,
                'amount_liters' => $consumption ? round($consumption->total_ml / 1000, 2) : 0,
            ];
        }

        return $data;
    }
}
