<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveRole
{
    /**
     * Ensure the user has the required role and, if dual-role, the active role matches.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasRole($role)) {
            abort(403, 'Voce nao tem permissao para acessar esta area.');
        }

        $hasPatient = $user->hasRole('patient');
        $hasNutritionist = $user->hasRole('nutritionist');
        $hasBoth = $hasPatient && $hasNutritionist;

        if ($hasBoth) {
            $activeRole = $request->session()->get('active_role');

            if (!$activeRole || $activeRole !== $role) {
                return redirect()->route('portal.selecionar')
                    ->with('message', 'Escolha a plataforma que deseja acessar.');
            }
        }

        return $next($request);
    }
}
