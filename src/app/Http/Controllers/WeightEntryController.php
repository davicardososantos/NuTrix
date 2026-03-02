<?php

namespace App\Http\Controllers;

use App\Models\WeightEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WeightEntryController extends Controller
{
    /**
     * Exibir pagina principal de peso com estatisticas.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->expectsJson()) {
            $entries = auth()->user()->weightEntries()->get();

            return response()->json([
                'success' => true,
                'data' => $entries,
            ]);
        }

        $user = auth()->user();

        $entries = $user
            ->weightEntries()
            ->orderByDesc('measured_date')
            ->paginate(10);

        $latestEntry = $user
            ->weightEntries()
            ->orderByDesc('measured_date')
            ->first();

        $previousEntry = $user
            ->weightEntries()
            ->orderByDesc('measured_date')
            ->skip(1)
            ->first();

        $weightChange = null;
        if ($latestEntry && $previousEntry) {
            $weightChange = (float) $latestEntry->weight_kg - (float) $previousEntry->weight_kg;
        }

        $chartEntries = $user
            ->weightEntries()
            ->orderByDesc('measured_date')
            ->limit(10)
            ->get()
            ->sortBy('measured_date')
            ->values();

        return view('weights.index', [
            'entries' => $entries,
            'latestEntry' => $latestEntry,
            'previousEntry' => $previousEntry,
            'weightChange' => $weightChange,
            'chartEntries' => $chartEntries,
        ]);
    }

    /**
     * Exibir pagina de edicao de peso.
     */
    public function edit(WeightEntry $weightEntry): View
    {
        $this->authorize('update', $weightEntry);

        return view('weights.edit', [
            'entry' => $weightEntry,
        ]);
    }

    /**
     * Registrar um novo peso.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'weight_kg' => 'required|numeric|min:20|max:300',
            'measured_date' => 'required|date|before_or_equal:today',
        ]);

        $entry = auth()->user()->weightEntries()->create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peso registrado com sucesso',
                'data' => $entry,
            ], 201);
        }

        return redirect()->route('weights.index')
            ->with('success', 'Peso registrado com sucesso!');
    }

    /**
     * Atualizar um peso.
     */
    public function update(Request $request, WeightEntry $weightEntry): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $weightEntry);

        $validated = $request->validate([
            'weight_kg' => 'required|numeric|min:20|max:300',
            'measured_date' => 'required|date|before_or_equal:today',
        ]);

        $weightEntry->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peso atualizado com sucesso',
                'data' => $weightEntry,
            ]);
        }

        return redirect()->route('weights.index')
            ->with('success', 'Peso atualizado com sucesso!');
    }

    /**
     * Deletar um peso.
     */
    public function destroy(Request $request, WeightEntry $weightEntry): RedirectResponse|JsonResponse
    {
        $this->authorize('delete', $weightEntry);

        $weightEntry->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peso deletado com sucesso',
            ]);
        }

        return redirect()->route('weights.index')
            ->with('success', 'Peso deletado com sucesso!');
    }

}
