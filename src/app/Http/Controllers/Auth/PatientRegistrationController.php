<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PatientRegistrationController extends Controller
{
    /**
     * Display the patient registration form with code.
     */
    public function create($code): View
    {
        $patient = Patient::where('code', $code)->firstOrFail();

        // If patient already has a user, redirect to login
        if ($patient->user_id) {
            return redirect()->route('login')->with('info', 'Este paciente já possui uma conta. Faça login.');
        }

        return view('auth.patient-register', compact('patient', 'code'));
    }

    /**
     * Handle an incoming patient registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $patient = Patient::where('code', $request->code)->firstOrFail();

        // Verify patient hasn't already registered
        if ($patient->user_id) {
            abort(403, 'Este paciente já possui uma conta registrada.');
        }

        // Check if email already exists
        $existingUser = User::where('email', $request->email)->first();

        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'weight' => ['nullable', 'numeric', 'min:0.1'],
            'height' => ['nullable', 'numeric', 'min:0.1'],
        ];

        // If email doesn't exist, email must be unique and password is required
        if (!$existingUser) {
            $validationRules['email'][] = 'unique:'.User::class;
            $validationRules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        } else {
            // If email exists, password is optional
            $validationRules['password'] = ['nullable', 'confirmed', Rules\Password::defaults()];
        }

        $validated = $request->validate($validationRules, [
            'email.unique' => 'Este email já está registrado no sistema.',
        ]);

        // If email exists, use existing user; otherwise create new one
        if ($existingUser) {
            $user = $existingUser;
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }

        // Update patient profile with weight, height and link user
        $patient->update([
            'user_id' => $user->id,
            'weight' => $request->weight,
            'height' => $request->height,
        ]);

        // Ensure patient role is assigned
        $patientRole = Role::where('name', 'patient')->first();
        if ($patientRole && !$user->hasRole('patient')) {
            $user->roles()->attach($patientRole);
        }

        if (!$existingUser) {
            event(new Registered($user));
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))
            ->with('success', 'Bem-vindo ao NuTrix Meta! Seu perfil foi criado com sucesso.');
    }
}
