<?php

namespace App\Http\Controllers;

use App\Models\WaterConsumption;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaterConsumptionUIController extends Controller
{
    /**
     * Exibir página de consumo de água.
     */
    public function index(Request $request): View
    {
        $consumptions = auth()->user()
            ->waterConsumptions()
            ->orderByDesc('consumption_date')
            ->paginate(10);

        $totalToday = auth()->user()
            ->waterConsumptions()
            ->whereDate('consumption_date', today())
            ->sum('amount_ml');

        return view('water-consumptions.index', [
            'consumptions' => $consumptions,
            'totalToday' => $totalToday,
        ]);
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
