<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeightController extends Controller
{
    /**
     * Store weight record (quick, no friction).
     * POST /weight
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
        ]);

        $patient = auth()->user()->patient;

        if (!$patient) {
            return back()->with('error', 'Perfil de paciente não encontrado.');
        }

        $patient->update(['weight' => $validated['weight']]);

        return back()->with('success', 'Peso atualizado com sucesso!');
    }
}
