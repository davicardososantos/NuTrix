<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Nutritionist;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if user already exists
        $existingUser = User::where('email', $request->email)->first();

        // If user exists and is already a nutritionist, reject registration
        if ($existingUser && $existingUser->hasRole('nutritionist')) {
            return redirect()->back()->with('error', 'Este email já está registrado como nutricionista.');
        }

        // Build validation rules
        $validationRules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'cpf' => ['required', 'string', 'size:11', 'unique:nutritionists,cpf'],
            'crn' => ['nullable', 'string', 'max:20', 'unique:nutritionists,crn'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        // If email doesn't exist, require password and email must be unique
        if (!$existingUser) {
            $validationRules['email'][] = 'unique:'.User::class;
            $validationRules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        } else {
            // If email exists, password is optional
            $validationRules['password'] = ['nullable', 'confirmed', Rules\Password::defaults()];
        }

        $request->validate($validationRules, [
            'cpf.unique' => 'Este CPF já está registrado no sistema.',
            'cpf.size' => 'O CPF deve conter 11 dígitos.',
            'crn.unique' => 'Este CRN já está registrado.',
            'email.unique' => 'Este email já está registrado.',
        ]);

        // Extract first name from full name
        $names = explode(' ', $request->full_name);
        $firstName = $names[0];

        // If user doesn't exist, create new user
        if (!$existingUser) {
            $user = User::create([
                'name' => $firstName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } else {
            $user = $existingUser;
        }

        // Create nutritionist profile with full name
        Nutritionist::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'cpf' => $request->cpf,
            'crn' => $request->crn ?? null,
            'phone' => $request->phone ?? null,
        ]);

        // Assign nutritionist role if not already assigned
        $nutritionistRole = Role::where('name', 'nutritionist')->first();
        if ($nutritionistRole && !$user->hasRole('nutritionist')) {
            $user->roles()->attach($nutritionistRole);
        }

        if (!$existingUser) {
            event(new Registered($user));
        }

        Auth::login($user);

        return redirect(route('painel', absolute: false));
    }
}
