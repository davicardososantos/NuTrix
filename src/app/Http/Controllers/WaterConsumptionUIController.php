<?php

namespace App\Http\Controllers;

use App\Models\WaterConsumption;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class WaterConsumptionUIController extends Controller
{
    /**
     * Exibir página de consumo de água.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $consumptions = $user
            ->waterConsumptions()
            ->orderByDesc('consumption_date')
            ->paginate(10);

        $totalToday = $user
            ->waterConsumptions()
            ->whereDate('consumption_date', today())
            ->sum('amount_ml');

        // Calcular meta de água usando OMS
        $dailyWaterGoal = $this->calculateWaterGoal();

        // Obter dados dos últimos 7 dias
        $sevenDaysData = $this->getSevenDaysData();

        return view('water-consumptions.index', [
            'consumptions' => $consumptions,
            'totalToday' => $totalToday,
            'dailyWaterGoal' => $dailyWaterGoal,
            'sevenDaysData' => $sevenDaysData,
        ]);
    }

    /**
     * Calcular meta de água baseado em altura e peso usando fórmula OMS.
     * Fórmula: (peso em kg × 35) = ml diários para pessoas ativas
     * Alternativa se sem dados: 2.5L (2500ml) como valor padrão
     */
    private function calculateWaterGoal(): int
    {
        $patient = auth()->user()->patient;

        // Use peso se disponível
        if ($patient && $patient->weight) {
            return (int)round($patient->weight * 35);
        }

        // Se altura disponível, estimar peso usando BMI médio (22) e altura em metros
        if ($patient && $patient->height) {
            // altura armazenada em cm? assumir se > 3 então é cm
            $heightCm = $patient->height;
            $heightM = $heightCm > 3 ? ($heightCm / 100) : $heightCm;
            $estimatedWeight = 22 * ($heightM * $heightM); // BMI 22
            return (int)round($estimatedWeight * 35);
        }

        // Valor padrão: 2.5L (2500ml)
        return 2500;
    }

    /**
     * Obter dados de consumo de água dos últimos 7 dias.
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

        // Criar array com 7 dias, mesmo que não tenha consumo
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
                'amount_ml' => $consumption ? $consumption->total_ml : 0,
                'amount_liters' => $consumption ? round($consumption->total_ml / 1000, 2) : 0,
            ];
        }

        return $data;
    }

    /**
     * Armazenar novo consumo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount_ml' => 'required|integer|min:1|max:10000',
            'consumption_date' => 'required|date|before_or_equal:today',
        ]);

        auth()->user()->waterConsumptions()->create($validated);

        return redirect()->route('water-consumptions.index')
            ->with('success', 'Consumo de água registrado com sucesso!');
    }

    /**
     * Exibir página de edição.
     */
    public function edit(WaterConsumption $waterConsumption): View
    {
        $this->authorize('update', $waterConsumption);

        return view('water-consumptions.edit', [
            'consumption' => $waterConsumption,
        ]);
    }

    /**
     * Atualizar consumo.
     */
    public function update(Request $request, WaterConsumption $waterConsumption)
    {
        $this->authorize('update', $waterConsumption);

        $validated = $request->validate([
            'amount_ml' => 'required|integer|min:1|max:10000',
            'consumption_date' => 'required|date|before_or_equal:today',
        ]);

        $waterConsumption->update($validated);

        return redirect()->route('water-consumptions.index')
            ->with('success', 'Consumo atualizado com sucesso!');
    }

    /**
     * Deletar consumo.
     */
    public function destroy(WaterConsumption $waterConsumption)
    {
        $this->authorize('delete', $waterConsumption);

        $waterConsumption->delete();

        return redirect()->route('water-consumptions.index')
            ->with('success', 'Consumo deletado com sucesso!');
    }
}
