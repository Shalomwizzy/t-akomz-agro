<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || (!$user->isAdmin() && !$user->hasRole('MANAGER'))) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
