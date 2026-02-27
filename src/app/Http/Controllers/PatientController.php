<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Nutritionist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientController extends Controller
{
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
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:patients,email'],
            'birth_date' => ['nullable', 'date'],
            'biological_sex' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'work_routine' => ['nullable', 'string'],
            'main_goal' => ['nullable', 'string'],
            'referral_source' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
        ], [
            'full_name.required' => 'O nome do paciente é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.unique' => 'Este email já está registrado para outro paciente.',
            'weight.numeric' => 'O peso deve ser um número válido.',
            'height.numeric' => 'A altura deve ser um número válido.',
        ]);

        // Generate unique code
        $validated['code'] = $this->generateUniqueCode();

        // Get current user's nutritionist profile
        $user = auth()->user();
        $nutritionist = Nutritionist::where('user_id', $user->id)->first();

        if (!$nutritionist) {
            abort(403, 'Perfil de nutricionista não encontrado.');
        }

        $validated['nutritionist_id'] = $nutritionist->id;

        $patient = Patient::create($validated);

        return redirect()->route('patients.show-code', $patient)
            ->with('success', 'Paciente cadastrado com sucesso! Compartilhe o código abaixo.');
    }

    /**
     * Display the registration code for a patient.
     */
    public function showCode(Patient $patient)
    {
        // Verify ownership
        $nutritionist = auth()->user()->nutritionist;
        if (!$nutritionist || $patient->nutritionist_id !== $nutritionist->id) {
            abort(403, 'Você não tem permissão para ver este paciente.');
        }

        $registrationLink = route('patient-registration', ['code' => $patient->code]);

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
     */
    public function update(Request $request, Patient $patient)
    {
        // Verify ownership
        $nutritionist = auth()->user()->nutritionist;
        if (!$nutritionist || $patient->nutritionist_id !== $nutritionist->id) {
            abort(403, 'Você não tem permissão para editar este paciente.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:patients,email,' . $patient->id],
            'birth_date' => ['nullable', 'date'],
            'biological_sex' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'work_routine' => ['nullable', 'string'],
            'main_goal' => ['nullable', 'string'],
            'referral_source' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'calorie_target' => ['nullable', 'integer', 'min:0'],
            'clinical_history' => ['nullable', 'string'],
            'medical_notes' => ['nullable', 'string'],
        ]);

        $patient->update($validated);

        return redirect()->route('patients.edit', $patient)
            ->with('success', 'Paciente atualizado com sucesso!');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient)
    {
        // Verify ownership
        $nutritionist = auth()->user()->nutritionist;
        if (!$nutritionist || $patient->nutritionist_id !== $nutritionist->id) {
            abort(403, 'Você não tem permissão para deletar este paciente.');
        }

        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Paciente removido com sucesso!');
    }

    /**
     * Generate a unique registration code.
     */
    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Patient::where('code', $code)->exists());

        return $code;
    }
}
