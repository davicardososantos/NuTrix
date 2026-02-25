<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsNutritionist
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->hasRole('nutritionist')) {
            abort(403, 'Apenas nutricionistas podem acessar esta área.');
        }

        return $next($request);
    }
}
