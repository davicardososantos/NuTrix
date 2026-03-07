<?php

namespace App\Http\Controllers;

use App\Domain\Weight\Services\WeightCalculationService;
use App\Http\Requests\StoreWeightEntryRequest;
use App\Http\Requests\UpdateWeightEntryRequest;
use App\Models\WeightEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * WeightController
 *
 * Responsabilidades:
 * - Gerenciar requisições HTTP de entradas de peso
 * - Delegar lógica de negócio para WeightCalculationService
 * - Transformar dados para apresentação
 *
 * Design Patterns aplicados:
 * - Service Layer: Injeta WeightCalculationService no constructor
 * - Form Request: Validação centralizada em StoreWeightEntryRequest/UpdateWeightEntryRequest
 * - Repository Pattern: Usa Eloquent queries através de modelos
 */
class WeightController extends Controller
{
    public function __construct(
        private readonly WeightCalculationService $weightCalculationService,
    ) {}

    /**
     * Exibir pagina principal de peso com estatisticas.
     *
     * SOLID - Single Responsibility:
     * - WeightCalculationService calcula estatísticas
     * - Controller apenas orquestra e formata resposta
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

        // Usar WeightCalculationService para calcular estatísticas
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

        // Get chart entries (last 10) - será normalizado em ChartPointsViewModel
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
     *
     * SOLID - Liskov Substitution:
     * - Política de autorização garante que apenas proprietário pode editar
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
     *
     * SOLID - Single Responsibility:
     * - StoreWeightEntryRequest valida dados
     * - Controller apenas coordena persistência
     * - WeightCalculationService será chamado após persistência em future refactoring
     */
    public function store(StoreWeightEntryRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $entry = auth()->user()->weightEntries()->create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peso registrado com sucesso',
                'data' => $entry,
            ], 201);
        }

        return redirect()->route('pesos.index')
            ->with('success', 'Peso registrado com sucesso!');
    }

    /**
     * Atualizar peso atual do paciente (fluxo rapido).
     */
    public function storeQuick(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
        ]);

        $patient = auth()->user()->patient;

        if (!$patient) {
            return back()->with('error', 'Perfil de paciente nao encontrado.');
        }

        $patient->update(['weight' => $validated['weight']]);

        return back()->with('success', 'Peso atualizado com sucesso!');
    }

    /**
     * Atualizar um peso.
     *
     * SOLID - Single Responsibility:
     * - UpdateWeightEntryRequest valida dados e autorização
     * - Controller apenas coordena atualização
     */
    public function update(UpdateWeightEntryRequest $request, WeightEntry $weightEntry): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $weightEntry->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Peso atualizado com sucesso',
                'data' => $weightEntry,
            ]);
        }

        return redirect()->route('pesos.index')
            ->with('success', 'Peso atualizado com sucesso!');
    }

    /**
     * Deletar um peso.
     *
     * SOLID - Single Responsibility:
     * - Política de autorização garante permissões
     * - Controller apenas coordena exclusão
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

        return redirect()->route('pesos.index')
            ->with('success', 'Peso deletado com sucesso!');
    }
}
