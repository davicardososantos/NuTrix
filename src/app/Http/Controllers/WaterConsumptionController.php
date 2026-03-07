<?php

namespace App\Http\Controllers;

use App\Domain\Water\Services\WaterHydrationService;
use App\Http\Requests\StoreWaterConsumptionRequest;
use App\Http\Requests\UpdateWaterConsumptionRequest;
use App\Models\WaterConsumption;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * WaterConsumptionController
 *
 * Responsabilidades:
 * - Gerenciar requisições HTTP de consumo de água
 * - Delegar cálculos para WaterHydrationService
 * - Transformar dados para apresentação
 *
 * Design Patterns aplicados:
 * - Service Layer: Injeta WaterHydrationService no constructor
 * - Form Request: Validação centralizada em StoreWaterConsumptionRequest/UpdateWaterConsumptionRequest
 * - Dependency Injection: Services injetados automaticamente pelo Laravel container
 */
class WaterConsumptionController extends Controller
{
    public function __construct(
        private readonly WaterHydrationService $waterHydrationService,
    ) {}

    // ===========================================
    // WEB ROUTES - Retornam Views
    // ===========================================

    /**
     * Exibir página principal de consumo de água com estatísticas.
     *
     * SOLID - Single Responsibility:
     * - WaterHydrationService calcula meta diária e histórico
     * - Controller apenas orquestra apresentação
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

        // Delegar cálculos para WaterHydrationService
        $dailyWaterGoalVolume = $this->waterHydrationService->calculateDailyGoal($user->patient);
        $dailyWaterGoal = $dailyWaterGoalVolume->milliliters();
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
     *
     * SOLID - Liskov Substitution:
     * - Política de autorização garante permissões
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
     *
     * SOLID - Single Responsibility:
     * - StoreWaterConsumptionRequest valida dados
     * - Controller apenas coordena persistência
     */
    public function store(StoreWaterConsumptionRequest $request)
    {
        $validated = $request->validated();
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
        return redirect()->route('consumos-agua.index')
            ->with('success', 'Consumo de água registrado com sucesso!');
    }

    /**
     * Atualizar um consumo de água.
     *
     * SOLID - Single Responsibility:
     * - UpdateWaterConsumptionRequest valida dados e autorização
     * - Controller apenas coordena atualização
     */
    public function update(UpdateWaterConsumptionRequest $request, WaterConsumption $waterConsumption)
    {
        $validated = $request->validated();
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
        return redirect()->route('consumos-agua.index')
            ->with('success', 'Consumo atualizado com sucesso!');
    }

    /**
     * Deletar um consumo de água.
     *
     * SOLID - Single Responsibility:
     * - Política de autorização garante permissões
     * - Controller apenas coordena exclusão
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
        return redirect()->route('consumos-agua.index')
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
    // MÉTODOS PRIVADOS - Lógica de Apresentação
    // ===========================================

    /**
     * Obter dados de consumo dos últimos 7 dias.
     *
     * Retorna array com dados diários incluindo dias sem consumo.
     *
     * TODO: Mover para WaterHydrationService.getSevenDaysData() em refactoring futuro
     */
    private function getSevenDaysData(): array
    {
        $sevenDaysAgo = now()->subDays(6)->toDateString();

        $consumptions = auth()->user()
            ->waterConsumptions()
            ->whereDate('consumption_date', '>=', $sevenDaysAgo)
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
            $baseDate = now()->subDays($i);
            $date = $baseDate->toDateString();
            $dayName = $baseDate->locale('pt_BR')->dayName;
            $dayShort = $baseDate->locale('pt_BR')->isoFormat('ddd');
            $shortDay = $baseDate->format('d/m');

            $consumption = $consumptions->firstWhere('date', $date);

            $data[] = [
                'date' => $date,
                'day' => $dayName,
                'day_short' => $dayShort,
                'shortDay' => $shortDay,
                'amount_ml' => $consumption ? (int) $consumption->total_ml : 0,
                'amount_liters' => $consumption ? round($consumption->total_ml / 1000, 2) : 0,
            ];
        }

        return $data;
    }
}
