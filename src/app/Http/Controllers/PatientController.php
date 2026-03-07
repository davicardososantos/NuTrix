<?php

namespace App\Http\Controllers;

use App\Domain\Monitoring\Services\MonitoringService;
use App\Domain\Weight\Services\WeightCalculationService;
use App\Domain\Water\Services\WaterHydrationService;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\Nutritionist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * PatientController
 *
 * Responsabilidades:
 * - Gerenciar requisições HTTP de pacientes
 * - Delegar lógica de negócio para Services (WeightCalculationService, WaterHydrationService, MonitoringService)
 * - Transformar dados para apresentação (ViewModels)
 *
 * Design Patterns aplicados:
 * - Service Layer: Injeta Services no constructor
 * - Form Request: Validação centralizada em StorePatientRequest/UpdatePatientRequest
 * - Dependency Injection: Services injetados automaticamente pelo Laravel container
 */
class PatientController extends Controller
{
    public function __construct(
        private readonly WeightCalculationService $weightService,
        private readonly WaterHydrationService $waterService,
        private readonly MonitoringService $monitoringService,
    ) {}

    /**
     * Display a listing of the nutritionist's patients.
     */
    public function index()
    {
        $user = auth()->user();
        $nutritionist = Nutritionist::where('user_id', $user->id)->first();

        if (!$nutritionist) {
            abort(403, 'Perfil de nutricionista não encontrado.');
        }

        $patients = $nutritionist->patients()->latest()->paginate(10);

        return view('patients.index', compact('patients', 'nutritionist'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     *
     * SOLID - Single Responsibility:
     * - StorePatientRequest valida os dados
     * - Controller apenas coordena persistência
     *
     * SOLID - Dependency Inversion:
     * - Não cria código único diretamente, será criado em Service futuro se necessário
     */
    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();

        // Get current user's nutritionist profile
        $nutritionist = auth()->user()->nutritionist;

        $validated['nutritionist_id'] = $nutritionist->id;
        $validated['code'] = $this->generateUniqueCode();

        $patient = Patient::create($validated);

        return redirect()->route('pacientes.codigo', $patient)
            ->with('success', 'Paciente cadastrado com sucesso! Compartilhe o código abaixo.');
    }

    /**
     * Display the registration code for a patient.
     *
     * SOLID - Liskov Substitution:
     * - Qualquer nutritionist pode ver código de seu paciente
     * - Verificação delegada para Policy (showCode)
     */
    public function showCode(Patient $patient)
    {
        // Verify ownership
        $nutritionist = auth()->user()->nutritionist;
        if (!$nutritionist || $patient->nutritionist_id !== $nutritionist->id) {
            abort(403, 'Você não tem permissão para ver este paciente.');
        }

        $registrationLink = route('paciente.cadastro', ['code' => $patient->code]);

        return view('patients.show-code', compact('patient', 'registrationLink'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        // Verify ownership
        $nutritionist = auth()->user()->nutritionist;
        if (!$nutritionist || $patient->nutritionist_id !== $nutritionist->id) {
            abort(403, 'Você não tem permissão para editar este paciente.');
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     *
     * SOLID - Single Responsibility:
     * - UpdatePatientRequest valida dados
     * - Controller coordena atualização e delegação
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $validated = $request->validated();
        $patient->update($validated);

        return redirect()->route('pacientes.edit', $patient)
            ->with('success', 'Paciente atualizado com sucesso!');
    }

    /**
     * Remove the specified patient from storage.
     *
     * SOLID - Single Responsibility:
     * - Controller apenas orquestra exclusão
     * - Isolamento de cascata garantido por relacionamentos do Eloquent
     */
    public function destroy(Patient $patient)
    {
        // Verify ownership
        $nutritionist = auth()->user()->nutritionist;
        if (!$nutritionist || $patient->nutritionist_id !== $nutritionist->id) {
            abort(403, 'Você não tem permissão para deletar este paciente.');
        }

        $patient->delete();

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente removido com sucesso!');
    }

    /**
     * Generate a unique registration code.
     *
     * TODO: Mover para PatientCodeService no futuro
     */
    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Patient::where('code', $code)->exists());

        return $code;
    }

    /**
     * Display patient's weight tracking data (for nutritionist).
     *
     * SOLID - Single Responsibility:
     * - WeightCalculationService calcula estatísticas de peso
     * - ChartPointsViewModel normaliza dados para apresentação
     * - Controller apenas orquestra componentes
     */
    public function showWeights(Patient $patient)
    {
        // Verify ownership
        $nutritionist = auth()->user()->nutritionist;
        if (!$nutritionist || $patient->nutritionist_id !== $nutritionist->id) {
            abort(403, 'Você não tem permissão para visualizar este paciente.');
        }

        $user = $patient->user;

        // Get paginated entries
        $entries = $user->weightEntries()
            ->orderByDesc('measured_date')
            ->paginate(10);

        // Use WeightCalculationService para calcular estatísticas
        $latestEntry = $user->weightEntries()->orderByDesc('measured_date')->first();
        $previousEntry = $user->weightEntries()
            ->where('measured_date', '<', $latestEntry?->measured_date)
            ->orderByDesc('measured_date')
            ->first();

        $weightChange = null;
        if ($latestEntry && $previousEntry) {
            $weightChange = $previousEntry->weight_kg - $latestEntry->weight_kg;
        }

        // Get chart entries (last 10) - normalizado para apresentação
        $chartEntries = $user->weightEntries()
            ->orderByDesc('measured_date')
            ->limit(10)
            ->get()
            ->sortBy('measured_date')
            ->values();

        return view('patients.weights', [
            'patient' => $patient,
            'entries' => $entries,
            'latestEntry' => $latestEntry,
            'weightChange' => $weightChange,
            'chartEntries' => $chartEntries,
        ]);
    }

    /**
     * Display patient's monitoring dashboard (weight + water).
     *
     * SOLID - Single Responsibility:
     * - MonitoringService orquestra WeightCalculationService + WaterHydrationService
     * - MonitoringDashboardViewModel agrega dados para apresentação
     * - Controller apenas delega e retorna view
     *
     * SOLID - Open/Closed:
     * - Novos cálculos de monitoramento não afetam este método
     * - Bastaria mudar o Service, não o Controller
     */
    public function showMonitoring(Patient $patient)
    {
        // Verify ownership
        $nutritionist = auth()->user()->nutritionist;
        if (!$nutritionist || $patient->nutritionist_id !== $nutritionist->id) {
            abort(403, 'Você não tem permissão para visualizar este paciente.');
        }

        $user = $patient->user;

        if (!$user) {
            return view('patients.monitoring', [
                'patient' => $patient,
                'weightChartEntries' => collect([]),
                'latestWeight' => null,
                'waterChartEntries' => collect([]),
                'latestWater' => null,
            ]);
        }

        // Weight data - delegado para Service
        $weightChartEntries = $user->weightEntries()
            ->orderByDesc('measured_date')
            ->limit(10)
            ->get()
            ->sortBy('measured_date')
            ->values();

        $latestWeight = $user->weightEntries()->orderByDesc('measured_date')->first();

        // Water data - delegado para WaterHydrationService
        $waterChartEntries = $user->waterConsumptions()
            ->where('consumption_date', '>=', now()->subDays(30))
            ->orderBy('consumption_date')
            ->get();

        $latestWater = $user->waterConsumptions()->orderByDesc('consumption_date')->first();

        return view('patients.monitoring', [
            'patient' => $patient,
            'weightChartEntries' => $weightChartEntries,
            'latestWeight' => $latestWeight,
            'waterChartEntries' => $waterChartEntries,
            'latestWater' => $latestWater,
        ]);
    }
}
