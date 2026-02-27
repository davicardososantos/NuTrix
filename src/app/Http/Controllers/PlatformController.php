<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformController extends Controller
{
    /**
     * Entry point: redirect user based on role(s).
     */
    public function entry(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        $hasPatient = $user?->hasRole('patient') ?? false;
        $hasNutritionist = $user?->hasRole('nutritionist') ?? false;

        if ($hasPatient && $hasNutritionist) {
            return view('platform.select', [
                'hasPatient' => $hasPatient,
                'hasNutritionist' => $hasNutritionist,
            ]);
        }

        if ($hasNutritionist) {
            $request->session()->put('active_role', 'nutritionist');
            return redirect()->route('patients.index');
        }

        if ($hasPatient) {
            $request->session()->put('active_role', 'patient');
            return view('dashboard');
        }

        return view('dashboard');
    }

    /**
     * Show platform selection for users with both roles.
     */
    public function select(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        $hasPatient = $user?->hasRole('patient') ?? false;
        $hasNutritionist = $user?->hasRole('nutritionist') ?? false;

        if (!($hasPatient && $hasNutritionist)) {
            return $this->entry($request);
        }

        return view('platform.select', [
            'hasPatient' => $hasPatient,
            'hasNutritionist' => $hasNutritionist,
        ]);
    }

    /**
     * Persist selected platform and redirect.
     */
    public function set(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:patient,nutritionist',
        ]);

        $role = $validated['role'];

        if (!$request->user()?->hasRole($role)) {
            abort(403, 'Voce nao tem permissao para acessar esta plataforma.');
        }

        $request->session()->put('active_role', $role);

        return $role === 'nutritionist'
            ? redirect()->route('patients.index')
            : redirect()->route('water-consumptions.index');
    }
}
